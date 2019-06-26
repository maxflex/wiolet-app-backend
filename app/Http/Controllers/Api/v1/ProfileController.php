<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\User\User;
use App\Http\Requests\Profile\UpdateRequest;

class ProfileController extends Controller
{
    public function show()
    {
        return new UserResource(auth()->user());
    }

    public function update(UpdateRequest $request)
    {
        auth()->user()->update($request->all());
        if (isset($request->preferences)) {
            auth()->user()->preferences()->update($request->preferences);
        }
        return new ProfileResource(auth()->user()->fresh());
    }

    public function delete()
    {
        auth()->user()->delete();
        return emptyResponse();
    }
}
