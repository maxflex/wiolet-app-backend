<?php

namespace App\Http\Controllers\Api\crm;

use Illuminate\Http\Request;
use App\Http\Controllers\CrmController as Controller;
use App\Models\Event\Event;
use App\Http\Resources\Crm\EventListResource;

class EventsController extends Controller
{
    protected $filters = [
        'multiple' => ['type']
    ];

    public function index(Request $request)
    {
        $query = Event::query();
        $this->filter($request, $query);
        return EventListResource::collection(
            $this->showBy($request, $query)
        );
    }
}
