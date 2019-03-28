<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\{Photo\PhotoResource};

class CardResource extends JsonResource
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
            'id', 'name', 'about', 'gender', 'birthdate', 'height', 'weight'
        ], [
            'city' => $this->city->name . ', ' . $this->city->region->name,
            'photos' => PhotoResource::collection($this->photos),
        ]);
    }
}
