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

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');

/*Auth routes*/
Route::get('/login',                ['uses'=>'RegisterController@loginView',    'as'=>'loginView']);
Route::post('/login',               ['uses'=>'RegisterController@login',        'as'=>'login']);
Route::get('/register',             ['uses'=>'RegisterController@registerView', 'as'=>'registerView']);
Route::post('/register',            ['uses'=>'RegisterController@register',     'as'=>'register']);
Route::get('/logout',               ['uses'=>'RegisterController@logout',       'as'=>'logout']);
Route::get('/verify/{code}',        'VerifyController@verify');

Route::group(['middleware' => 'auth'], function (){
/*products*/
    Route::get('/all',                  ['uses'=>'ProductsController@index',        'as'=>'allProducts']);
    Route::get('/create',               ['uses'=>'ProductsController@create',       'as'=>'createView']);
    Route::post('/store',               ['uses'=>'ProductsController@store',        'as'=>'store']);
    Route::get('/show/{id}',            ['uses'=>'ProductsController@show',         'as'=>'showProduct']);
    Route::get('/edit/{id}',            ['uses'=>'ProductsController@edit',         'as'=>'editView']);
    Route::post('/update/{id}',         ['uses'=>'ProductsController@update',       'as'=>'edit']);
    Route::get('/destroy/{id}',         ['uses'=>'ProductsController@destroy',      'as'=>'deleteProduct']);
/*categories*/
    Route::get('/categories',           ['uses'=>'CategoryController@index',        'as'=>'allCat']);
    Route::get('/create_cat',           ['uses'=>'CategoryController@create',       'as'=>'createCat']);
    Route::post('/create_cat',          ['uses'=>'CategoryController@store',        'as'=>'storeCat']);
    Route::get('/show_cat/{id}',        ['uses'=>'CategoryController@show',         'as'=>'showCat']);
    Route::get('/edit_cat/{id}',        ['uses'=>'CategoryController@edit',         'as'=>'editCat']);
    Route::post('/edit_cat',            ['uses'=>'CategoryController@update',       'as'=>'updateCat']);
    Route::get('/delete_cat',           ['uses'=>'CategoryController@destroy',      'as'=>'deleteCat']);
/*clients*/
    Route::get('/clients',              ['uses'=>'ClientController@index',          'as'=>'allClients']);
    Route::get('/create_cl',            ['uses'=>'ClientController@create',         'as'=>'createCl']);
    Route::post('/create_cl',           ['uses'=>'ClientController@store',          'as'=>'storeCl']);
    Route::get('/show_cl/{id}',         ['uses'=>'ClientController@show',           'as'=>'showCl']);
    Route::get('/edit_cl/{id}',         ['uses'=>'ClientController@edit',           'as'=>'editCl']);
    Route::post('/edit_cl',             ['uses'=>'ClientController@update',         'as'=>'updateCl']);
    Route::get('/delete_cl',            ['uses'=>'ClientController@destroy',        'as'=>'deleteCl']);
});

/* API */
Route::get('/test_geocoder/{id}', 'GoogleGeocoderController@setGeocode');

Route::group(['prefix' => 'api','namespace'=>'Api'], function (){
    /*Auth routes*/
    Route::post('/login',       'AuthController@login');
    Route::post('/register',    'AuthController@register');

    Route::group(['middleware' => 'verify_token'], function (){
        /*products*/
        Route::get('/products/{take?}/{skip?}',     'ProductsController@index');
        Route::post('/product',                     'ProductsController@store');
        Route::put('/product/{id}',                 'ProductsController@update');
        Route::get('/product/{id}',                 'ProductsController@show');
        Route::delete('/product/{id}',              'ProductsController@destroy');
        /*categories*/
        Route::get('/categories/{take?}/{skip?}',   'CategoryController@index');
        Route::post('/category',                    'CategoryController@store');
        Route::get('/category/{id}',                'CategoryController@show');
        Route::put('/category/{id}',                'CategoryController@update');
        Route::delete('/category/{id}',             'CategoryController@destroy');
        Route::get('/category_products/{id}',       'CategoryController@category_products');
        /*clients*/
        Route::get('/clients/{take?}/{skip?}',      'ClientController@index');
        Route::post('/client',                      'ClientController@store');
        Route::get('/client/{id}',                  'ClientController@show');
        Route::put('/client/{id}',                  'ClientController@update');
        Route::delete('/client/{id}',               'ClientController@destroy');
        /*invoices*/
        Route::get('/invoices/{take?}/{skip?}',     'InvoiceController@index');
        Route::post('/invoice',                     'InvoiceController@store');
        /*cities*/
        Route::get('/cities',                       'CityController@index');

        Route::any('{any?}',                        'AuthController@notFound');
    });
});
