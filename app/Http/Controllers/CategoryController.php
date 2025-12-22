<?php

namespace App\Http\Controllers;

use App\Models\Category; // Import Model Category
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import untuk validasi unique saat update

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $categories = $query->get();

        // Tampilkan view index dengan data kategori
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     */
    public function create()
    {
        // Tampilkan form untuk menambahkan kategori baru
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            // Wajib diisi, maksimal 255 karakter, dan harus unik di tabel 'categories'
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // 2. Simpan ke Database
        Category::create($request->only('name')); // Hanya ambil kolom 'name'

        // 3. Redirect dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource (SHOW - Biasanya tidak diperlukan untuk CRUD sederhana).
     */
    public function show(Category $category)
    {
        // Karena kita menggunakan Model Binding, $category otomatis berisi data kategori yang dicari
        // return view('categories.show', compact('category'));
        // Kita bisa biarkan ini kosong jika tidak membuat halaman detail terpisah
    }

    /**
     * Show the form for editing the specified resource (UPDATE Form).
     */
    public function edit(Category $category)
    {
        // Tampilkan form edit dengan data kategori yang sudah ada
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage (UPDATE Logic).
     */
    public function update(Request $request, Category $category)
    {
        // 1. Validasi Data
        $request->validate([
            // Gunakan Rule::unique() untuk mengecualikan kategori yang sedang di-edit dari cek unik
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
        ]);

        // 2. Update Data
        $category->update($request->only('name'));

        // 3. Redirect dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (DELETE Logic).
     */
    public function destroy(Category $category)
    {
        try {
            // Hapus data kategori
            $category->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('categories.index')
                             ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error jika kategori masih digunakan (relasi dengan produk)
             return redirect()->route('categories.index')
                              ->with('error', 'Gagal menghapus kategori. Pastikan tidak ada Produk yang menggunakan kategori ini.');
        }
    }
}
