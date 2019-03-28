<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Utils\Phone;
use App\Models\{Photo, Event\Event, Geo\City};

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, UserScopes;

    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'gender',
        'phone', 'city_id', 'about', 'height', 'weight',
    ];

    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function seen()
    {
        return $this->hasManyThrough(User::class, UserSeenCard::class, 'user_id', 'id', 'id', 'card_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id_from');
    }

    public function setPasswordAttribute($password)
    {
        if (! empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function see(User $card)
    {
        UserSeenCard::create([
            'user_id' => $this->id,
            'card_id' => $card->id,
        ]);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = Phone::clean($value);
    }

    public function getAgeAttribute()
    {
        return intval(date('Y')) - intval(date('Y', strtotime($this->birthdate)));
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->preferences()->create([
                'gender' => $model->gender === 'male' ? 'female' : 'male'
            ]);
        });
    }
}
