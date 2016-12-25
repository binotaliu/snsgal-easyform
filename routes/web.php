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
    Route::get('/requests/{token}/detail', 'RequestController@detail');
    Route::post('/request', 'RequestController@create');

    Route::put('/request/{token}', 'RequestController@create');
    Route::delete('/request/{token}', 'RequestController@remove');
});

Route::get('/request/{token}', 'RequestController@get');
Route::post('/request/{token}/address', 'RequestController@respond');
