<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\User\User;
use App\Http\Requests\Profile\UpdateRequest;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response(
            new ProfileResource(auth()->user())
        )->header('Application-Outdated', $this->checkApplicationVersion($request));
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

    /**
     * Проверка версии приложения
     */
    private function checkApplicationVersion(Request $request)
    {
        return $request->headers->get('Application-Version') === config('app.version') ? 0 : 1;
    }
}
