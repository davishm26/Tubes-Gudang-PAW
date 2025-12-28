<?php

namespace App\Http\Controllers;

use App\Models\InventoryOut;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException; // Tidak diperlukan di sini, tetapi tidak masalah jika ada

class InventoryOutController extends Controller
{
    /**
     * Display a listing of the resource (READ Transaksi Keluar / Manajemen).
     */
    public function index()
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $inventoryOuts = collect(config('demo_data.inventory_outs'))->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) ['name' => $item['product_name']];
                $obj->user = (object) session('demo_user');
                return $obj;
            });
            return view('inventory_out.index', compact('inventoryOuts'));
        }

        $inventoryOuts = InventoryOut::with(['product', 'user'])->latest()->get();
        return view('inventory_out.index', compact('inventoryOuts'));
    }

    /**
     * MENAMPILKAN HALAMAN RIWAYAT (HISTORY)
     */
    public function history()
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $inventoryOuts = collect(config('demo_data.inventory_outs'))->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) ['name' => $item['product_name']];
                $obj->user = (object) session('demo_user');
                return $obj;
            });
            return view('inventory_out.history', compact('inventoryOuts'));
        }

        $inventoryOuts = InventoryOut::with(['product', 'user'])->latest()->get();
        return view('inventory_out.history', compact('inventoryOuts'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
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
            return view('inventory_out.create', compact('products'));
        }

        $products = Product::all(['id', 'name', 'stock']);
        return view('inventory_out.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
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

            return redirect()->route('inventory-out.history')
                ->with('success', 'Stok keluar berhasil dicatat! (Simulasi - Data tidak tersimpan)');
        }
        // 1. Validasi Data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255', // <--- VALIDASI DESCRIPTION
        ]);

        // 2. Cek dan Update Stok Produk (LOGIC UTAMA)
        $product = Product::find($request->product_id);
        $quantityOut = $request->quantity; // Kuantitas yang ingin dikeluarkan

        // --- LOGIC PENTING: CEK STOK SEBELUM MENGURANGI ---
        if ($product->stock < $quantityOut) {
            // Jika stok tidak cukup, kembalikan error
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi! Stok saat ini: ' . $product->stock . ' unit.')
                ->withInput(); // Mengembalikan input yang sudah diisi
        }

        // --- LOGIC PENGURANGAN STOK ---
        $product->stock -= $quantityOut;
        $product->save(); // Simpan perubahan stok ke database

        // 3. Catat Transaksi
        InventoryOut::create([
            'product_id' => $request->product_id,
            'quantity' => $quantityOut,
            'date' => $request->date,
            'description' => $request->description, // <--- SIMPAN DESCRIPTION
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('inventory-out.history') // Arahkan ke history
            ->with('success', 'Stok keluar berhasil dicatat. Stok produk telah diperbarui.');
    }

    // show, edit, update, destroy diabaikan
}
