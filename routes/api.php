<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('registerProfile', 'AuthController@registerProfile');
    Route::post('logout', 'AuthController@logout');
    Route::post('login', 'AuthController@login');
});

Route::group(['prefix' => 'seller'], function () {
    Route::post('pup/create', 'PupController@create');
    Route::post('pup/read', 'PupController@seller_pups');
    Route::get('pup/details/{id}', 'PupController@details');
    Route::delete('pup/delete/{id}', 'PupController@delete');
    Route::put('pup/update/{id}', 'PupController@update');
    Route::post('shipment/read', 'ShipmentController@seller_shipment');
    Route::post('review/read', 'ReviewController@seller_review');
    Route::get('review/details/{id}', 'ReviewController@details');    
});

Route::group(['prefix' => 'pup'], function () {
  
    Route::post('read', 'PupController@index');
   
});

Route::group(['prefix' => 'buyer'], function () {
  
    Route::post('create', 'BuyerController@create');
    Route::get('details/{id}', 'BuyerController@details');
    Route::delete('delete/{id}', 'BuyerController@delete');
    Route::post('create_shipment', 'ShipmentController@create');
    Route::post('create_review', 'ReviewController@create');
    Route::post('review', 'ReviewController@index');
});

Route::group(['prefix' => 'admin'], function () {
  
    Route::delete('delete_buyer/{id}', 'BuyerController@delete');
    Route::post('all_buyer', 'BuyerController@show');

    Route::post('breed/create', 'BreedController@create');
    Route::post('breed/read', 'BreedController@index');
    Route::get('breed/details/{id}', 'BreedController@details');
    Route::delete('breed/delete/{id}', 'BreedController@delete');
    Route::put('breed/update/{id}', 'BreedController@update');
});