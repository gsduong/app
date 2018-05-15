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
// Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('/social-auth/{provider}', 'Auth\SocialController@redirectToProvider')->name('provider');
Route::get('/social-auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

/* Facebook Login with FacebookSDK*/
Route::get('/login', 'Auth\FacebookController@showFormLogin')->name('login');
Route::get('/facebook/callback', 'Auth\FacebookController@handleFacebookCallback');
Route::get('/test', 'Auth\FacebookController@test');
Route::get('/logout', 'Auth\FacebookController@logout')->name('logout');
/* Logout */
// Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/* Auth */
Route::get('/typography',['as'=>'typography','uses'=>'TypographyController@index']);
Route::get('/helper',['as'=>'helper','uses'=>'HelperController@index']);
Route::get('/widget',['as'=>'widget','uses'=>'WidgetController@index']);
Route::get('/table',['as'=>'table','uses'=>'TableController@index']);
Route::get('/media',['as'=>'media','uses'=>'MediaController@index']);
Route::get('/chart',['as'=>'chart','uses'=>'ChartController@index']);
Route::get('/dashboard',['as'=>'dashboard','uses'=>'DashboardController@index']);
Route::get('/r', ['as' => 'restaurant.index', 'uses' => 'RestaurantController@index']);
Route::get('/r/select-page', ['as' => 'restaurant.select-page', 'uses' => 'RestaurantController@selectPage']);
Route::get('/r/create', ['as' => 'restaurant.show-form-create', 'uses' => 'RestaurantController@showFormCreate']);
Route::post('/r/create', ['as' => 'restaurant.show-form-create-with-id', 'uses' => 'RestaurantController@showFormCreateWithId']);
Route::post('/r/add', ['as' => 'restaurant.create', 'uses' => 'RestaurantController@create']);
Route::get('/r/delete/{restaurant_id}', ['as' => 'restaurant.delete', 'uses' => 'RestaurantController@delete']);