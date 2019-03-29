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
            'photo_url' => count($this->photos) > 0 ? $this->photos[0]->url : null,
        ]);
    }
}
