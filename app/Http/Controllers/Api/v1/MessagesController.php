<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Events\IncomingMessage;
use App\Http\Resources\Message\MessageResource;
use App\Notifications\IncomingMessageNotification;
use User, DB;

class MessagesController extends Controller
{
    /**
     * Список чатов
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'latest_message_created_at' => 'nullable|date_format:' . FORMAT_DATE_TIME
        ]);

        $userId = auth()->id();

        $ids = collect(DB::select("
            select max(`id`) as `id` from messages
            where (user_id_from = {$userId} or user_id_to = {$userId})
            group by if(user_id_from = {$userId}, user_id_to, user_id_from)
        "))->pluck('id');

        $query = Message::whereIn('id', $ids)->orderBy('created_at', 'desc')->take(20);

        // кастомная пагинация, потому что может прийти новое сообщение во время просмотра списка чатов
        if (isset($request->latest_message_created_at)) {
            $query->where('created_at', '>', $request->latest_message_created_at);
        }

        return MessageResource::collection($query->get());
    }

    /**
     * Диалог с пользователем
     */
    public function show($userId)
    {
        $me = auth()->id();

        // отметить сообщения прочитанными
        Message::query()
            ->whereRaw("(user_id_from = {$userId} and user_id_to = {$me})")
            ->update([
                'read_at' => now()->format(FORMAT_DATE_TIME)
            ]);


        $items = Message::query()
            ->whereRaw("((user_id_from = {$me} and user_id_to = {$userId}) or (user_id_from = {$userId} and user_id_to = {$me}))")
            ->orderBy('created_at', 'asc')
            ->get();

        return MessageResource::collection($items);
    }

    /**
     * Отправить сообщение
     */
    public function store(Request $request)
    {
        // TODO: реализовать лимит на сообщения,
        // сделать MessageStoreRequest
        $this->validate($request, [
            'text' => ['required', 'string'],
            'uid' => ['required', 'unique:messages'],
            'user_id_to' => ['required', 'exists:users,id']
        ]);

        $item = new Message($request->all());
        $item->user_id_from = auth()->id();
        $item->save();

        $item->notify(new IncomingMessageNotification);

        event(new IncomingMessage($item));

        return new MessageResource($item);
    }

    public function update(Request $request, Message $message)
    {
        $message->update($request->all());
        return emptyResponse();
    }
}
