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
			Route::patch('/', 'UserController@update');
		});

		Route::group(['prefix' => 'publish'], function()
		{
			Route::post('/', 'PublishController@comic');
			Route::post('{comic_id}', 'PublishController@chapter');
			Route::post('chapter/{chapter_id}', 'PublishController@batch');
		});

		Route::group(['prefix' => 'tag'], function()
		{
			Route::get('{name}', 'TagController@search');
			Route::post('{name}/{comic_id}', 'TagController@store');
			Route::delete('{name}/{comic_id}', 'TagController@destroy');
		});

		Route::group(['prefix' => 'favorite'], function()
		{
			Route::post('{comic_id}', 'FavoriteController@store');
			Route::delete('{comic_id}', 'FavoriteController@destroy');
		});

		Route::patch('comic/{id}', 'ComicController@update');
		Route::post('comment', 'CommentController@storeOrUpdate');
	});

	Route::group(['prefix' => 'comic'], function()
	{
		Route::get('page/{page}', 'ComicController@index');
		Route::get('{id}', 'ComicController@show');
	});

	Route::group(['prefix' => 'search'], function()
	{
		Route::get('name/{name}/{page}', 'SearchController@name');
		Route::get('publisher/{user_id}/{page}', 'SearchController@publisher');
		Route::get('type/{name}/{page}', 'SearchController@type');
		Route::get('tag/{name}/{page}', 'SearchController@tag');
		Route::get('author/{name}/{page}', 'SearchController@author');
	});

	Route::group(['prefix' => 'user'], function()
	{
		Route::get('{id}', 'UserController@show');
		Route::get('{id}/favorites', 'FavoriteController@show');
	});

	Route::group(['prefix' => 'comment'], function()
	{
		Route::get('comic/{id}/{page}', 'CommentController@comic');
		Route::get('chapter/{id}/{page}', 'CommentController@chapter');
	});

	Route::get('type', 'TypeController@index');
});

Route::get('comic/{id}/cover', 'ComicController@showCover');
Route::get('comic/chapter/{chapter_id}/{page}', 'ComicController@showPage');
