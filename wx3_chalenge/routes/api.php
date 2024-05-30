<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\BestSellerProductController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('stocks', StockController::class);
Route::resource('addresses', AddressController::class);
Route::resource('clients', ClientController::class);
Route::resource('payment-methods', PaymentMethodController::class);
Route::resource('sales', SaleController::class);
Route::resource('items', ItemController::class);
Route::resource('shippings', ShippingController::class);
Route::get('best-seller', [BestSellerProductController::class, 'bestSellerProduct']);