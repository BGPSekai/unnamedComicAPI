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



Route::get('/', function () {
	return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => 'cors'], function()
{
	Route::get('/', function () {
		return view('welcome');
	});
	
	Route::group(['prefix' => 'auth'], function()
	{
		Route::post('/', 'AuthController@auth');
		Route::post('register', 'AuthController@register');
		Route::post('reset', 'AuthController@reset');
	});

	Route::group(['middleware' => 'jwt.auth'], function()
	{
		Route::group(['prefix' => 'user'], function()
		{
			Route::get('/', 'UserController@index');
			Route::get('{id}', 'UserController@show');
		});

		Route::group(['prefix' => 'publish'], function()
		{
			Route::post('/', 'PublishController@index');
			Route::post('{comic_id}', 'PublishController@chapter');
			Route::post('chapter/{id}', 'PublishController@batch');
		});
	});

	Route::group(['prefix' => 'comic'], function()
	{
		Route::get('page', 'ComicController@count');
		Route::get('page/{page}', 'ComicController@index');
		Route::get('{id}', 'ComicController@show');
	});

	Route::group(['prefix' => 'search'], function()
	{
		Route::get('name/{name}/{page}', 'SearchController@name');
		Route::get('publisher/{user_id}/{page}', 'SearchController@publisher');
		Route::get('type/{id}/{page}', 'SearchController@type');
		Route::get('tag/{name}/{page}', 'SearchController@tag');
	});

	Route::group(['prefix' => 'type'], function()
	{
		Route::get('/', 'TypeController@index');
	});

	Route::group(['prefix' => 'tag'], function()
	{
		Route::get('{name}/comic/{comic_id}', 'TagController@store');
		Route::delete('{name}/comic/{comic_id}', 'TagController@destroy');
	});
});

Route::get('api/comic/{id}/cover', 'ComicController@showCover');
Route::get('api/comic/chapter/{page}', 'ComicController@showPage');
