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
        // Ambil semua transaksi stok keluar
        $inventoryOuts = InventoryOut::with(['product', 'user'])->latest()->get();
        return view('inventory_out.index', compact('inventoryOuts'));
    }

    /**
     * MENAMPILKAN HALAMAN RIWAYAT (HISTORY)
     */
    public function history()
    {
        // Ambil data stok keluar terbaru beserta relasinya
        $inventoryOuts = InventoryOut::with(['product', 'user'])->latest()->get();

        // Return ke view khusus history
        return view('inventory_out.history', compact('inventoryOuts'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     */
    public function create()
    {
        // Kirim daftar produk ke view untuk dropdown
        $products = Product::all(['id', 'name', 'stock']);
        return view('inventory_out.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
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
