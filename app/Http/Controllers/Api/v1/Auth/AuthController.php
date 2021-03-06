<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Broadcast;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\PhotosController;
use App\Http\Requests\Auth\{RegisterRequest};

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return errorMessageResponse(__('auth.wrong-credentials'), 403);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return emptyResponse();
    }

    /**
     * Авторизация приватных каналов для сокетов
     *
     * пока что используется pusher
     */
    public function sockets(Request $request)
    {
        return Broadcast::auth($request);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            // 'token_type'   => 'bearer',
            // 'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }
}
