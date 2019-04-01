<?php

namespace App\Http\Resources\Crm;

use Illuminate\Http\Resources\Json\JsonResource;

class EventListResource extends JsonResource
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
            'id', 'type', 'created_at', 'comment'
        ], [
            'userTo' => $this->userTo,
            'userFrom' => $this->userFrom,
        ]);
    }
}
