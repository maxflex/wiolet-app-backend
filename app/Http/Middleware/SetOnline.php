<?php

namespace App\Http\Middleware;

use App\Events\OnlineChanged;
use App\Jobs\Delayed\SetOffline;
use Closure, Redis;

class SetOnline
{
    /**
     * Статус онлайн на 5 минут
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $wasOnline = auth()->user()->is_online;
            Redis::set(cacheKey('online', auth()->id()), null, 'EX', 60 * 1);
            auth()->user()->last_seen = now()->format(FORMAT_DATE_TIME);
            auth()->user()->save();
            if (! $wasOnline) {
                event(new OnlineChanged(auth()->user()));
            }
            SetOffline::dispatch([
                'user_id' => auth()->id(),
            ], 5);
        }
        return $next($request);
    }
}
