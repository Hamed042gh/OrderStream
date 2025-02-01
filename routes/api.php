<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderitemController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Command Routes

    // Create a new order
    Route::post('/orders', [OrderController::class, 'store']);
    // Update an existing order
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    // Delete an order
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

    // Create a new order item
    Route::post('/orderitems', [OrderitemController::class, 'store']);
    // Update an existing order item
    Route::put('/orderitems/{orderitem}', [OrderitemController::class, 'update']);
    // Delete an order item
    Route::delete('/orderitems/{orderitem}', [OrderitemController::class, 'destroy']);

    // Create a new product
    Route::post('/products', [ProductController::class, 'store']);
    // Update an existing product
    Route::put('/products/{product}', [ProductController::class, 'update']);
    // Delete a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

// Query Routes
Route::prefix('v1')->group(function () {
    // Get a list of all orders
    Route::get('/orders', [OrderController::class, 'index']);
    // Get details of a specific order
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Get a list of all order items
    Route::get('/orderitems', [OrderitemController::class, 'index']);
    // Get details of a specific order item
    Route::get('/orderitems/{orderitem}', [OrderitemController::class, 'show']);

    // Get a list of all products
    Route::get('/products', [ProductController::class, 'index']);
    // Get details of a specific product
    Route::get('/products/{product}', [ProductController::class, 'show']);
});
