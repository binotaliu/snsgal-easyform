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

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shipment\AddressTicketsController;
use App\Http\Controllers\Shipment\SenderController;

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

Route::get('/home', [HomeController::class, 'index']);

// /shipment/requests
Route::group(['prefix' => 'shipment'], function () {
    Route::get('/requests/{token}', [AddressTicketsController::class, 'get']);
    Route::post('/requests/{token}/address', [AddressTicketsController::class, 'addAddress']);
    Route::post('/requests/{token}/notify', [AddressTicketsController::class, 'notify']);

    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::get('/requests', [AddressTicketsController::class, 'view']); // vue handle
        Route::get('/requests/{token}/print', [AddressTicketsController::class, 'print']);
    });
});

// /api/shipment
Route::group(['prefix' => 'api/shipment', 'middleware' => ['auth', 'admin']], function () {
    Route::post('/requests/{token}/export', [AddressTicketsController::class, 'export']);
    Route::post('/requests/{token}/archive', [AddressTicketsController::class, 'archive']);
    Route::resource('/requests', AddressTicketsController::class, [
        'only' => ['index', 'store', 'update']
    ]);
    Route::post('/requests/batch', [AddressTicketsController::class, 'batch']);

    Route::resource('/sender_profile', SenderController::class, [
        'only' => ['index', 'store']
    ]);
});

    Route::get('/map/cvs', [AddressTicketsController::class, 'cvsmap']);
    Route::post('/map/cvs/response', [AddressTicketsController::class, 'cvsmapResponse']);

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('/api/configs', ConfigController::class, [
        'only' => ['index', 'store']
    ]);
});

