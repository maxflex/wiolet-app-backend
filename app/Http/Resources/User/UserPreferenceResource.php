<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    public function toArray($request)
    {
        // вообще тут должно быть всё, кроме ID
        return extractFields($this, [
            'gender', 'age_from', 'age_to'
        ]);
    }
}
