<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    if (Auth::guest()) {
        return redirect('https://www.snsgal.com/');
    } else {
        return redirect('/home');
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['namespace' => 'Shipment'], function () {
    // /shipment/requests
    Route::group(['prefix' => 'shipment'], function () {
        Route::get('/requests/{token}', 'RequestController@get');
        Route::post('/requests/{token}/address', 'RequestController@addAddress');
        Route::post('/requests/{token}/notify', 'RequestController@notify');

        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::get('/requests', 'RequestController@view');
        });
    });

    // /api/shipment
    Route::group(['prefix' => 'api/shipment', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/requests/{token}/export', 'RequestController@export');
        Route::post('/requests/{token}/archive', 'RequestController@archive');
        Route::resource('/requests', 'RequestController', [
            'only' => ['index', 'store', 'update', 'destroy']
        ]);

        Route::resource('/sender_profile', 'SenderController', [
            'only' => ['index', 'store']
        ]);
    });

    Route::get('/map/cvs', 'RequestController@cvsmap');
    Route::post('/map/cvs/response', 'RequestController@cvsmapResponse');
});

