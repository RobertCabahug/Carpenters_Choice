<?php

use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthTokenFails;
use App\Http\Middleware\IsUserCustomer;
use App\Http\Middleware\IsUserSeller;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


Route::middleware([AuthTokenFails::class])->group(function(){
    Route::delete('/logout', [UserController::class, 'logout']);

    Route::get('/me', [UserController::class,'me']);
    Route::get('/me/conversations', [UserController::class,'me']);

    Route::middleware([IsUserCustomer::class])->group(function(){
        Route::patch('/me/start-selling', [UserController::class,'startSelling']);

        Route::get('/me/cart', [UserController::class, 'cart']);
        Route::post('/me/cart', [UserController::class, 'updateCart']); // add or update cart
        Route::delete('/me/cart', [UserController::class, 'clearCart']);

        Route::post('/order', [OrderController::class, 'add']);

        Route::get('/me/favorites', [UserController::class, 'favorites']);
        Route::post('/me/favorites', [UserController::class, 'addToFavorites']); // add or update cart
        Route::delete('/me/favorites/{id}', [UserController::class, 'unfavorite']);

        Route::post('/conversation', [ConversationsController::class, 'create']);
    });

    Route::middleware([IsUserSeller::class])->group(function(){
        Route::patch('/me/quit-selling', [UserController::class,'quitSelling']);

        Route::get('/me/products', [UserController::class, 'products']);
        Route::get('/me/orders', [UserController::class, 'orders']);

        Route::post('/product', [ProductController::class, 'add']);
        Route::patch('/product/{id}', [ProductController::class, 'edit']);
        Route::delete('/product/{id}', [ProductController::class, 'delete']);
        
    });

    Route::get('/product', [ProductController::class, 'all']);
    Route::get('/product/{id}', [ProductController::class, 'get']);
    
    Route::get('/order/{id}', [OrderController::class, 'orderData']);
    Route::post('/order/{id}', [OrderController::class, 'addLog']);

    Route::get('/conversation/{id}', [ConversationsController::class, 'messages']);
    Route::post('/conversation/{id}', [ConversationsController::class, 'message']);
    Route::patch('/conversation/{id}/read', [ConversationsController::class, 'readMessage']);

});