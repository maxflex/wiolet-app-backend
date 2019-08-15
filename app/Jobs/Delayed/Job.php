<?php

namespace App\Jobs\Delayed;
use App\Models\DelayedJob;

/**
 * Отложенные задачи
 */

abstract class Job
{
    abstract public function handle($params);

    /**
     * @param StdObject $params параметры
     * @param int $delay задержка в минутах
     */
    public static function dispatch($params, int $delay)
    {
        DelayedJob::dispatch(
            static::class,
            $params,
            $delay
        );
    }
}
