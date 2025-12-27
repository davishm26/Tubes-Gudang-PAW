<?php

namespace App\Http\Controllers;

use App\Models\InventoryIn;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryInController extends Controller
{
    /**
     * Display a listing of the resource (READ Transaksi Masuk).
     * Biasanya digunakan untuk halaman utama manajemen stok masuk (index Resource).
     * Route Name: inventory-in.index
     */
    public function index()
    {
        if (session('demo_mode')) {
            $items = collect(session('demo_inventory_in', []));
            if ($items->isEmpty()) {
                $items = collect([
                    [
                        'date' => now()->subDay()->toDateString(),
                        'created_at' => now()->subDay(),
                        'quantity' => 5,
                        'description' => 'Contoh pemasukan',
                        'product' => ['name' => 'Produk Demo A', 'sku' => 'DEM-A'],
                        'supplier' => ['name' => 'Demo Supplier'],
                        'user' => ['name' => 'Demo Admin'],
                    ],
                    [
                        'date' => now()->toDateString(),
                        'created_at' => now(),
                        'quantity' => 3,
                        'description' => 'Contoh pemasukan 2',
                        'product' => ['name' => 'Produk Demo B', 'sku' => 'DEM-B'],
                        'supplier' => ['name' => 'Demo Vendor'],
                        'user' => ['name' => 'Demo Staff'],
                    ],
                ]);
                session(['demo_inventory_in' => $items]);
            }
            $inventoryIns = $items->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) $item['product'];
                $obj->supplier = (object) $item['supplier'];
                $obj->user = (object) $item['user'];
                return $obj;
            });
            return view('inventory_in.index', compact('inventoryIns'));
        }
        // Ambil semua transaksi stok masuk
        $inventoryIns = InventoryIn::with(['product', 'user'])->latest()->get();
        // Anda mungkin ingin menggunakan view yang sama dengan history, atau ini adalah view khusus untuk index.
        return view('inventory_in.index', compact('inventoryIns'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     * Route Name: inventory-in.create
     */
    public function create()
    {
        if (session('demo_mode')) {
            // Ambil produk dan pemasok dari session demo agar form tetap berfungsi tanpa DB
            $products = collect(session('demo_products', []))->map(function ($p) {
                $obj = is_array($p) ? (object) $p : $p;
                return (object) [
                    'id' => $obj->id ?? null,
                    'name' => $obj->name ?? 'Produk Demo',
                    'stock' => $obj->stock ?? 0,
                ];
            });
            $suppliers = collect(session('demo_suppliers', []))->map(function ($s) {
                $obj = is_array($s) ? (object) $s : $s;
                return (object) [
                    'id' => $obj->id ?? null,
                    'name' => $obj->name ?? 'Demo Supplier',
                ];
            });
            return view('inventory_in.create', compact('products', 'suppliers'));
        }
        $products = Product::with('supplier')->get(['id', 'name', 'stock', 'supplier_id']);
        // Pastikan Anda mengimpor Supplier di bagian atas jika tidak ingin menggunakan namespace penuh
        $suppliers = \App\Models\Supplier::all(['id', 'name']);
        return view('inventory_in.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     * Route Name: inventory-in.store
     */
    public function store(Request $request)
    {
        if (session('demo_mode')) {
            $request->validate([
                'product_id' => 'required',
                'quantity' => 'required|integer|min:1',
                'date' => 'required|date',
                'description' => 'nullable|string|max:255',
            ]);

            // Cari produk dari session demo_products, jangan query DB
            $demoProducts = collect(session('demo_products', []))->map(fn($p) => (object) (is_array($p) ? $p : (array) $p));
            $product = $demoProducts->firstWhere('id', (int) $request->product_id);

            $demoEntry = [
                'date' => $request->date,
                'created_at' => now(),
                'quantity' => $request->quantity,
                'description' => $request->description,
                'product' => $product ? ['name' => $product->name ?? 'Produk Demo', 'sku' => $product->sku ?? '-'] : ['name' => 'Produk Demo', 'sku' => '-'],
                'supplier' => ['name' => 'Demo Supplier'],
                'user' => ['name' => 'Demo User'],
            ];

            $existing = collect(session('demo_inventory_in', []));
            $updated = $existing->prepend($demoEntry)->values();
            session(['demo_inventory_in' => $updated]);

            return redirect()->route('inventory-in.history')
                ->with('success', 'Data demo stok masuk ditambahkan (tidak disimpan ke database).');
        }
        // 1. Validasi Data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // 2. Update Stok Produk (LOGIC UTAMA: Tambah Stok)
        $product = Product::find($request->product_id);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.')->withInput();
        }

        // TAMBAHKAN STOK PADA PRODUK
        $product->stock += $request->quantity;
        $product->save();

        // 3. Catat Transaksi
        InventoryIn::create([
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        // REDIRECT KE HISTORY (Riwayat)
        return redirect()->route('inventory-in.history')
            ->with('success', 'Stok masuk berhasil dicatat. Stok produk telah diperbarui.');
    }

    /**
     * Menampilkan halaman khusus Riwayat (History).
     * Route Name: inventory-in.history
     */
    public function history()
    {
        if (session('demo_mode')) {
            $items = collect(session('demo_inventory_in', []));
            if ($items->isEmpty()) {
                $items = collect([
                    [
                        'date' => now()->subDays(2)->toDateString(),
                        'created_at' => now()->subDays(2),
                        'quantity' => 10,
                        'description' => 'Contoh pemasukan history',
                        'product' => ['name' => 'Produk Demo A', 'sku' => 'DEM-A'],
                        'supplier' => ['name' => 'Demo Supplier'],
                        'user' => ['name' => 'Demo Admin'],
                    ],
                    [
                        'date' => now()->subDay()->toDateString(),
                        'created_at' => now()->subDay(),
                        'quantity' => 7,
                        'description' => 'Contoh pemasukan history 2',
                        'product' => ['name' => 'Produk Demo B', 'sku' => 'DEM-B'],
                        'supplier' => ['name' => 'Demo Vendor'],
                        'user' => ['name' => 'Demo Staff'],
                    ],
                ]);
                session(['demo_inventory_in' => $items]);
            }
            $items = $items->map(function ($item) {
                $obj = (object) $item;
                $obj->product = isset($item['product']) ? (object) $item['product'] : null;
                $obj->supplier = isset($item['supplier']) ? (object) $item['supplier'] : null;
                $obj->user = isset($item['user']) ? (object) $item['user'] : null;
                return $obj;
            });

            $inventoryIns = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $items->count(),
                10,
                1,
                ['path' => request()->url()]
            );

            return view('inventory_in.history', compact('inventoryIns'));
        }

        // Gunakan 'with' untuk eager loading (agar query cepat)
        // Gunakan 'paginate(10)' agar halaman rapi (10 data per halaman)
        $inventoryIns = \App\Models\InventoryIn::with(['product', 'supplier', 'user'])
                        ->latest() // Urutkan dari yang terbaru
                        ->paginate(10);

        return view('inventory_in.history', compact('inventoryIns'));
    }
}
