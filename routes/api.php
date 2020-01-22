<?php

use Illuminate\Http\Request;

Route::group([
    'middleware' => [
        'api.auth'
    ],
    'namespace' => 'Api',
], function () {
    Route::get('/order/{id}', 'OrderController@get');
});
