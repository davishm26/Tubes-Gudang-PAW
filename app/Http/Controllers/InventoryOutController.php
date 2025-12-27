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
        if (session('demo_mode')) {
            $items = collect(session('demo_inventory_out', []));
            if ($items->isEmpty()) {
                $items = collect([
                    [
                        'date' => now()->subDay()->toDateString(),
                        'quantity' => 2,
                        'description' => 'Contoh pengeluaran',
                        'product' => ['name' => 'Produk Demo A'],
                    ],
                    [
                        'date' => now()->toDateString(),
                        'quantity' => 1,
                        'description' => 'Contoh pengeluaran 2',
                        'product' => ['name' => 'Produk Demo B'],
                    ],
                ]);
                session(['demo_inventory_out' => $items]);
            }
            $inventoryOuts = $items->map(function ($item) {
                $obj = (object) $item;
                $obj->product = (object) $item['product'];
                return $obj;
            });
            return view('inventory_out.index', compact('inventoryOuts'));
        }
        // Ambil semua transaksi stok keluar
        $inventoryOuts = InventoryOut::with(['product', 'user'])->latest()->get();
        return view('inventory_out.index', compact('inventoryOuts'));
    }

    /**
     * MENAMPILKAN HALAMAN RIWAYAT (HISTORY)
     */
    public function history()
    {
        if (session('demo_mode')) {
            $items = collect(session('demo_inventory_out', []));
            if ($items->isEmpty()) {
                $items = collect([
                    [
                        'date' => now()->subDays(2)->toDateString(),
                        'quantity' => 5,
                        'description' => 'Contoh pengeluaran history',
                        'product' => ['name' => 'Produk Demo A'],
                    ],
                    [
                        'date' => now()->subDay()->toDateString(),
                        'quantity' => 2,
                        'description' => 'Contoh pengeluaran history 2',
                        'product' => ['name' => 'Produk Demo B'],
                    ],
                ]);
                session(['demo_inventory_out' => $items]);
            }
            $items = $items->map(function ($item) {
                $obj = (object) $item;
                $obj->product = isset($item['product']) ? (object) $item['product'] : null;
                return $obj;
            });

            return view('inventory_out.history', ['inventoryOuts' => $items]);
        }
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
        if (session('demo_mode')) {
            // Ambil produk dari session demo agar form bekerja tanpa DB
            $products = collect(session('demo_products', []))->map(function ($p) {
                $obj = is_array($p) ? (object) $p : $p;
                return (object) [
                    'id' => $obj->id ?? null,
                    'name' => $obj->name ?? 'Produk Demo',
                    'stock' => $obj->stock ?? 0,
                ];
            });
            return view('inventory_out.create', compact('products'));
        }
        // Kirim daftar produk ke view untuk dropdown
        $products = Product::all(['id', 'name', 'stock']);
        return view('inventory_out.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
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
            // Cari produk dari session demo_products
            $demoProducts = collect(session('demo_products', []))->map(fn($p) => (object) (is_array($p) ? $p : (array) $p));
            $product = $demoProducts->firstWhere('id', (int) $request->product_id);

            $demoEntry = [
                'date' => $request->date,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'product' => $product ? ['name' => $product->name ?? 'Produk Demo'] : ['name' => 'Produk Demo'],
            ];

            $existing = collect(session('demo_inventory_out', []));
            $updated = $existing->prepend($demoEntry)->values();
            session(['demo_inventory_out' => $updated]);

            return redirect()->route('inventory-out.history')
                ->with('success', 'Data demo stok keluar ditambahkan (tidak disimpan ke database).');
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
