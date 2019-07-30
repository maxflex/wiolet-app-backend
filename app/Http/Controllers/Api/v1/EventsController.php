<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event\{Event, EventType};
use App\Http\Resources\Event\EventResource;
use App\Events\IncomingEvent;
use App\Notifications\TestPushNotification;

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

        $item->notify(new TestPushNotification());

        event(new IncomingEvent($item));

        return new EventResource($item);
    }
}
