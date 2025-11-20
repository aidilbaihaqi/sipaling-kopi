<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// Namespace Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
// Namespace Kitchen
use App\Http\Controllers\Kitchen\KitchenController;
// Namespace Cashier
use App\Http\Controllers\Cashier\CashierController;

// --- 1. PUBLIC ROUTES ---
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// --- 2. PROTECTED ROUTES (Memerlukan Login) ---
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ======================================================
    // 🔹 A. ROUTE DAPUR (Kitchen Manager)
    // ======================================================

    Route::get('/kitchen', [KitchenController::class, 'index'])
        ->name('kitchen.index');

    Route::post('/kitchen/order/{order}/update-status', [KitchenController::class, 'updateStatus'])
        ->name('kitchen.updateStatus');

    Route::get('/kitchen/stock', [KitchenController::class, 'stock'])
        ->name('kitchen.stock');

    Route::post('/kitchen/stock/{menu}/toggle', [KitchenController::class, 'toggleMenuAvailability'])
        ->name('kitchen.toggleMenu');

    Route::post('/kitchen/stock/{menu}/update-stock', [KitchenController::class, 'updateStock'])
        ->name('kitchen.updateStock');

    // ======================================================
    // 🔹 C. ADMIN ROUTES (Menggunakan prefix '/admin')
    // ======================================================
    Route::prefix('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // 🔸 Resource Routes
        Route::resource('menus', MenuController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);

        // 🔸 Custom Report
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('admin.reports');
    });

// Group Route Kasir
Route::middleware(['auth'])->prefix('cashier')->name('cashier.')->group(function () {
    
    // Halaman Utama POS
    Route::get('/', [CashierController::class, 'index'])->name('index');
    
    // Proses Checkout
    Route::post('/checkout', [CashierController::class, 'store'])->name('store');
    
    // Cetak Struk
    Route::get('/print/{id}', [CashierController::class, 'print'])->name('print');
    
    // Riwayat Transaksi
    Route::get('/history', [CashierController::class, 'history'])->name('history');
});
});
