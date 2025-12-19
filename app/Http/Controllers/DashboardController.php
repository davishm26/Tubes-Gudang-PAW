<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
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
        $recentActivities = InventoryIn::select('date', 'quantity', 'product_id', DB::raw('"Masuk" as type'), 'user_id')
            ->union(
                InventoryOut::select('date', 'quantity', 'product_id', DB::raw('"Keluar" as type'), 'user_id')
            )
            ->with(['product', 'user']) // Pastikan relasi ini ada di model InventoryIn dan InventoryOut
            ->orderBy('date', 'desc')
            ->take(8)
            ->get();

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
