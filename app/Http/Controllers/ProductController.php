<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // DEMO MODE: gunakan data dari session, tidak query DB
        if (session('demo_mode')) {
            // Ambil dari session lalu normalisasi ke object agar Blade bisa akses ->property
            $products = collect(session('demo_products', []))->map(function ($p) {
                $obj = is_array($p) ? (object) $p : $p;
                if (isset($obj->category)) {
                    $obj->category = is_array($obj->category) ? (object) $obj->category : $obj->category;
                }
                if (isset($obj->supplier)) {
                    $obj->supplier = is_array($obj->supplier) ? (object) $obj->supplier : $obj->supplier;
                }
                return $obj;
            });

            // Seed contoh jika belum ada
            if ($products->isEmpty()) {
                $products = collect([
                    [
                        'id' => 1,
                        'name' => 'Laptop Demo',
                        'sku' => 'DEM001',
                        'stock' => 15,
                        'category' => (object)['name' => 'Elektronik'],
                        'supplier' => (object)['name' => 'Demo Supplier'],
                        'image' => null,
                    ],
                    [
                        'id' => 2,
                        'name' => 'Mouse Demo',
                        'sku' => 'DEM002',
                        'stock' => 50,
                        'category' => (object)['name' => 'Elektronik'],
                        'supplier' => (object)['name' => 'Demo Supplier'],
                        'image' => null,
                    ],
                ])->map(function ($p) { return (object) $p; });

                session(['demo_products' => $products]);
            }

            // Pencarian sederhana di session
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
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menambah produk.');
        }
        if (session('demo_mode')) {
            // Normalisasi kategori/supplier dari session ke object
            $categories = collect(session('demo_categories', []))->map(fn($c) => is_array($c) ? (object) $c : $c);
            if ($categories->isEmpty()) {
                $categories = collect([
                    ['id' => 1, 'name' => 'Elektronik'],
                    ['id' => 2, 'name' => 'Furniture'],
                    ['id' => 3, 'name' => 'Alat Tulis'],
                ])->map(fn($c) => (object) $c);
                session(['demo_categories' => $categories]);
            }

            $suppliers = collect(session('demo_suppliers', []))->map(fn($s) => is_array($s) ? (object) $s : $s);
            if ($suppliers->isEmpty()) {
                $suppliers = collect([
                    ['id' => 1, 'name' => 'CV Furniture Indo'],
                    ['id' => 2, 'name' => 'Toko ATK Sejahtera'],
                ])->map(fn($s) => (object) $s);
                session(['demo_suppliers' => $suppliers]);
            }
            return view('products.create', compact('categories', 'suppliers'));
        }
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menambah produk.');
        }
        if (session('demo_mode')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $categories = collect(session('demo_categories'));
            $suppliers = collect(session('demo_suppliers'));
            $category = $categories->firstWhere('id', (int)$request->category_id);
            $supplier = $suppliers->firstWhere('id', (int)$request->supplier_id);

            $products = collect(session('demo_products'));
            $nextId = ($products->max('id') ?? 0) + 1;

            $imagePath = null;
            if ($request->hasFile('image')) {
                // Simpan ke storage publik agar preview jalan; ini tetap tidak ke DB
                $imagePath = $request->file('image')->store('products-demo', 'public');
            }

            $new = (object) [
                'id' => $nextId,
                'name' => $request->name,
                'sku' => $request->sku,
                'stock' => (int) $request->stock,
                'category' => $category ? (object)['name' => $category->name] : null,
                'supplier' => $supplier ? (object)['name' => $supplier->name] : null,
                'image' => $imagePath,
            ];

            $products = $products->push($new);
            session(['demo_products' => $products]);

            return redirect()->route('products.index')
                ->with('success', 'Produk demo ditambahkan (tidak tersimpan di database).');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengedit produk.');
        }
        if (session('demo_mode')) {
            return redirect()->route('products.index')->with('info', 'Edit produk dinonaktifkan pada demo mode.');
        }
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengupdate produk.');
        }
        if (session('demo_mode') === 'true') {
            return redirect()->route('products.index')->with('info', 'Update produk dinonaktifkan pada demo mode.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', Rule::unique('products')->ignore($product->id)],
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

    public function destroy(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat menghapus produk.');
        }
        if (session('demo_mode') === 'true') {
            return redirect()->route('products.index')->with('info', 'Hapus produk dinonaktifkan pada demo mode.');
        }
        // Hapus file fisik dari storage disk 'public'
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
