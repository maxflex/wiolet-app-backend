<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['text', 'type'];
}
