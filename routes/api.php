<?php

/**
 * ============================================
 * REST API ROUTES - SIPALINGKOPI CAFE MANAGEMENT
 * ============================================
 * 
 * Base URL: /api
 * 
 * Authentication: Laravel Sanctum
 * - Public routes: Login
 * - Protected routes: Semua CRUD operations
 * 
 * Struktur API:
 * 1. Authentication API
 * 2. Dashboard API (Admin)
 * 3. Menu API (CRUD)
 * 4. Category API (CRUD)
 * 5. User API (CRUD)
 * 6. Order API (CRUD + Kitchen + Cashier)
 * 7. Stock API (Kitchen)
 * 8. Report API (Admin)
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\ReportController;

// ============================================
// PUBLIC ROUTES (Tanpa Authentication)
// ============================================

/**
 * API: Test Connection
 * Method: GET
 * URL: /api/test
 */
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API berhasil diakses!',
        'timestamp' => now()
    ]);
});

/**
 * API: Login
 * Method: POST
 * URL: /api/auth/login
 * Body: { email, password }
 */
Route::post('/auth/login', [AuthController::class, 'login']);

// ============================================
// PROTECTED ROUTES (Perlu Authentication)
// ============================================
Route::middleware('auth:sanctum')->group(function () {

    // ----------------------------------------
    // AUTHENTICATION
    // ----------------------------------------
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // ----------------------------------------
    // DASHBOARD (Admin)
    // ----------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ----------------------------------------
    // MENUS (CRUD)
    // ----------------------------------------
    Route::prefix('menus')->group(function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::get('/available', [MenuController::class, 'available']);
        Route::get('/{menu}', [MenuController::class, 'show']);
        Route::post('/', [MenuController::class, 'store']);
        Route::put('/{menu}', [MenuController::class, 'update']);
        Route::delete('/{menu}', [MenuController::class, 'destroy']);
    });

    // ----------------------------------------
    // CATEGORIES (CRUD)
    // ----------------------------------------
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    // ----------------------------------------
    // USERS (CRUD)
    // ----------------------------------------
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    // ----------------------------------------
    // ORDERS (CRUD + Kitchen + Cashier)
    // ----------------------------------------
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/kitchen', [OrderController::class, 'kitchen']);
        Route::get('/history', [OrderController::class, 'history']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
    });

    // ----------------------------------------
    // STOCK (Kitchen)
    // ----------------------------------------
    Route::prefix('stock')->group(function () {
        Route::get('/', [StockController::class, 'index']);
        Route::post('/{menu}/toggle', [StockController::class, 'toggleAvailability']);
        Route::post('/{menu}/update', [StockController::class, 'updateStock']);
    });

    // ----------------------------------------
    // REPORTS (Admin)
    // ----------------------------------------
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/export', [ReportController::class, 'export']);
    });
});
