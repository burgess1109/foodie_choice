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
Route::get('foodie/menu', 'FoodieController@menu');
Route::resource('foodie', 'FoodieController');
Route::get('restaurant/menu', 'RestaurantController@menu');
Route::resource('restaurant', 'RestaurantController');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/facade', function () {
    return view('index', ['path' => 'restaurant']);
});

Route::get('/', function () {
    return view('index', ['path' => 'foodie']);
});
