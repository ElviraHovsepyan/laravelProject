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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',['uses'=>'RegisterController@loginView','as'=>'loginView']);
Route::post('/login',['uses'=>'RegisterController@login','as'=>'login']);
Route::get('/register',['uses'=>'RegisterController@registerView','as'=>'registerView']);
Route::post('/register',['uses'=>'RegisterController@register','as'=>'register']);


Route::get('/verify/{code}','VerifyController@verify');


Route::group(['prefix' => 'api','namespace'=>'Api'], function (){
    /*Auth routes*/
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register');

    /*products*/
});
