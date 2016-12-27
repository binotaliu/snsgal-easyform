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
    Route::get('/requests', 'RequestController@list');
    Route::get('/request/{token}/detail', 'RequestController@detail');
    Route::post('/request/{token}/export', 'RequestController@export');
    Route::get('/request/create', 'RequestController@createForm');
    Route::post('/request/create', 'RequestController@create');

    Route::get('/request/profile', 'RequestController@profile');
    Route::put('/request/profile', 'RequestController@profileUpdate');

    Route::put('/request/{token}', 'RequestController@update');
    Route::delete('/request/{token}', 'RequestController@remove');
});

Route::get('/request/{token}', 'RequestController@get');
Route::post('/request/{token}/address', 'RequestController@addAddress');

Route::post('/request/{token}/notify', 'RequestController@notify');

Route::get('/map/cvs', 'RequestController@cvsmap');
Route::post('/map/cvs/response', 'RequestController@cvsmapResponse');
