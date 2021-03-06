<?php

namespace App\Http\Controllers\Api\crm;

use Illuminate\Http\Request;
use App\Http\Controllers\CrmController as Controller;
use App\Models\User\User;
use App\Http\Resources\{Crm\UserListResource, Crm\UserResource};

class UsersController extends Controller
{
    protected $filters = [
        'multiple' => ['gender']
    ];

    public function index(Request $request)
    {
        $query = User::query();
        $this->filter($request, $query);
        return UserListResource::collection(
            $this->showBy($request, $query)
        );
    }

    public function show($id)
    {
        $item = User::find($id);
        return new UserResource($item);
    }

    public function update($id, Request $request)
    {
        $item = User::find($id);
        $item->update($request->all());
        $item->preferences()->update($request->preferences);
        foreach($request->photos as $photo) {
            if (isset($photo['is_deleted'])) {
                $item->photos->where('id', $photo['id'])->first()->delete();
            }
        }
        $item->save();
        return new UserResource($item);
    }
}
