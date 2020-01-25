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

Route::post('/contact_form', 'IndexController@send_contact_message');

Route::resource('/products', 'ProductController');
Route::post('/products/update', 'ProductController@update');
Route::post('/products/restock', 'ProductController@restock');
Route::post('/products/upload_image', 'ProductController@upload_image');
Route::post('/products/remove_image', 'ProductController@remove_image');
Route::get('/product_search', 'ProductController@search');
Route::post("/products/quick_search", "ProductController@quick_search");
Route::get('/products/related_products/{id}', 'ProductController@related_products');

Route::resource('/brands', 'BrandController');
Route::post('/brands/update', 'BrandController@update');

Route::resource('/categories', 'CategoryController');
Route::post('/categories/update', 'CategoryController@update');

Route::resource('/order_request', 'OrderRequestController');
Route::post("/order_request/get_one/{id}", "OrderRequestController@get_one");
Route::post("/order_request/track_order", "OrderRequestController@track_order");
Route::post("/order_request/update", "OrderRequestController@update");
Route::post("/order_request/update_status", "OrderRequestController@update_status");

Route::resource("/order_status", "OrderStatusController");

Route::resource("/shopping_cart", "ShoppingCartController")->middleware('throttle:999999,1');

Route::post("/shopping_cart/update", "ShoppingCartController@update");
Route::post("/shopping_cart/empty_cart", "ShoppingCartController@empty_cart");
Route::post("/shopping_cart/item_exist", "ShoppingCartController@item_exist");

Route::resource("/payments", "PaymentController");
Route::get("/payments/get_or_payment/{order_request_id}", "PaymentController@get_or_payment");
Route::post("/payments/update", "PaymentController@update");

Route::resource("/notifications", "NotificationsController");
Route::post("/mark_unread_notifications", "NotificationsController@mark_all_read");


Route::resource("/user_settings","UserSettingController");
