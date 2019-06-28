<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Http\Resources\Event\EventResource;

class Event extends Model
{
    protected $fillable = ['user_id_to', 'type', 'comment'];

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function scopeMutual($query, int $userIdFrom, int $userIdTo)
    {
        return $query
            ->whereRaw("((user_id_from = {$userIdFrom} and user_id_to = {$userIdTo}) or (user_id_from = {$userIdTo} and user_id_to = {$userIdFrom}))");
    }

    public static function getLatest(int $userIdFrom, int $userIdTo)
    {
        return self::where('user_id_from', $userIdFrom)->where('user_id_to', $userIdTo)->latest()->first();
    }
}
