<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/', function () {
        return view('welcome');
    });



    Route::get('/auth', function(){
        return view('auth.login');
    });

    Route::post('/auth', 'Auth\LoginController@login');

    Route::get('/logout', 'Auth\LoginController@logout');

    //Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/facebook', 'FacebookController@test');

    Route::get('/fb-callback', 'FacebookController@fb_callback');

    Route::get('/fb_auth', 'FacebookController@fb_auth');

});
