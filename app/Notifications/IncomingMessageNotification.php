<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use App\Models\User\Enums\Gender;

class IncomingMessageNotification extends Notification
{
    public function via($notifiable)
    {
        return [ApnChannel::class];
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(0)
            ->title('Новое сообщение')
            ->body(sprintf(
                'Вам %s %s. Не забудьте ответить!',
                $notifiable->userFrom->gender === Gender::FEMALE ? 'написала' : 'написал',
                $notifiable->userFrom->name
            ));
    }
}
