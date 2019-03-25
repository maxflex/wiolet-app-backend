<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name . ', ' . $this->region->name,
        ];
    }
}
