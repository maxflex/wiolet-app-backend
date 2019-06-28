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
    Route::prefix('location')->group(function () {
        Route::get('cities', 'LocationController@cities');
        Route::get('determine', 'LocationController@determine');
    });

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

        Route::get('social/{service}', 'SocialController@register');
    });

    Route::middleware(['auth:api', 'online'])->group(function() {

        Route::put('photos', 'PhotosController@update');
        Route::apiResources([
            'feedback' => 'FeedbackController',
            'users' => 'UsersController',
            'photos' => 'PhotosController',
            'messages' => 'MessagesController',
        ]);

        Route::post('events', 'EventsController@store');

        Route::get('profile', 'ProfileController@show');
        Route::put('profile', 'ProfileController@update');
        Route::delete('profile', 'ProfileController@delete');

        Route::prefix('cards')->group(function() {
           Route::get('show', 'CardsController@show');
           Route::post('see', 'CardsController@see');
        });

        Route::get('lists/counts', 'ListsController@counts');
        Route::get('lists', 'ListsController@index');
    });
});
