<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserSeenCard extends Model
{
    protected $fillable = ['user_id', 'card_id'];
}
