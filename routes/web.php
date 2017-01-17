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
            Route::get('/requests', 'RequestController@view'); // vue handle
        });
    });

    // /api/shipment
    Route::group(['prefix' => 'api/shipment', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/requests/{token}/export', 'RequestController@export');
        Route::post('/requests/{token}/archive', 'RequestController@archive');
        Route::resource('/requests', 'RequestController', [
            'only' => ['index', 'store', 'update']
        ]);

        Route::resource('/sender_profile', 'SenderController', [
            'only' => ['index', 'store']
        ]);
    });

    Route::get('/map/cvs', 'RequestController@cvsmap');
    Route::post('/map/cvs/response', 'RequestController@cvsmapResponse');
});


Route::group(['namespace' => 'Procurement'], function () {
    // /procurement/tickets
    Route::group(['prefix' => 'procurement'], function () {
        Route::get('/tickets', 'TicketController@view');
        Route::get('/tickets/new', 'TicketController@new');
        Route::get('/tickets/{token}', 'TicketController@get');
    });

    Route::group(['prefix' => 'api/procurement', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/tickets/{token}/archive', 'TicketController@archive');

        Route::resource('/tickets', 'TicketController', [
            'only' => ['index', 'store', 'update']
        ]);

        Route::resource('/shipment_methods/japan', 'Ticket\ShipmentMethod\JapanController', [
            'only' => ['index', 'store']
        ]);

        Route::resource('/shipment_methods/local', 'Ticket\ShipmentMethod\LocalController', [
            'only' => ['index', 'store']
        ]);

        Route::resource('/item_categories', 'Item\CategoryController', [
            'only' => ['index', 'store']
        ]);

        Route::resource('/item_extra_services', 'Item\ExtraServiceController', [
            'only' => ['index', 'store']
        ]);
    });
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('/api/configs', 'ConfigController', [
        'only' => ['index', 'store']
    ]);
});

