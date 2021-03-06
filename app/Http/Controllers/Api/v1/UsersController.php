<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Http\Resources\User\UserResource;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'ids' => ['required']
        ]);

        return UserResource::collection(
            User::whereIn('id', explode(',', $request->ids))->get()
        );
    }

    public function show($id)
    {
        $item = User::find($id);
        return new UserResource($item);
    }
}
