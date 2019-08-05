<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\{User, UserList};

class UserListView extends Model
{
    public $timestamps = false;

    protected $fillable = ['list', 'viewed_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
