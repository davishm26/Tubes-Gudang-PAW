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
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $inventoryIns = collect(config('demo_data.inventory_ins'))->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) ['name' => $item['product_name']];
                $obj->user = (object) session('demo_user');
                return $obj;
            });
            return view('inventory_in.index', compact('inventoryIns'));
        }

        $inventoryIns = InventoryIn::with(['product', 'user'])->latest()->get();
        return view('inventory_in.index', compact('inventoryIns'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     * Route Name: inventory-in.create
     */
    public function create()
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $products = collect(config('demo_data.products'))->map(function ($p) {
                return (object) [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'stock' => $p['stock'],
                ];
            });
            $suppliers = collect(config('demo_data.suppliers'))->map(function ($s) {
                return (object) [
                    'id' => $s['id'],
                    'name' => $s['name'],
                ];
            });
            return view('inventory_in.create', compact('products', 'suppliers'));
        }

        $products = Product::with('supplier')->get(['id', 'name', 'stock', 'supplier_id']);
        $suppliers = \App\Models\Supplier::all(['id', 'name']);
        return view('inventory_in.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     * Route Name: inventory-in.store
     */
    public function store(Request $request)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $request->validate([
                'product_id' => 'required',
                'quantity' => 'required|integer|min:1',
                'date' => 'required|date',
                'description' => 'nullable|string|max:255',
            ]);

            return redirect()->route('inventory-in.history')
                ->with('success', 'Data stok masuk berhasil ditambahkan! (Simulasi - Data tidak tersimpan)');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.')->withInput();
        }

        $product->stock += $request->quantity;
        $product->save();

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
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $inventoryIns = collect(config('demo_data.inventory_ins'))->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) ['name' => $item['product_name']];
                $obj->supplier = (object) ['name' => 'Demo Supplier'];
                $obj->user = (object) session('demo_user');
                $obj->created_at = $item['date'];
                return $obj;
            });

            $inventoryIns = new \Illuminate\Pagination\LengthAwarePaginator(
                $inventoryIns,
                $inventoryIns->count(),
                10,
                request()->get('page', 1),
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
