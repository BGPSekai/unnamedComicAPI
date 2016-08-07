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



Route::group(['prefix' => 'api', 'middleware' => 'cors'], function()
{
	Route::get('/', function () {
	    return view('welcome');
	});
	
	Route::group(['prefix' => 'service'], function()
	{
		Route::post('register', 'ServiceController@register');
	});

	Route::group(['prefix' => 'auth'], function()
	{
		Route::post('/', 'AuthController@auth');
	});

	Route::group(['middleware' => 'jwt.auth'], function()
	{
		Route::group(['prefix' => 'publish'], function()
		{
			Route::post('/', 'PublishController@index');
			Route::post('{id}', 'PublishController@chapter');
		});
	});

	Route::group(['prefix' => 'comic'], function()
	{
		Route::get('{id}', 'ComicController@show');
		Route::get('page/{page}', 'ComicController@showAll');
	});
});

Route::get('api/comic/{id}/cover', 'ComicController@showCover');
