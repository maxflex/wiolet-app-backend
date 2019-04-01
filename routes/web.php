<?php

Route::get('/{any}', function() {
    return view('crm');
})->where('any', '.*');
