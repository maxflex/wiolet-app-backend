<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;

class IncomingMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastAs()
    {
        return 'message.incoming';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user-channel.' . $this->message->user_id_to);
        // return new PrivateChannel('user-channel.' . $this->message->user_id_to);
    }

    public function broadcastWith()
    {
        // return new MessageResource($this->message);
        return [
            'id' => $this->message->id,
            'text' => $this->message->text,
            'user_id_to' => $this->message->user_id_to,
        ];
    }
}
