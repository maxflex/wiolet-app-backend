<?php

namespace App\Jobs\Delayed;

use App\Events\OnlineChanged;
use App\Models\User\User;
use Redis;

/**
 * Уведомление о логауте
 */

class SetOffline extends Job
{
    public function handle($params)
    {
        $user = User::find($params->user_id);
        Redis::del(cacheKey('online', $user->id));
        event(new OnlineChanged($user));
    }
}
