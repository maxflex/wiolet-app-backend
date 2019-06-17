<?php

namespace App\Http\Middleware;

use Closure, Redis;

class SetOnline
{
    /**
     * Статус онлайн на 5 минут
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            Redis::set(cacheKey('online', auth()->id()), null, 'EX', 60 * 5);
            auth()->user()->last_seen = now()->format(FORMAT_DATE_TIME);
            auth()->user()->save();
        }
        return $next($request);
    }
}
