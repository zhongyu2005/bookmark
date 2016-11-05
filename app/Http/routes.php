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
    return redirect('login');
});




Route::any('login', 'LoginController@index');
Route::get('logout', 'LoginController@logout');
Route::any('active', 'LoginController@active');


Route::group(['middleware' => 'authBase'], function () {
    Route::any('userinfo', 'UserController@index');
    Route::post('recommend', 'UserController@recommend');
    
    Route::get('index', 'IndexController@index');
    Route::any('add', 'IndexController@add');
    Route::post('edit', 'IndexController@edit');
    Route::get('favorite', 'IndexController@favorite');
    Route::get('search', 'IndexController@search');
    Route::get('go', 'IndexController@redirect');
    
    //导出
    Route::get('export', 'ExportController@index');
    
});