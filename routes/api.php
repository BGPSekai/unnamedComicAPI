<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'cors'], function()
{
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
			Route::post('avatar', 'UserController@avatar');
		});

		Route::group(['prefix' => 'publish'], function()
		{
			Route::post('/', 'PublishController@index');
			Route::post('{comic_id}', 'PublishController@chapter');
			Route::post('chapter/{chapter_id}', 'PublishController@batch');
		});

		Route::group(['prefix' => 'tag'], function()
		{
			Route::post('{name}/{comic_id}', 'TagController@store');
			Route::delete('{name}/{comic_id}', 'TagController@destroy');
		});

		Route::group(['prefix' => 'favorite'], function()
		{
			Route::post('{comic_id}', 'FavoriteController@store');
			Route::delete('{comic_id}', 'FavoriteController@destroy');
		});
	});

	Route::group(['prefix' => 'comic'], function()
	{
		Route::get('page/{page}', 'ComicController@index');
		Route::get('{id}', 'ComicController@show');
		Route::post('info', 'ComicController@info');
	});

	Route::group(['prefix' => 'search'], function()
	{
		Route::get('name/{name}/{page}', 'SearchController@name');
		Route::get('publisher/{user_id}/{page}', 'SearchController@publisher');
		Route::get('type/{id}/{page}', 'SearchController@type');
		Route::get('tag/{name}/{page}', 'SearchController@tag');
		Route::get('author/{name}/{page}', 'SearchController@author');
	});

	Route::group(['prefix' => 'user'], function() {
		Route::get('{id}', 'UserController@show');
		Route::get('{id}/favorites', 'FavoriteController@showComics');
	});

	Route::get('type', 'TypeController@index');
});

Route::get('comic/{id}/cover', 'ComicController@showCover');
Route::get('comic/chapter/{page}', 'ComicController@showPage');
