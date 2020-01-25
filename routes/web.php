<?php

use App\OrderRequest;
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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', 'IndexController@index')->name('index');
Route::view('/admin-panel/{any?}','admin_panel' )->where('any','.*')->middleware('auth');

Route::resource("/products", "ProductController");
Route::get("/products", "ProductController@show_all")->name('products');
Route::get("/products/view/{id}-{slug?}", "ProductController@show");

Route::get("/products/brand/{id}-{slug?}", "BrandController@show");
Route::get("/products/category/{id}-{slug?}", "CategoryController@show");

Route::get("product_fetch_data", "ProductController@product_fetch_data");

Route::get("/shopping_cart", "ShoppingCartController@show");
Route::get("/shopping_cart/checkout/{session_id}", "ShoppingCartController@checkout");

Route::resource("/order_request", "OrderRequestController");
Route::get("/track_order", "OrderRequestController@show");
Route::get("/order_request/print/{tracking_no}", "OrderRequestController@print");

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/test', function() {
	return view('test');
});
