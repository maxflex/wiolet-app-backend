<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
// use NotificationChannels\Apn\ApnChannel;
// use NotificationChannels\Apn\ApnMessage;
use App\Models\User\Enums\Gender;
use SemyonChetvertnyh\ApnNotificationChannel\ApnMessage;
class IncomingMessageNotification extends Notification
{
    public function via($notifiable)
    {
        return ['apn'];
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
            ))
            ->threadId('message')
            ->custom('acme', [
                'id' => $notifiable->userFrom->id,
                'name' => $notifiable->userFrom->name,
                'birthdate' => $notifiable->userFrom->birthdate,
                'last_seen' => $notifiable->userFrom->last_seen,
                'is_online' => $notifiable->userFrom->is_online,
                'image_url' => optional($notifiable->userFrom->photos)->first()->thumb_url,
            ]);
    }
}
