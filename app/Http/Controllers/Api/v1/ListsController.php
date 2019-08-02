<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\EventType;
use App\Models\User\{User, UserList};
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

        $items = $this->getList(new UserList($request->name))->paginate(20);

        return UserListResource::collection($items);
    }

    /**
     * Получить ID всех пользователей в списках
     */
    public function counts()
    {
        $result = [];
        foreach(UserList::toArray() as $listName) {
            $userIds =  $this->getList(new UserList($listName))->pluck('id');
            $result[$listName] = [
                'user_ids' => $userIds,
                'new_messages' => Message::new($userIds->all(), auth()->id())->count(),
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
