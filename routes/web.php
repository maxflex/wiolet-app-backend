<?php

// Route::namespace('Crm')->group(function() {
// Route::prefix('crm')->group(function() {
//     Route::get('initial-data', 'InitialDataController@index');
// });
// Route::get('/{any}', 'AppController@index')->where('any', '.*');
// });

Route::namespace('Api\crm')->prefix('api/crm')->group(function() {
    Route::get('initial-data', 'InitialDataController@index');
});


Route::get('/{any}', 'CrmController@index')->where('any', '.*');
