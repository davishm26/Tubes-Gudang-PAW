<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\InventoryInController;
use App\Http\Controllers\Api\InventoryOutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Grouping dengan prefix nama 'api.' untuk mencegah bentrok dengan web.php
Route::name('api.')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('suppliers', SupplierController::class);
});

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'user');
    });
});
