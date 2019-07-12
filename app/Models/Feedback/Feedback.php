<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use App\Mail\FeedbackMail;
use Mail;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = ['text', 'type'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Mail::to('support@unscreed.com')->send(new FeedbackMail($model));
        });
    }
}
