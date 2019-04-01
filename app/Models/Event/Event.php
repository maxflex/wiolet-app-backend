<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

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
}
