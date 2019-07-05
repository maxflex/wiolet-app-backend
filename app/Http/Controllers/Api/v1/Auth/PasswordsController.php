<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use Redis, Mail;
use App\Models\User\User;


class PasswordsController extends Controller
{
    const CACHE_KEY = 'password-resets';

    /**
     * Получить токен для изменениия пароля
     */
    public function getToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $token = md5($request->email . mt_rand(1000, 9999));
        Mail::to($request->email)->send(new ResetPassword([
            'email' => $request->email,
            'token' => $token,
            'time' => time()
        ]));
        Redis::set(cacheKey(self::CACHE_KEY, $token), $request->email, 'EX', 60 * 10);
        return emptyResponse();
    }

    /**
     * Изменить пароль
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => [
                'required',
                function($attribute, $value, $fail) {
                    if (Redis::get(cacheKey(self::CACHE_KEY, $value)) === null) {
                        return $fail(__('passwords.token'));
                    }
                },
            ],
            'password' => ['required', 'confirmed']
        ]);

        $email = Redis::get(cacheKey(self::CACHE_KEY, $request->token));

        $user = User::where('email', $email)->first();
        $user->password = $request->password;
        $user->save();

        // сразу возвращаем JWT token
        $request->merge(compact('email'));
        return app()->call(AuthController::class . '@login');
    }
}
