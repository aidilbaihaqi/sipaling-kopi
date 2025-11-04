<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/admin', function () {
    return response()->json(['message' => 'Welcome, Admin!']);
})->middleware(['auth:sanctum', 'role:admin']);

Route::get('/kitchen', function () {
    return response()->json(['message' => 'Welcome, Kitchen Manager!']);
})->middleware(['auth:sanctum', 'role:kitchen_manager']);

Route::get('/cashier', function () {
    return response()->json(['message' => 'Welcome, Cashier!']);
})->middleware(['auth:sanctum', 'role:cashier']);

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('payments', PaymentController::class);
});
