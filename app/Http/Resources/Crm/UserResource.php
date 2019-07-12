<?php

namespace App\Http\Resources\Crm;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'gender', 'birthdate', 'about',
            'height', 'weight', 'phone', 'is_online', 'university',
            'body_type', 'hair_color', 'eye_color', 'kids', 'is_hidden',
            'lives', 'alcohol', 'smoking', 'company', 'occupation', 'last_seen',
            'preferences', 'email'
        ], [
            'photos' => PhotoResource::collection($this->photos),
        ]);
    }
}
