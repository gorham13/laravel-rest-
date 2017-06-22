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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/guzzle', 'PostsController@guzzleTesting');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::put('/update/{id}', 'UsersController@update');
    Route::get('/user/{id}', 'UsersController@get');
    Route::delete('/user/{id}', 'UsersController@delete');
    Route::get('/search', 'UsersController@search');
    Route::post('/post/create', 'PostsController@create');

    Route::group(['middleware' => 'CheckUser'], function () {
        Route::post('/post/{id}/setTags', 'PostsController@setTags');
        Route::put('/post/{id}', 'PostsController@update');
        Route::get('/post/{id}', 'PostsController@get');
        Route::delete('/post/{id}', 'PostsController@delete');
    });

    Route::get('/tags/autocomplete', 'PostsController@autocomplete');

    Route::get('/posts/search', 'PostsController@search');

    Route::post('/like/togle', 'LikesController@togle');

    Route::group(['middleware' => 'isAdmin'], function () {
        Route::get('/users', 'UsersController@getUsers');
    });
});

Route::post('/reg', 'UsersController@authenticate');
Route::post('/create', 'UsersController@create');
