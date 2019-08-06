<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MessageRead;
use Illuminate\Notifications\Notifiable;
use App\Models\User\User;

class Message extends Model
{
    use Notifiable;

    protected $fillable = ['user_id_to', 'text', 'type', 'read_at', 'uid'];

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function setIsReadAttribute($value)
    {
        if ($value) {
            $this->attributes['read_at'] = now()->format(FORMAT_DATE_TIME);
        }
    }

    public function getIsReadAttribute()
    {
        return (bool) @$this->attributes['read_at'];
    }

    public function scopeMutual($query, int $userIdFrom, int $userIdTo)
    {
        return $query
            ->whereRaw("((user_id_from = {$userIdFrom} and user_id_to = {$userIdTo}) or (user_id_from = {$userIdTo} and user_id_to = {$userIdFrom}))");
    }

    public function scopeNew($query, $userIdFrom, $userIdTo)
    {
        if (is_array($userIdFrom)) {
            $query->whereIn('user_id_from', $userIdFrom);
        } else {
            $query->where('user_id_from', $userIdFrom);
        }
        return $query
            ->where('user_id_to', $userIdTo)
            ->whereNull('read_at');
    }

    public function routeNotificationForApn()
    {
        return $this->userTo->device_token;
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('read_at') && $model->read_at !== null) {
                event(new MessageRead($model));
            }
        });
    }
}
