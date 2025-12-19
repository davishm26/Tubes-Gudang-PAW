<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // <-- TAMBAHKAN INI
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\InventoryOutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Akses Setelah Login - Admin & Staf)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard - Diubah dari closure menjadi memanggil Controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Route Custom: History / Riwayat (Laporan)
    Route::get('/inventory-in/history', [InventoryInController::class, 'history'])->name('inventory-in.history');
    Route::get('/inventory-out/history', [InventoryOutController::class, 'history'])->name('inventory-out.history');

    // 3. Route Resource: Transaksi Stok (Pencatatan)
    Route::resource('inventory-in', InventoryInController::class)->only(['index', 'create', 'store']);
    Route::resource('inventory-out', InventoryOutController::class)->only(['index', 'create', 'store']);

    // 4. Route Resource: Data Master (Produk, Supplier, Kategori)
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);
    // 5. Route Resource: User Management (Hanya Admin)
    // Use the middleware class directly to avoid alias resolution issues
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware(\App\Http\Middleware\AdminMiddleware::class);
});


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
