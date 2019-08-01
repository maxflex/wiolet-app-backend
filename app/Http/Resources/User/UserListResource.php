<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\{Photo\PhotoResource, Message\MessageResource};
use App\Models\Message;

class UserListResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'name', 'gender', 'is_online', 'birthdate', 'last_seen'
        ], [
            'photo_url' => count($this->photos) > 0 ? $this->photos[0]->thumb_url : null,
            'new_messages' => Message::new($this->id, auth()->id())->count(),
            'last_message' => new MessageResource(
                Message::mutual($this->id, auth()->id())->latest()->first()
            )
        ]);
    }
}
