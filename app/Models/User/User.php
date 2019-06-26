<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Utils\Phone;
use App\Models\{
    Photo,
    Event\Event,
    Geo\City,
    Feedback\Feedback
};
use App\Models\User\Enums\Gender;
use Redis;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, UserScopes;

    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'gender',
        'phone', 'city_id', 'about', 'height', 'weight',
        'body_type', 'hair_color', 'eye_color', 'kids',
        'lives', 'alcohol', 'smoking', 'company', 'occupation',
        'university', 'is_hidden'
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
        return $this->hasMany(Photo::class)->orderBy('position');
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id_from');
    }

    public function getIsOnlineAttribute()
    {
        return Redis::get(cacheKey('online', $this->id)) !== null;
    }

    public function setPasswordAttribute($password)
    {
        if (! empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = Phone::clean($value);
    }

    public function getAgeAttribute()
    {
        // это бомжатство вследствие того, что в хуке created дата
        // возвращается как DateTime, а во всех остальных как String
        // (воспроизводится в UserFactory)
        if ($this->birthdate instanceof \Datetime) {
            $birthdate = $this->birthdate->format(FORMAT_DATE);
        } else {
            $birthdate = $this->birthdate;
        }
        return intval(date('Y')) - intval(date('Y', strtotime($birthdate)));
    }

    /**
     * Предпочтения по умолчанию
     */
    private function getDefaultPreferences()
    {
        $defaultPreferences['gender'] = $this->gender === Gender::MALE ? Gender::FEMALE : Gender::MALE;

        if ($this->age === 18) {
            $defaultPreferences['age_from'] = 18;
            $defaultPreferences['age_to'] = 20;
        } else if ($this->gender === Gender::MALE) {
            $defaultPreferences['age_from'] = $this->age - 10;
            if ($defaultPreferences['age_from'] < 18) {
                $defaultPreferences['age_from'] = 18;
            }
        } else  {
            $defaultPreferences['age_from'] = $this->age + 10;
            if ($defaultPreferences['age_from'] > 75) {
                $defaultPreferences['age_from'] = 75;
            }
        }

        return $defaultPreferences;
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->preferences()->create($model->getDefaultPreferences());
        });
    }
}
