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
        $products = Product::all(['id', 'name', 'stock']);
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
    // Gunakan 'with' untuk eager loading (agar query cepat)
    // Gunakan 'paginate(10)' agar halaman rapi (10 data per halaman)
    $inventoryIns = \App\Models\InventoryIn::with(['product', 'supplier', 'user'])
                    ->latest() // Urutkan dari yang terbaru
                    ->paginate(10);

    return view('inventory_in.history', compact('inventoryIns'));
}
}
