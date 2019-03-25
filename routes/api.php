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


Route::namespace('Api\v1')->prefix('v1')->group(function () {
    Route::apiResource('cities', 'CitiesController');

    Route::prefix('auth')->namespace('Auth')->group(function() {
        Route::post('refresh', 'LoginController@refresh');
        Route::post('register', 'LoginController@register');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout');
        Route::prefix('sms')->group(function() {
            Route::post('send-code', 'VerifyController@sendCode');
            Route::post('verify-code', 'VerifyController@verifyCode');
        });

        Route::prefix('password')->group(function() {
            Route::post('get-token', 'PasswordsController@getToken');
            Route::post('reset', 'PasswordsController@reset');
        });
    });

    Route::middleware('auth:api')->group(function() {
        Route::apiResources([
            'photos' => 'PhotosController',
        ]);

        Route::get('profile', 'ProfileController@show');
        Route::put('profile', 'ProfileController@update');
    });
});
