<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    const UPLOAD_PATH = 'photos/';

    protected $fillable = ['position'];

    public function getUrlAttribute()
    {
        return config('app.url') . 'storage/' . self::UPLOAD_PATH . self::getFilename($this);
    }

    public function getThumbUrlAttribute()
    {
        return config('app.url') . 'storage/' . self::UPLOAD_PATH . self::getFilename($this, true);
    }

    public static function getFilename($item, $thumb = false)
    {
        // 13.jpg
        // 13_thumb.jpg
        return sprintf(
            "%s%s.jpg",
            $item->id,
            ($thumb ? '_thumb' : '')
        );
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->position = self::where('user_id', $model->user_id)->max('position') + 1;
        });
    }
}
