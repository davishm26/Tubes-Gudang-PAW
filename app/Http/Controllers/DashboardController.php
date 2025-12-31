<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if demo mode (support multiple session keys, accept any truthy)
        $isDemoMode = (bool) (session('is_demo') || session('demo_mode'));

        if ($isDemoMode) {
            // Demo mode: use dummy data from config
            $demoProducts = collect(config('demo_data.products', []));
            $demoSuppliers = collect(config('demo_data.suppliers', []));
            $demoInventoryIns = collect(config('demo_data.inventory_ins', []));
            $demoInventoryOuts = collect(config('demo_data.inventory_outs', []));

            // Calculate stats from dummy data
            $totalProducts = $demoProducts->count();
            $totalStock = $demoProducts->sum('stock');
            $totalSuppliers = $demoSuppliers->count();
            $lowStockProducts = $demoProducts->where('stock', '<', 10)->values();
            $lowStockCount = $lowStockProducts->count();

            // Chart data - generate 7 days of dummy data
            $days = 7;
            $chartLabels = [];
            $chartDataIn = [];
            $chartDataOut = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartLabels[] = $date->format('d M');
                // Generate random but realistic inventory movements
                $chartDataIn[] = rand(20, 120);
                $chartDataOut[] = rand(10, 80);
            }

            $chartData = [
                'labels' => $chartLabels,
                'data_in' => $chartDataIn,
                'data_out' => $chartDataOut,
            ];

            return view('dashboard', [
                'totalProducts' => $totalProducts,
                'totalStock' => $totalStock,
                'totalSuppliers' => $totalSuppliers,
                'lowStockCount' => $lowStockCount,
                'lowStockProducts' => $lowStockProducts->take(5)->map(fn($p) => (object)$p),
                'chartData' => $chartData,
                'recentActivities' => $demoInventoryIns->concat($demoInventoryOuts)->map(function($item) use ($demoProducts) {
                    $product = $demoProducts->firstWhere('id', $item['product_id']);
                    return (object)[
                        'type' => isset($item['notes']) ? 'Masuk' : 'Keluar',
                        'quantity' => $item['quantity'],
                        'date' => $item['date'],
                        'product' => (object)['name' => $item['product_name']]
                    ];
                })->sortByDesc('date')->take(5),
                'outOfStockProducts' => collect(),
                'overstockProducts' => collect(),
                'inboundToday' => $demoInventoryIns->count(),
                'outboundToday' => $demoInventoryOuts->count(),
                'pendingOrders' => 3,
                'topMoving' => $demoProducts->take(3)->map(fn($p) => (object)$p),
                'slowMoving' => collect(),
                'totalAssetValue' => $demoProducts->sum(fn($p) => $p['price'] * $p['stock']),
                'unreadNotifications' => 0,
            ]);
        }

        // Normal mode - dengan error handling fallback ke demo
        try {
            // --- PERBAIKAN DI SINI ---
            // Kita definisikan variabel $user dengan PHPDoc agar editor tahu ini adalah Model User
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // Cek apakah user login DAN apakah dia super admin
            if ($user && $user->isSuperAdmin()) {
                return redirect()->route('super_admin.dashboard');
            }

            // CHECK: Apakah company user suspended?
            if ($user && $user->company_id && $user->company) {
                if ($user->company->subscription_status === 'suspended' || $user->company->suspended) {
                    Auth::logout();
                    return redirect()->route('subscription.suspended')
                        ->with('error', 'Akun perusahaan Anda telah di-suspend. Silakan hubungi administrator.');
                }
            }
            // -------------------------

            // 1. DATA STATISTIK RINGKASAN (CARDS)
            $totalProducts = Product::count();
            $totalSuppliers = Supplier::count();
            $totalStock = Product::sum('stock');
            $lowStockCount = Product::where('stock', '<=', 10)->count();

            $unreadNotifications = $user
                ? Notification::where('recipient_id', $user->id)->whereNull('read_at')->count()
                : 0;

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

            // Additional Alerts
            $outOfStockProducts = Product::where('stock', '=', 0)->orderBy('name')->get();
            $overstockThreshold = 100;
            $overstockProducts = Product::where('stock', '>=', $overstockThreshold)->orderByDesc('stock')->get();

            // Daily activity (today)
            $inboundToday = InventoryIn::whereDate('date', now()->toDateString())->sum('quantity');
            $outboundToday = InventoryOut::whereDate('date', now()->toDateString())->sum('quantity');

            // Pending orders
            $pendingOrders = 0;

            // Top moving items
            $topPeriodDays = 30;
            $topMoving = InventoryOut::select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->where('date', '>=', now()->subDays($topPeriodDays))
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->with('product')
                ->take(10)
                ->get();

            // Slow moving items
            $slowPeriodDays = 90;
            $activeOutProductIds = InventoryOut::where('date', '>=', now()->subDays($slowPeriodDays))
                ->groupBy('product_id')
                ->pluck('product_id')
                ->toArray();

            $slowMoving = Product::whereNotIn('id', $activeOutProductIds)
                ->where('stock', '>', 0)
                ->take(10)
                ->get();

            // Total asset value
            $totalAssetValue = null;

            // Chart data
            $chartData = [
                'labels' => array_keys($inventoryInHistory + $inventoryOutHistory),
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
                'totalAssetValue',
                'unreadNotifications'
            ));
        } catch (\Exception $e) {
            // Jika DB error, fallback ke demo mode
            Log::error('Dashboard DB Error: ' . $e->getMessage());

            $demoProducts = collect(config('demo_data.products', []));
            $demoSuppliers = collect(config('demo_data.suppliers', []));
            $demoInventoryIns = collect(config('demo_data.inventory_ins', []));
            $demoInventoryOuts = collect(config('demo_data.inventory_outs', []));

            return view('dashboard', [
                'totalProducts' => $demoProducts->count(),
                'totalStock' => $demoProducts->sum('stock'),
                'totalSuppliers' => $demoSuppliers->count(),
                'lowStockCount' => $demoProducts->where('stock', '<', 10)->count(),
                'lowStockProducts' => $demoProducts->where('stock', '<', 10)->take(5)->map(fn($p) => (object)$p),
                'chartData' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    'data_in' => [120, 150, 180, 140, 200, 160],
                    'data_out' => [80, 90, 120, 100, 150, 110],
                ],
                'recentActivities' => collect([
                    (object)['type' => 'Masuk', 'quantity' => 10, 'date' => now()->subDays(1), 'product' => (object)['name' => 'Laptop Demo']],
                    (object)['type' => 'Keluar', 'quantity' => 5, 'date' => now()->subDays(2), 'product' => (object)['name' => 'Mouse Demo']],
                    (object)['type' => 'Masuk', 'quantity' => 15, 'date' => now()->subDays(3), 'product' => (object)['name' => 'Keyboard Mechanical']],
                ]),
                'outOfStockProducts' => collect(),
                'overstockProducts' => collect(),
                'inboundToday' => $demoInventoryIns->count(),
                'outboundToday' => $demoInventoryOuts->count(),
                'pendingOrders' => 3,
                'topMoving' => $demoProducts->take(3)->map(fn($p) => (object)$p),
                'slowMoving' => collect(),
                'totalAssetValue' => $demoProducts->sum(fn($p) => $p['price'] * $p['stock']),
                'unreadNotifications' => 0,
            ]);
        }
    }
}
