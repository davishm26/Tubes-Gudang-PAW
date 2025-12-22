<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // <-- TAMBAHKAN INI
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\InventoryOutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantController;
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
    Route::resource('inventory-in', InventoryInController::class)->only(['index', 'create', 'store'])
        ->middleware(\App\Http\Middleware\NotSuperAdminMiddleware::class);
    Route::resource('inventory-out', InventoryOutController::class)->only(['index', 'create', 'store'])
        ->middleware(\App\Http\Middleware\NotSuperAdminMiddleware::class);

    // 4. Route Resource: Data Master (Produk, Supplier, Kategori)
    Route::resource('products', ProductController::class)->middleware(\App\Http\Middleware\NotSuperAdminMiddleware::class);
    Route::resource('suppliers', SupplierController::class)->middleware(\App\Http\Middleware\NotSuperAdminMiddleware::class);
    Route::resource('categories', CategoryController::class)->middleware(\App\Http\Middleware\NotSuperAdminMiddleware::class);
    // 5. Route Resource: User Management (Hanya Admin)
    // Use the middleware class directly to avoid alias resolution issues
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware(\App\Http\Middleware\AdminMiddleware::class);
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes (Akses Khusus Super Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('super-admin')->name('super_admin.')->middleware(['auth','verified', \App\Http\Middleware\SuperAdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Tenant management (companies)
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{company}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{company}', [TenantController::class, 'update'])->name('tenants.update');
    Route::post('/tenants/{company}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('/tenants/{company}/unsuspend', [TenantController::class, 'unsuspend'])->name('tenants.unsuspend');
    Route::delete('/tenants/{company}', [TenantController::class, 'destroy'])->name('tenants.destroy');
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
