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

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/shipment/requests', 'Shipment\RequestController@view');

    Route::post('/api/shipment/request/{token}/export', 'Shipment\RequestController@export');
    Route::resource('/api/shipment/requests', 'Shipment\RequestController', [
        'only' => [
            'index', 'store', 'update', 'destroy'
        ]
    ]);

    Route::resource('/api/shipment/sender_profile', 'Shipment\SenderController', [
        'only' => [
            'index', 'store'
        ]
    ]);
});

Route::get('/shipment/request/{token}', 'Shipment\RequestController@get');
Route::post('/request/{token}/address', 'Shipment\RequestController@addAddress');

Route::post('/request/{token}/notify', 'Shipment\RequestController@notify');

Route::get('/map/cvs', 'Shipment\RequestController@cvsmap');
Route::post('/map/cvs/response', 'Shipment\RequestController@cvsmapResponse');
