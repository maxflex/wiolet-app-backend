<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redis;
use App\Utils\{Sms, Phone};
use App\Http\Requests\Auth\{SendCodeRequest, VerifyCodeRequest};

class VerifyController extends Controller
{
    /**
     * Отправить код подтверждения на телефон
     */
    public function sendCode(SendCodeRequest $request)
    {
        // Код уже был отправлен и ещё не истек
        if (Redis::get(cacheKey('codes', $request->phone)) !== null) {
            return errorResponse([
                'ttl' => Redis::ttl(cacheKey('codes', $request->phone))
            ]);
        }
        $phone = Phone::clean($request->phone);
        $code = mt_rand(10000, 99999);
        Redis::set(cacheKey('codes', $phone), $code, 'EX', 120);
        Sms::send($phone, __('auth.sms-code', compact('code')), false);
        // return response()->json(compact('code'));
        return emptyResponse();
    }

    /**
     * Подтвердить код
     */
    public function verifyCode(VerifyCodeRequest $request)
    {
        Redis::del(cacheKey('codes', $request->phone));
        return emptyResponse();
    }
}
