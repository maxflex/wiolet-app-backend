<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Geo\City;
use App\Http\Resources\City\CityResource;
use App\Http\Requests\Location\{CitiesRequest, DetermineRequest};

class LocationController extends Controller
{
    public function determine(DetermineRequest $request)
    {
        // TODO: добавить реальное автоопределение
        return new CityResource(City::find(365));
    }

    public function cities(CitiesRequest $request)
    {
        return CityResource::collection(
            City::where('name', 'like', '%' . $request->name . '%')->take(10)->get()
        );
    }
}
