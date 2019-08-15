<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
// use NotificationChannels\Apn\ApnChannel;
// use NotificationChannels\Apn\ApnMessage;
use SemyonChetvertnyh\ApnNotificationChannel\ApnMessage;
use App\Models\Event\{Event, EventType};
use App\Models\User\Enums\Gender;

class LikeNotification extends Notification
{
    public function via($notifiable)
    {
        return ['apn'];
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('Вы кому-то понравились!')
            ->body(sprintf(
                '%s %s симпатию. Не упустите шанс завести новое  знакомство',
                $notifiable->userFrom->name,
                $notifiable->userFrom->gender === Gender::FEMALE ? 'проявила' : 'проявил'
            ))
            ->threadId('event')
            ->custom('acme', [
                'event-type' => $notifiable->userFrom->associateWithList($notifiable->userTo->id),
            ]);;
    }
}
