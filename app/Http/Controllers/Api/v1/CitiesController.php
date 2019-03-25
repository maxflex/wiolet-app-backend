<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Geo\City;
use App\Http\Resources\City\CityResource;
use App\Http\Requests\City\IndexRequest;

class CitiesController extends Controller
{
    public function index(IndexRequest $request)
    {
        return CityResource::collection(
            City::where('name', 'like', '%' . $request->name . '%')->take(10)->get()
        );
    }
}
