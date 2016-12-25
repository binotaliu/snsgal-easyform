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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/requests', 'RequestController@list');
    Route::get('/request/{token}/detail', 'RequestController@detail');
    Route::post('/request/{token}/export', 'RequestController@export');
    Route::get('/request/create', 'RequestController@createForm');
    Route::post('/request/create', 'RequestController@create');

    Route::put('/request/{token}', 'RequestController@create');
    Route::delete('/request/{token}', 'RequestController@remove');
});

Route::get('/request/{token}', 'RequestController@get');
Route::post('/request/{token}/address', 'RequestController@addAddress');

Route::post('/request/{token}/notify', 'RequestController@notify');

Route::get('/map/cvs', 'RequestController@cvsmap');
Route::post('/map/cvs/response', 'RequestController@cvsmapResponse');
