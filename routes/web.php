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

Route::post('/auth0/callback', '\Auth0\Login\Auth0Controller@callback');
Route::post('/user/logout', function () {
    Auth::logout();
    return redirect('/home');
});
Route::get('/user/login', function () {
    return view('user.auth.login');
});

Route::get('/home', 'HomeController@index');

Route::group(['namespace' => 'Shipment'], function () {
    // /shipment/requests
    Route::group(['prefix' => 'shipment'], function () {
        Route::get('/requests/{token}', 'Address\RequestController@get');
        Route::post('/requests/{token}/address', 'Address\RequestController@addAddress');
        Route::post('/requests/{token}/notify', 'Address\RequestController@notify');

        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::get('/requests', 'Address\RequestController@view'); // vue handle
            Route::get('/requests/{token}/print', 'Address\RequestController@print');
        });
    });

    // /api/shipment
    Route::group(['prefix' => 'api/shipment', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/requests/{token}/export', 'Address\RequestController@export');
        Route::post('/requests/{token}/archive', 'Address\RequestController@archive');
        Route::resource('/requests', 'Address\RequestController', [
            'only' => ['index', 'store', 'update']
        ]);

        Route::resource('/sender_profile', 'SenderController', [
            'only' => ['index', 'store']
        ]);
    });

    Route::get('/map/cvs', 'Address\RequestController@cvsmap');
    Route::post('/map/cvs/response', 'Address\RequestController@cvsmapResponse');
});


Route::group(['namespace' => 'Procurement'], function () {
    // /procurement/tickets
    Route::group(['prefix' => 'procurement'], function () {
        Route::get('/tickets/new', 'TicketController@new');
        Route::get('/tickets/{token}', 'TicketController@get');

        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::get('/tickets', 'TicketController@view'); // vue handle
        });
    });

    Route::group(['prefix' => 'api/procurement'], function () {
        Route::resource('/tickets', 'TicketController', [
            'only' => ['store']
        ]);
    });

    Route::group(['prefix' => 'api/procurement', 'middleware' => ['auth', 'admin']], function () {
        Route::post('/tickets/{token}/archive', 'TicketController@archive');
        Route::post('/tickets/{token}/status', 'TicketController@updateTicketStatus');
        Route::post('/ticket-items/{itemId}/status', 'TicketController@updateItemStatus');

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

