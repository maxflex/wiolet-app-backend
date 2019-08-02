<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\{Event, EventType};
use App\Http\Resources\Event\EventResource;
use App\Events\IncomingEvent;
use App\Notifications\TestPushNotification;
use User;

class EventsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id_to' => ['required', 'exists:users,id'],
            'type' => [
                'required',
                function($attribute, $value, $fail) {
                    if (! EventType::isValid($value)) {
                        return $fail(__('validation.events.wrong-type'));
                    }
                }
            ],
        ]);

        $item = auth()->user()->events()->create($request->all());

        if ($item->type === EventType::LIKE()) {
            // Если существует взаимный лайк – уведомление о списке "свидания"
            if (Event::query()
                ->where('user_id_from', $item->user_id_to)
                ->where('user_id_to', auth()->id())
                ->where('type', EventType::LIKE())
                ->exists()
            ) {
                $item->notify(new DateNotification());
            } else {
                $item->notify(new LikeNotification());
            }
        }

        event(new IncomingEvent($item));

        return new EventResource($item);
    }
}
