<?php

/**
 * ============================================
 * WEB ROUTES - SIPALINGKOPI CAFE MANAGEMENT
 * ============================================
 * 
 * File ini berisi route untuk halaman web (views)
 * Semua CRUD operations dilakukan via REST API
 * 
 * @package  SipalingKopi
 * @version  2.0.0 (REST API Version)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ============================================
// PROTECTED ROUTES (Perlu Login)
// ============================================
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ----------------------------------------
    // KITCHEN ROUTES
    // ----------------------------------------
    Route::get('/kitchen', function () {
        return view('kitchen.index');
    })->name('kitchen.index');

    Route::get('/kitchen/stock', function () {
        return view('kitchen.stock');
    })->name('kitchen.stock');

    // ----------------------------------------
    // CASHIER ROUTES
    // ----------------------------------------
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', function () {
            return view('cashier.index');
        })->name('index');

        Route::get('/history', function () {
            return view('cashier.history');
        })->name('history');

        Route::get('/print/{id}', [\App\Http\Controllers\Cashier\CashierController::class, 'print'])->name('print');
    });

    // ----------------------------------------
    // ADMIN ROUTES
    // ----------------------------------------
    Route::prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Menu Management (View only, CRUD via API)
        Route::get('/menus', function () {
            return view('admin.menus.index');
        })->name('menus.index');

        // Category Management
        Route::get('/categories', function () {
            return view('admin.categories.index');
        })->name('categories.index');

        // User Management
        Route::get('/users', function () {
            return view('admin.users.index');
        })->name('users.index');

        // Reports
        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('admin.reports');

        // Export Excel (still uses controller for file download)
        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
            ->name('admin.reports.export');
    });
});
