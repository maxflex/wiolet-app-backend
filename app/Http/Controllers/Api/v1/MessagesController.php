<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Events\IncomingMessage;
use App\Http\Resources\Message\MessageResource;
use User, DB;

class MessagesController extends Controller
{
    /**
     * Список чатов
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $ids = DB::select("
            select max(`id`) as `id` from messages
            where (user_id_from = {$userId} or user_id_to = {$userId})
            group by if(user_id_from = {$userId}, user_id_to, user_id_from)
        ");

        $ids = array_map(function($e) {
            return $e->id;
        }, $ids);

        $items = Message::whereIn('id', $ids)->orderBy('created_at', 'desc')->get();

        return MessageResource::collection($items);
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
            'user_id_to' => ['required', 'exists:users,id']
        ]);

        $item = new Message($request->all());
        $item->user_id_from = auth()->id();
        $item->save();

        event(new IncomingMessage($item));

        return new MessageResource($item);
    }
}
