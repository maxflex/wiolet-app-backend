<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MessageRead;

class Message extends Model
{
    protected $fillable = ['user_id_to', 'text', 'type', 'read_at', 'status', 'uid'];

    public function scopeMutual($query, int $userIdFrom, int $userIdTo)
    {
        return $query
            ->whereRaw("((user_id_from = {$userIdFrom} and user_id_to = {$userIdTo}) or (user_id_from = {$userIdTo} and user_id_to = {$userIdFrom}))");
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === 'read') {
                $model->read_at = now()->format(FORMAT_DATE_TIME);
                event(new MessageRead($model));
            }
        });
    }
}
