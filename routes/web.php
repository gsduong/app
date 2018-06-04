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
Route::get('/r', ['as' => 'restaurant.index', 'uses' => 'RestaurantController@index']);
Route::get('/r/select-page', ['as' => 'restaurant.select-page', 'uses' => 'RestaurantController@selectPage']);
Route::get('/r/create', ['as' => 'restaurant.show-form-create', 'uses' => 'RestaurantController@showFormCreate']);
Route::get('/r/edit/{restaurant_id}.html', ['as' => 'restaurant.show-form-edit', 'uses' => 'RestaurantController@showFormEdit']);
Route::post('/r/create', ['as' => 'restaurant.show-form-create-with-id', 'uses' => 'RestaurantController@showFormCreateWithId']);
Route::post('/r/update/{restaurant_id}', ['as' => 'restaurant.update', 'uses' => 'RestaurantController@update']);
Route::post('/r/add', ['as' => 'restaurant.create', 'uses' => 'RestaurantController@create']);
Route::get('/r/delete/{restaurant_id}', ['as' => 'restaurant.delete', 'uses' => 'RestaurantController@delete']);
Route::get('r/{slug}.html', ['as' => 'restaurant.show', 'uses' => 'RestaurantController@show']);
Route::get('r/{slug}/members.html', ['as' => 'staff.index', 'uses' => 'RestaurantController@staff_index']);
// Route::get('r/{slug}/delete/{id}', ['as' => 'staff.delete', 'uses' => 'RestaurantController@staff_delete'])->middleware('admin.delete');
Route::get('r/{slug}/contact.html', ['as' => 'contact.index', 'uses' => 'ContactInfoController@index']);
Route::post('r/{slug}/contact/update', ['as' => 'contact.update', 'uses' => 'ContactInfoController@update']);
Route::post('r/{slug}/contact/create', ['as' => 'contact.create', 'uses' => 'ContactInfoController@create']);
Route::get('r/{slug}/contact/delete/{contact_id}', ['as' => 'contact.delete', 'uses' => 'ContactInfoController@delete']);
Route::get('r/{restaurant_slug}/menu.html', ['as' => 'category.index', 'uses' => 'CategoryController@index']);
Route::get('r/{restaurant_slug}/menu-list.html', ['as' => 'category.list', 'uses' => 'CategoryController@list']);
Route::post('r/{restaurant_slug}/menu/update', ['as' => 'category.update', 'uses' => 'CategoryController@update']);
Route::post('r/{restaurant_slug}/menu/create', ['as' => 'category.create', 'uses' => 'CategoryController@create']);
Route::get('r/{restaurant_slug}/menu/delete/{category_id}', ['as' => 'category.delete', 'uses' => 'CategoryController@delete']);
Route::get('r/{restaurant_slug}/menu/{category_slug}.html', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
Route::get('r/{restaurant_slug}/menu/{category_slug}/item/create.html', ['as' => 'item.show-form-create', 'uses' => 'ItemController@showFormCreate']);
Route::post('r/{restaurant_slug}/menu/{category_slug}/item/create', ['as' => 'item.create', 'uses' => 'ItemController@create']);
Route::get('r/{restaurant_slug}/menu/{category_slug}/item/delete/{item_id}', ['as' => 'item.delete', 'uses' => 'ItemController@delete']);
Route::get('r/{restaurant_slug}/menu/{category_slug}/item/delete-image/{item_id}', ['as' => 'item.delete-image', 'uses' => 'ItemController@deleteImage']);
Route::get('r/{restaurant_slug}/menu/{category_slug}/item/edit/{item_id}.html', ['as' => 'item.show-form-edit', 'uses' => 'ItemController@showFormEdit']);
Route::post('r/{restaurant_slug}/menu/{category_slug}/item/update', ['as' => 'item.update', 'uses' => 'ItemController@update']);

// Booking
Route::get('r/{restaurant_slug}/reservations.html', ['as' => 'reservation.index', 'uses' => 'ReservationController@index']);
Route::get('r/{restaurant_slug}/reservations/create.html', ['as' => 'reservation.show-form-create', 'uses' => 'ReservationController@showFormCreate']);
Route::get('r/{restaurant_slug}/reservations/edit-{reservation_id}.html', ['as' => 'reservation.show-form-edit', 'uses' => 'ReservationController@showFormEdit']);
Route::post('r/{restaurant_slug}/reservations/create', ['as' => 'reservation.create', 'uses' => 'ReservationController@create']);
Route::post('r/{restaurant_slug}/reservations/update', ['as' => 'reservation.update', 'uses' => 'ReservationController@update']);
Route::get('r/{restaurant_slug}/reservations/delete/{reservation_id}', ['as' => 'reservation.delete', 'uses' => 'ReservationController@delete']);

// Discount
Route::get('r/{restaurant_slug}/discounts.html', ['as' => 'discount.index', 'uses' => 'DiscountController@index']);
Route::get('r/{restaurant_slug}/discounts/create.html', ['as' => 'discount.show-form-create', 'uses' => 'DiscountController@showFormCreate']);
Route::post('r/{restaurant_slug}/discounts/create', ['as' => 'discount.create', 'uses' => 'DiscountController@create']);
Route::get('r/{restaurant_slug}/discounts/delete/{discount_id}', ['as' => 'discount.delete', 'uses' => 'DiscountController@delete']);
Route::post('r/{restaurant_slug}/discounts/update/{discount_id}', ['as' => 'discount.update', 'uses' => 'DiscountController@update']);
Route::get('r/{restaurant_slug}/discounts/edit{discount_id}.html', ['as' => 'discount.show-form-edit', 'uses' => 'DiscountController@showFormEdit']);
// order
Route::get('r/{restaurant_slug}/orders.html', ['as' => 'order.index', 'uses' => 'OrderController@index']);
Route::get('r/{restaurant_slug}/orders/create.html', ['as' => 'order.show-form-create', 'uses' => 'OrderController@showFormCreate']);
Route::post('r/{restaurant_slug}/orders/create', ['as' => 'order.create', 'uses' => 'ReservationController@create']);
// Bot
Route::get('r/{restaurant_slug}/bot/index.html', ['as' => 'bot.index', 'uses' => 'BotController@index']);
Route::get('r/{restaurant_slug}/bot/create', ['as' => 'bot.create', 'uses' => 'BotController@create']);
Route::get('r/{restaurant_slug}/bot/delete', ['as' => 'bot.delete', 'uses' => 'BotController@delete']);
Route::get('r/{restaurant_slug}/bot/test', ['as' => 'bot.test', 'uses' => 'BotController@test']);

Route::get("/webhook", "WebhookController@verify");
Route::post("/webhook", "WebhookController@receive")->name('webhook');

Route::get('customer/{restaurant_slug}/create-order.html', ['as' => 'customer.show-form-create-order', 'uses' => 'CustomerController@showFormCreateOrder']);
Route::get('customer/{restaurant_slug}/reservation-{psid}.html', ['as' => 'customer.reservation', 'uses' => 'CustomerController@showFormCreateReservation']);