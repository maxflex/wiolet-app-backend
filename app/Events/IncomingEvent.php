<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\{Event\Event, User\User};
use App\Http\Resources\{Event\EventResource, User\UserShortResource};

class IncomingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function broadcastAs()
    {
        return 'event';
    }

    public function broadcastOn()
    {
        return new Channel('user-channel.' . $this->event->user_id_to);
        // return new PrivateChannel('user-channel.' . $this->event->user_id_to);
    }

    public function broadcastWith()
    {
        $latestEventFromMe = Event::getLatest($this->event->user_id_to, $this->event->user_id_from);
        $latestEventFromThem = Event::getLatest($this->event->user_id_from, $this->event->user_id_to);
        return [
            'user' => new UserShortResource(User::find($this->event->user_id_from)),
            'events' => [
                'me' => $latestEventFromMe === null ? null : new EventResource($latestEventFromMe),
                'them' => $latestEventFromThem === null ? null : new EventResource($latestEventFromThem),
            ]
        ];
    }
}
