<?php

namespace App\Http\Resources\Crm;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class UserListResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'gender', 'created_at', 'email', 'city'
        ]);
    }
}
