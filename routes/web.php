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

Route::group(['prefix' => 'user'], function () {
    Auth::routes(['register' => false]);
});

Route::get('/', function () {
    if (Auth::guest()) {
        return redirect('https://www.snsgal.com.tw/');
    } else {
        return redirect('/home');
    }
});

Route::get('/home', 'HomeController@index');

Route::group(['namespace' => 'Shipment'], function () {
    // /shipment/requests
    Route::group(['prefix' => 'shipment'], function () {
        Route::get('/requests/{token}', 'AddressTicketsController@get');
        Route::post('/requests/{token}/address', 'AddressTicketsController@addAddress');
        Route::post('/requests/{token}/notify', 'AddressTicketsController@notify');

        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::get('/requests', 'AddressTicketsController@view'); // vue handle
            Route::get('/requests/{token}/print', 'AddressTicketsController@print');
        });
    });

    // /api/shipment
    Route::group(['prefix' => 'api/shipment', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/requests/{token}/export', 'AddressTicketsController@export');
        Route::post('/requests/{token}/archive', 'AddressTicketsController@archive');
        Route::resource('/requests', 'AddressTicketsController', [
            'only' => ['index', 'store', 'update']
        ]);
        Route::post('/requests/batch', 'AddressTicketsController@batch');

        Route::resource('/sender_profile', 'SenderController', [
            'only' => ['index', 'store']
        ]);
    });

    Route::get('/map/cvs', 'AddressTicketsController@cvsmap');
    Route::post('/map/cvs/response', 'AddressTicketsController@cvsmapResponse');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('/api/configs', 'ConfigController', [
        'only' => ['index', 'store']
    ]);
});

