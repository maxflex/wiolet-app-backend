<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;

class TestPushNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        logger('we are inside TestPushNotification');
    }

    public function via($notifiable)
    {
        return [ApnChannel::class, 'database'];
    }

    public function toApn($notifiable)
    {
        logger('toApn');
        return ApnMessage::create()
            ->badge(1)
            ->title('Account approved')
            ->body("Your account was approved!");
    }

    public function toDatabase($notifiable)
    {
        logger('toDatabase');
        return [
            'test' => 1
        ];
    }
}
