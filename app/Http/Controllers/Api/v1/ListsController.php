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

        $items = User::{toCamelCase($request->name) . 'List'}(auth()->id())->get();

        return UserListResource::collection($items);
    }
}
