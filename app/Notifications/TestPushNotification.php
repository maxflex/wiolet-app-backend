<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;

class TestPushNotification extends Notification
{
    public function via($notifiable)
    {
        return [ApnChannel::class];
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('Вы кому-то понравились')
            ->body("Откройте приложение, чтобы узнать");
    }
}
