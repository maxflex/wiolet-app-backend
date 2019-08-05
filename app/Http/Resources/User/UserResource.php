<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\{
    City\CityResource,
    Event\EventResource,
    Photo\PhotoResource
};
use App\Models\{Event\Event, Message, User\User};

class UserResource extends JsonResource
{
    public function toArray($request)
    {

        $latestEventFromMe = Event::getLatest(auth()->id(), $this->id);
        $latestEventFromThem = Event::getLatest($this->id, auth()->id());

        return extractFields($this, [
            'id', 'name', 'gender', 'birthdate', 'about',
            'height', 'weight', 'phone', 'is_online', 'university',
            'body_type', 'hair_color', 'eye_color', 'kids', 'is_hidden',
            'lives', 'alcohol', 'smoking', 'company', 'occupation', 'last_seen'
        ], [
            'is_banned' => User::query()
                ->whereId($this->id)
                ->bannedBy(auth()->id())
                ->exists(),

            'city' => new CityResource($this->city),

            'photos' => PhotoResource::collection($this->photos),

            'events' => [
                'me' => $latestEventFromMe === null ? null : new EventResource($latestEventFromMe),
                'them' => $latestEventFromThem === null ? null : new EventResource($latestEventFromThem),
            ],

            'total_messages' => Message::query()
                ->where('user_id_from', $this->id)
                ->where('user_id_to', auth()->id())
                ->count(),

            'new_messages' => Message::new($this->id, auth()->id())->count(),

            'is_liked' => Event::query()
                ->where('user_id_from', auth()->id())
                ->where('user_id_to', $this->id)
                ->exists()
        ]);
    }
}
