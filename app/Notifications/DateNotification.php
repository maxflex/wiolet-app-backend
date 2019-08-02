<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use App\Models\Event\{Event, EventType};
use App\Models\User\Enums\Gender;

class DateNotification extends Notification
{
    public function via($notifiable)
    {
        return [ApnChannel::class];
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('У вас новое свидание!')
            ->body(sprintf(
                'Вы и %s понравились друг другу. Общайтесь и назначайте встречу',
                $notifiable->userFrom->name
            ));
    }
}
