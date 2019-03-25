<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    const UPLOAD_PATH = 'photos/';

    protected $fillable = ['filename', 'position'];

    public function getUrlAttribute()
    {
        return config('app.url') . 'storage/' . self::UPLOAD_PATH . $this->filename;
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->position = self::where('user_id', $model->user_id)->max('position') + 1;
        });
    }
}
