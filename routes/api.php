<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [TestController::class, 'admin'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/kitchen', [TestController::class, 'kitchen'])->middleware(['auth:sanctum', 'role:kitchen_manager']);
Route::get('/cashier', [TestController::class, 'cashier'])->middleware(['auth:sanctum', 'role:cashier']);

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('payments', PaymentController::class);
});

Route::group(['prefix' => 'v1', 'middleware' => 'api.key'], function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API key is valid']);
    });

    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/test', function () {
            return response()->json(['message' => 'Admin access granted']);
        });
    });

    Route::middleware(['auth:sanctum', 'role:kitchen_manager'])->prefix('kitchen')->group(function () {
        Route::get('/test', function () {
            return response()->json(['message' => 'Kitchen manager access granted']);
        });
    });
});
