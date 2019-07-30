<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;
use App\Http\Resources\City\CityResource;
use App\Http\Resources\User\UserPreferenceResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'email', 'gender', 'birthdate', 'about',
            'height', 'weight', 'phone', 'is_online', 'job', 'education',
            'body_type', 'hair_color', 'eye_color', 'kids', 'is_hidden',
            'lives', 'alcohol', 'smoking', 'notifications'
        ], [
            'city' => new CityResource($this->city),
            'photos' => PhotoResource::collection($this->photos),
            'preferences' => new UserPreferenceResource($this->preferences),
        ]);
    }
}
