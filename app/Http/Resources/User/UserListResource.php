<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\{Photo\PhotoResource, Message\MessageResource, Event\EventResource};
use App\Models\{Message, Event\Event};

class UserListResource extends JsonResource
{
    public function toArray($request)
    {
        $latestEventFromMe = Event::getLatest(auth()->id(), $this->id);
        $latestEventFromThem = Event::getLatest($this->id, auth()->id());

        return extractFields($this, [
            'id', 'name', 'gender', 'is_online', 'birthdate', 'last_seen', 'latest_created_at'
        ], [
            'photo_url' => count($this->photos) > 0 ? $this->photos[0]->thumb_url : null,
            'new_messages' => Message::new($this->id, auth()->id())->count(),
            'last_message' => new MessageResource(
                Message::mutual($this->id, auth()->id())->latest()->first()
            ),
            'events' => [
                'me' => $latestEventFromMe === null ? null : new EventResource($latestEventFromMe),
                'them' => $latestEventFromThem === null ? null : new EventResource($latestEventFromThem),
            ]
        ]);
    }
}
