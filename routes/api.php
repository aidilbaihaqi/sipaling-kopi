<?php

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
