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
    Route::apiResource('products', ProductController::class)->names([
        'index' => 'api.products.index',
        'store' => 'api.products.store',
        'show' => 'api.products.show',
        'update' => 'api.products.update',
        'destroy' => 'api.products.destroy',
    ]);

    // Categories API
    Route::apiResource('categories', CategoryController::class)->names([
        'index' => 'api.categories.index',
        'store' => 'api.categories.store',
        'show' => 'api.categories.show',
        'update' => 'api.categories.update',
        'destroy' => 'api.categories.destroy',
    ]);

    // Suppliers API
    Route::apiResource('suppliers', SupplierController::class)->names([
        'index' => 'api.suppliers.index',
        'store' => 'api.suppliers.store',
        'show' => 'api.suppliers.show',
        'update' => 'api.suppliers.update',
        'destroy' => 'api.suppliers.destroy',
    ]);

    // Inventory In API
    Route::apiResource('inventory-in', InventoryInController::class)->only(['index', 'store', 'show'])->names([
        'index' => 'api.inventory-in.index',
        'store' => 'api.inventory-in.store',
        'show' => 'api.inventory-in.show',
    ]);
    Route::get('inventory-in/history', [InventoryInController::class, 'history'])->name('api.inventory-in.history');

    // Inventory Out API
    Route::apiResource('inventory-out', InventoryOutController::class)->only(['index', 'store', 'show'])->names([
        'index' => 'api.inventory-out.index',
        'store' => 'api.inventory-out.store',
        'show' => 'api.inventory-out.show',
    ]);
    Route::get('inventory-out/history', [InventoryOutController::class, 'history'])->name('api.inventory-out.history');

    // Users API (Admin only)
    Route::apiResource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);
});
