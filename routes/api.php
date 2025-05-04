<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BuyItemController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;

Route::get('/', function () {
    return response()->json(['message' => "Hello from Carpenter's Choice, Made with Laravel, MongoDB and Herd!"]);
});

//Auth Route
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

//Carts Route
Route::get('/carts', [CartController::class, 'index']);
Route::get('/carts/{id}', [CartController::class, 'show']);
Route::post('/carts', [CartController::class, 'store']);
Route::put('/carts/{id}', [CartController::class, 'update']);
Route::delete('/carts/{id}', [CartController::class, 'destroy']);

//Transaction Route
Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

//Buy Item Route
Route::get('/buy-items', [BuyItemController::class, 'index']);
Route::get('/buy-items/{id}', [BuyItemController::class, 'show']);
Route::post('/buy-items', [BuyItemController::class, 'store']);

//Logistic Route
Route::get('/logistics', [LogisticController::class, 'index']);
Route::get('/logistics/{id}', [LogisticController::class, 'show']);
Route::post('/logistics', [LogisticController::class, 'store']);
Route::put('/logistics/{id}', [LogisticController::class, 'update']);

//Product Route
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

//Seller Route
Route::get('/sellers', [SellerController::class, 'index']);
Route::get('/sellers/{id}', [SellerController::class, 'show']);
Route::post('/sellers', [SellerController::class, 'store']);
Route::put('/sellers/{id}', [SellerController::class, 'update']);
Route::delete('/sellers/{id}', [SellerController::class, 'destroy']);