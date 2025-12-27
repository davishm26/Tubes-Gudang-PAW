<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if demo mode
        if (session('demo_mode') === 'true') {
            // Demo mode should not touch real data; return empty/zeroed dataset
            $chartData = [
                'labels' => [],
                'data_in' => [],
                'data_out' => [],
            ];

            return view('dashboard', [
                'totalProducts' => 0,
                'totalStock' => 0,
                'totalSuppliers' => 0,
                'lowStockCount' => 0,
                'lowStockProducts' => collect(),
                'chartData' => $chartData,
                'recentActivities' => collect(),
                'outOfStockProducts' => collect(),
                'overstockProducts' => collect(),
                'inboundToday' => 0,
                'outboundToday' => 0,
                'pendingOrders' => 0,
                'topMoving' => collect(),
                'slowMoving' => collect(),
                'totalAssetValue' => null,
            ]);
        }

        // Normal mode
        // --- PERBAIKAN DI SINI ---
        // Kita definisikan variabel $user dengan PHPDoc agar editor tahu ini adalah Model User
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Cek apakah user login DAN apakah dia super admin
        if ($user && $user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard');
        }
        // -------------------------

        // 1. DATA STATISTIK RINGKASAN (CARDS)
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalStock = Product::sum('stock');
        $lowStockCount = Product::where('stock', '<=', 10)->count(); // Contoh: Stok di bawah 10 dianggap Rendah

        // 2. DATA GRAFIK (Untuk 30 hari terakhir)
        $days = 30;
        $dateLimit = now()->subDays($days);

        // Data Stok Masuk dan Keluar per Hari
        $inventoryInHistory = InventoryIn::select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(quantity) as total_in')
            )
            ->where('date', '>=', $dateLimit)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total_in', 'day')
            ->toArray();

        $inventoryOutHistory = InventoryOut::select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(quantity) as total_out')
            )
            ->where('date', '>=', $dateLimit)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total_out', 'day')
            ->toArray();

        // 3. TABEL NOTIFIKASI/AKTIVITAS
        // Ambil 2 query terpisah (masuk + keluar), merge di collection supaya relasi dapat eager-loaded
        $inActivities = InventoryIn::select('date', 'quantity', 'product_id', DB::raw("'Masuk' as type"), 'user_id')
            ->with(['product', 'user'])
            ->get();

        $outActivities = InventoryOut::select('date', 'quantity', 'product_id', DB::raw("'Keluar' as type"), 'user_id')
            ->with(['product', 'user'])
            ->get();

        $recentActivities = $inActivities->concat($outActivities)
            ->sortByDesc(function ($item) {
                return $item->date;
            })
            ->values()
            ->take(8);

        // Notifikasi Stok Rendah
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock')->get();

        // Additional Alerts: Out of Stock, Overstock
        $outOfStockProducts = Product::where('stock', '=', 0)->orderBy('name')->get();
        $overstockThreshold = 100; // configurable threshold (adjust as needed)
        $overstockProducts = Product::where('stock', '>=', $overstockThreshold)->orderByDesc('stock')->get();

        // Daily activity (today)
        $inboundToday = InventoryIn::whereDate('date', now()->toDateString())->sum('quantity');
        $outboundToday = InventoryOut::whereDate('date', now()->toDateString())->sum('quantity');

        // Pending orders: no orders table in this project; default to 0
        $pendingOrders = 0;

        // Top moving items (last 30 days)
        $topPeriodDays = 30;
        $topMoving = InventoryOut::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('date', '>=', now()->subDays($topPeriodDays))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(10)
            ->get();

        // Slow moving items: products with NO outs in last 90 days
        $slowPeriodDays = 90;
        $activeOutProductIds = InventoryOut::where('date', '>=', now()->subDays($slowPeriodDays))
            ->groupBy('product_id')
            ->pluck('product_id')
            ->toArray();

        $slowMoving = Product::whereNotIn('id', $activeOutProductIds)
            ->where('stock', '>', 0)
            ->take(10)
            ->get();

        // Total asset value: requires a cost/price column which doesn't exist in schema. set null
        $totalAssetValue = null;

        // Siapkan data untuk Chart JS di View
        $chartData = [
            'labels' => array_keys($inventoryInHistory + $inventoryOutHistory), // Semua tanggal unik
            'data_in' => [],
            'data_out' => [],
        ];

        foreach ($chartData['labels'] as $date) {
            $chartData['data_in'][] = $inventoryInHistory[$date] ?? 0;
            $chartData['data_out'][] = $inventoryOutHistory[$date] ?? 0;
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalStock',
            'lowStockCount',
            'recentActivities',
            'lowStockProducts',
            'chartData',
            'outOfStockProducts',
            'overstockProducts',
            'inboundToday',
            'outboundToday',
            'pendingOrders',
            'topMoving',
            'slowMoving',
            'totalAssetValue'
        ));
    }
}
