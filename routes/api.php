<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api\crm')->prefix('crm')->middleware('auth:api')->group(function() {
    Route::get('initial-data', 'InitialDataController@index');

    Route::apiResources([
        'users' => 'UsersController',
        'events' => 'EventsController',
    ]);
});

Route::namespace('Api\v1')->prefix('v1')->group(function () {
    Route::apiResource('cities', 'CitiesController');

    Route::prefix('auth')->namespace('Auth')->group(function() {
        Route::post('refresh', 'AuthController@refresh');
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::prefix('sms')->group(function() {
            Route::post('send-code', 'VerifyController@sendCode');
            Route::post('verify-code', 'VerifyController@verifyCode');
        });

        Route::prefix('password')->group(function() {
            Route::post('get-token', 'PasswordsController@getToken');
            Route::post('reset', 'PasswordsController@reset');
        });


        Route::options('sockets', function() {
            return response(null, 200);
        });
        Route::post('sockets', 'AuthController@sockets');
    });

    Route::middleware(['auth:api', 'online'])->group(function() {
        Route::apiResources([
            'photos' => 'PhotosController',
            'messages' => 'MessagesController',
        ]);

        Route::post('events', 'EventsController@store');

        Route::get('profile', 'ProfileController@show');
        Route::put('profile', 'ProfileController@update');

        Route::prefix('cards')->group(function() {
           Route::get('show', 'CardsController@show');
        });

        Route::get('lists', 'ListsController@index');
    });
});
