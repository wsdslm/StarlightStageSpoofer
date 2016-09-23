<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
	return redirect('/app');
});

Route::get('/app/{path?}', 'AppController@index')->where('path', '(.*)');

include 'routes_api.php';

Route::group(['prefix' => '/auth', 'namespace' => 'Auth'], function() {
    Route::get('/login', 'AuthController@getLogin');
    Route::post('/login', 'AuthController@postLogin');
    Route::get('/logout', 'AuthController@getLogout');
    
    Route::get('/register', 'AuthController@getRegister');
    Route::post('/register', 'AuthController@postRegister');
});

Route::get('/{all?}', "SpooferController@forward")->where('all', '(.*)');
Route::post('/{all?}', "SpooferController@forward")->where('all', '(.*)');
