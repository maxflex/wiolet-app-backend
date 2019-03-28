<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class UserListResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'gender'
        ], [
            'photo' => count($this->photos) > 0 ? new PhotoResource($this->photos[0]) : null,
        ]);
    }
}
