<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public $timestamps = false;

    protected $fillable = ['gender', 'age_from', 'age_to'];
}
