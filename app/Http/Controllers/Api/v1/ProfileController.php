<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;

class ProfileController extends Controller
{
    public function show()
    {
        return new UserResource(auth()->user());
    }

    public function update(Request $request)
    {
        auth()->user()->update($request->all());
        if (isset($request->preferences)) {
            auth()->user()->preferences()->update($request->preferences);
        }
        return new UserResource(auth()->user());
    }
}
