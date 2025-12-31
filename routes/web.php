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
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DemoController; // <-- TAMBAHKAN INI
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SuspendedController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [SubscriptionController::class, 'landing'])->name('subscription.landing');
Route::match(['get', 'post'], '/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
Route::get('/payment', [SubscriptionController::class, 'payment'])->name('subscription.payment');
Route::post('/pay/{token}', [SubscriptionController::class, 'pay'])->name('subscription.pay');
Route::get('/suspended', [SuspendedController::class, 'show'])->middleware('auth')->name('subscription.suspended');

// Demo Mode Routes - Mode Demo Statis (NEW)
Route::get('/demo/{role}', [DemoController::class, 'enter'])->name('demo.enter');
Route::get('/demo-exit', [DemoController::class, 'exit'])->name('demo.exit');
Route::get('/demo-info', [DemoController::class, 'info'])->name('demo.info');

// Demo Mode Routes (Old - Keep untuk kompatibilitas)
Route::post('/demo/start', [SubscriptionController::class, 'startDemo'])->name('demo.start');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Akses Setelah Login - Admin & Staf)
| Note: Demo mode dapat mengakses route ini melalui DemoModeMiddleware
|--------------------------------------------------------------------------
*/
Route::middleware([\App\Http\Middleware\DemoOrAuthMiddleware::class, \App\Http\Middleware\DemoModeMiddleware::class])->group(function () {

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

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
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
    Route::post('/tenants/{company}/send-notification', [TenantController::class, 'sendNotification'])->name('tenants.send-notification');
    Route::delete('/tenants/{company}', [TenantController::class, 'destroy'])->name('tenants.destroy');

    // Financial report
    Route::get('/financial-report', [SuperAdminController::class, 'financialReport'])->name('financial-report');
    Route::post('/financial-report/download', [SuperAdminController::class, 'downloadFinancialReport'])->name('financial-report.download');

    // Notifications
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
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
