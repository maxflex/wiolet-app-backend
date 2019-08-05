<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\EventType;
use App\Models\User\{User, UserListView, UserList};
use App\Models\Message;

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
            ]
        ]);

        $listName = $request->name;

        $items = $this->getList(new UserList($listName))->paginate(20);

        auth()->user()->listViews()->where('list', $listName)->delete();

        $this->getList(new UserList($listName))->pluck('id')->each(function ($userId) use ($listName) {
            auth()->user()->listViews()->create([
                'viewed_user_id' => $userId,
                'list' => $listName,
            ]);
        });

        return UserListResource::collection($items);
    }

    /**
     * Получить ID всех пользователей в списках
     */
    public function counts()
    {
        $result = [];
        foreach(UserList::toArray() as $listName) {
            $userIds = $this->getList(new UserList($listName))->pluck('id')->all();
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

    /**
     * Получить список
     *
     * @param UserList $list название списка
     */
    private function getList(UserList $list)
    {
        return User::{toCamelCase($list->getValue()) . 'List'}(auth()->id());
    }
}
