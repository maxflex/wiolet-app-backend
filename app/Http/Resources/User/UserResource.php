<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;
use App\Http\Resources\City\CityResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'email', 'gender', 'birthdate', 'about',
            'height', 'weight', 'phone', 'is_online', 'university',
            'body_type', 'hair_color', 'eye_color', 'kids',
            'lives', 'alcohol', 'smoking', 'company', 'occupation',
        ], [
            'city' => new CityResource($this->city),
            'photos' => PhotoResource::collection($this->photos),
            'preferences' => new UserPreferenceResource($this->preferences),
        ]);
    }
}
