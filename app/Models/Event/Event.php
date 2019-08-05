<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\{User, UserList};
use App\Http\Resources\Event\EventResource;
use Illuminate\Notifications\Notifiable;

class Event extends Model
{
    use Notifiable;

    protected $fillable = ['user_id_to', 'type', 'comment'];

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function scopeMutual($query, int $userIdFrom, int $userIdTo)
    {
        return $query
            ->whereRaw("((user_id_from = {$userIdFrom} and user_id_to = {$userIdTo}) or (user_id_from = {$userIdTo} and user_id_to = {$userIdFrom}))");
    }

    public static function getLatest(int $userIdFrom, int $userIdTo)
    {
        return self::where('user_id_from', $userIdFrom)->where('user_id_to', $userIdTo)->latest()->first();
    }

    /**
     * Пытаемся найти в какой список пришло событие
     * Нужно для того, чтобы по сокетам было понятно, куда определить event
     * К примеру, зашли в список "Свидания", падает новый event, и нужно понять, что этот event
     * относится именно к списку "свидания", чтобы сразу его в вверх списка добавить
     *
     * На самом деле, событие может упасть либо в "свидания" либо в "Хотят встретиться с вами"
     */
    public function associateWithList()
    {
        // Event закидываем только в случае лайка
        if ($this->type) {
            // Если лайк был от нас, то в "свидания"
            return optional(self::getLatest($this->user_id_to, $this->user_id_from))->type === EventType::LIKE ?
                UserList::DATES :
                UserList::WANT_TO_MEET_YOU;
        }
        return null;
    }

    public function routeNotificationForApn()
    {
        return $this->userTo->device_token;
    }
}
