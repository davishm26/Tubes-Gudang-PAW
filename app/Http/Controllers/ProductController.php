<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\InventoryIn;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // DEMO MODE: gunakan data dari config
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            // Ambil data dari config/demo_data.php
            $products = collect(config('demo_data.products', []))->map(function ($p) {
                $obj = (object) $p;
                $obj->category = (object) ['name' => $p['category_name']];
                $obj->supplier = (object) ['name' => $p['supplier_name']];
                $obj->sku = $p['code']; // Map 'code' to 'sku' for compatibility
                // Demo image fallback
                $obj->image = $p['image'] ?? 'https://placehold.co/80x80?text=Demo';
                $obj->image_path = $obj->image;
                return $obj;
            });


            // Pencarian sederhana
            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower($request->search);
                $products = $products->filter(function ($p) use ($search) {
                    return str_contains(strtolower($p->name), $search)
                        || str_contains(strtolower($p->sku), $search)
                        || ($p->category && str_contains(strtolower($p->category->name), $search))
                        || ($p->supplier && str_contains(strtolower($p->supplier->name), $search));
                })->values();
            }

            return view('products.index', ['products' => $products]);
        }

        $query = Product::with(['category', 'supplier']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        $products = $query->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Demo mode: allow access without role check
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            // Ambil data dari config/demo_data.php
            $categories = collect(config('demo_data.categories', []))->map(fn($c) => (object) $c);
            $suppliers = collect(config('demo_data.suppliers', []))->map(fn($s) => (object) $s);
            return view('products.create', compact('categories', 'suppliers'));
        }

        // Real mode: check role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menambah produk.');
        }
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Demo mode: allow access without role check
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan! (Simulasi - Data tidak tersimpan)');
        }

        // Real mode: check role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menambah produk.');
        }

        $companyId = Auth::user()?->company_id ?? $request->get('company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                Rule::unique('products', 'sku')->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');
        $data['company_id'] = $companyId;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);

        // Jika ada stok awal, catat di InventoryIn
        if ($request->input('stock', 0) > 0) {
            InventoryIn::create([
                'company_id' => $companyId,
                'product_id' => $product->id,
                'supplier_id' => $request->input('supplier_id'),
                'quantity' => $request->input('stock'),
                'date' => now(),
                'description' => 'Penambahan stok produk baru',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Tidak digunakan, arahkan kembali
        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        // Demo mode: show edit form with demo data
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            // Convert product ID to demo data
            $demoProducts = collect(config('demo_data.products', []));
            $demoProduct = $demoProducts->firstWhere('id', (int)$id);

            if ($demoProduct) {
                $product = (object) $demoProduct;
                $product->sku = $demoProduct['code'];
            }

            $categories = collect(config('demo_data.categories', []))->map(fn($c) => (object) $c);
            $suppliers = collect(config('demo_data.suppliers', []))->map(fn($s) => (object) $s);
            return view('products.edit', compact('product', 'categories', 'suppliers'));
        }

        // Real mode: check role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengedit produk.');
        }
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        // Demo mode: disable update
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diperbarui! (Simulasi - Data tidak tersimpan)');
        }

        // Real mode: check role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengupdate produk.');
        }
        $product = Product::findOrFail($id);
        $companyId = $product->company_id ?? Auth::user()?->company_id ?? $request->get('company_id');
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                Rule::unique('products', 'sku')
                    ->ignore($product->id)
                    ->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                // Pastikan menghapus dari disk 'public'
                Storage::disk('public')->delete($product->image);
            }

            // Simpan gambar baru dengan format path yang benar
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Demo mode: disable delete
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus! (Simulasi - Data tidak tersimpan)');
        }

        // Real mode: check role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menghapus produk.');
        }
        $product = Product::findOrFail($id);
        // Hapus file fisik dari storage disk 'public'
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
