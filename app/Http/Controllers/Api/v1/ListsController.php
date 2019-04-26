<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\EventType;
use App\Models\User\{User, UserList};
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

        $items = $this->getList(new UserList($request->name))->get();

        return UserListResource::collection($items);
    }

    /**
     * Получить ID всех пользователей в списках
     */
    public function counts()
    {
        $result = [];
        foreach(UserList::toArray() as $listName) {
            $result[$listName] = $this->getList(new UserList($listName))->pluck('id');
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
