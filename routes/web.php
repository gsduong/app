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
/* Home Page */
Route::get('/',['uses'=>'HomeController@index'])->name('homepage');

/* Social Login */
Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('/social-auth/{provider}', 'Auth\SocialController@redirectToProvider')->name('provider');
Route::get('/social-auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

/* Logout */
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/* Auth */
Route::get('/typography',['as'=>'typography','uses'=>'TypographyController@index']);
Route::get('/helper',['as'=>'helper','uses'=>'HelperController@index']);
Route::get('/widget',['as'=>'widget','uses'=>'WidgetController@index']);
Route::get('/table',['as'=>'table','uses'=>'TableController@index']);
Route::get('/media',['as'=>'media','uses'=>'MediaController@index']);
Route::get('/chart',['as'=>'chart','uses'=>'ChartController@index']);
Route::get('/dashboard',['as'=>'dashboard','uses'=>'DashboardController@index']);
Route::get('/r', ['as' => 'restaurant.index', 'uses' => 'RestaurantController@index']);
Route::get('/r/create', ['as' => 'restaurant.add', 'uses' => 'RestaurantController@showCreateForm']);
Route::post('/r/add', ['as' => 'restaurant.create', 'uses' => 'RestaurantController@create']);