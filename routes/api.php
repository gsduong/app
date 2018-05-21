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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/{restaurant_slug}/reservations', ['uses' => 'Api\ReservationController@index']);
Route::post('/{restaurant_slug}/reservations/create', ['uses' => 'Api\ReservationController@create']);
// Route::get('/{restaurant_slug}/reservations/{id}', ['uses' => 'Api\ReservationController@show']);
Route::get('/{restaurant_slug}/address', ['uses' => 'Api\AddressController@index']);
