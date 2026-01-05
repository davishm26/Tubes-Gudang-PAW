<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\InventoryInController;
use App\Http\Controllers\Api\InventoryOutController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Products API
    Route::apiResource('products', ProductController::class);

    // Categories API
    Route::apiResource('categories', CategoryController::class);

    // Suppliers API
    Route::apiResource('suppliers', SupplierController::class);

    // Inventory In API
    Route::apiResource('inventory-in', InventoryInController::class)->only(['index', 'store', 'show']);
    Route::get('inventory-in/history', [InventoryInController::class, 'history']);

    // Inventory Out API
    Route::apiResource('inventory-out', InventoryOutController::class)->only(['index', 'store', 'show']);
    Route::get('inventory-out/history', [InventoryOutController::class, 'history']);

    // Users API (Admin only)
    Route::apiResource('users', UserController::class);
});
