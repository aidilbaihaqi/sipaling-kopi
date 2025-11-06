<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/v1/login', [AuthController::class, 'login']);

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'api.key'], function () {
    Route::get('/test-api-key', function () {
        return response()->json(['message' => 'API key is valid']);
    });
    Route::get('/admin/test', [TestController::class, 'admin'])->middleware(['auth:sanctum', 'role:admin']);
    Route::get('/kitchen/test', [TestController::class, 'kitchen'])->middleware(['auth:sanctum', 'role:kitchen_manager']);
    Route::get('/cashier/test', [TestController::class, 'cashier'])->middleware(['auth:sanctum', 'role:cashier']);
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

    // Menus
    Route::get('/menus', [MenuController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/menus', [MenuController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
    Route::patch('/menus/{menu}', [MenuController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

    // Other resources
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('payments', PaymentController::class);
});


