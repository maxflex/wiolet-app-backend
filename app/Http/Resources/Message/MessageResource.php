<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'text', 'user_id_from', 'user_id_to', 'status', 'read_at', 'type', 'uid'
        ]);
    }
}
