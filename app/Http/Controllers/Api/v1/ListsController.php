<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\EventType;
use App\Models\User\{User, UserListView, UserList};
use App\Models\Message;
use DB;

use App\Http\Resources\User\UserListResource;

class ListsController extends Controller
{
    /**
     * Получить список пользователей
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'in:' . implode(',', UserList::values())
            ],
            'latest_created_at' => 'nullable|date_format:' . FORMAT_DATE_TIME
        ]);

        $listName = $request->name;

        $query = User::getList(new UserList($listName), auth()->id())
            ->select(DB::raw("users.*, (select max(created_at) from events where events.user_id_from = users.id) as latest_created_at"))
            ->orderBy('latest_created_at', 'desc');

        // кастомная пагинация, потому что может прийти новое сообщение во время просмотра списка чатов
        if (isset($request->latest_created_at)) {
            $query->whereRaw("(select max(created_at) from events where events.user_id_from = users.id) > '{$request->latest_created_at}'");
        }

        // засчитать просмотр списка
        auth()->user()->listViews()->where('list', $listName)->delete();
        User::getList(new UserList($listName), auth()->id())->pluck('id')->each(function ($userId) use ($listName) {
            auth()->user()->listViews()->create([
                'viewed_user_id' => $userId,
                'list' => $listName,
            ]);
        });

        return UserListResource::collection($query->paginate(20));
    }

    /**
     * Получить ID всех пользователей в списках
     */
    public function counts()
    {
        $result = [];
        foreach(UserList::toArray() as $listName) {
            $userIds = User::getList(new UserList($listName), auth()->id())->pluck('id')->all();
            $result[$listName] = [
                'all_users' => count($userIds),
                'new_users' => count(array_diff(
                    $userIds,
                    auth()->user()->listViews()->where('list', $listName)->pluck('viewed_user_id')->all()
                )),
                'new_messages' => Message::new($userIds, auth()->id())->count(),
            ];
        }
        return $result;
    }
}
