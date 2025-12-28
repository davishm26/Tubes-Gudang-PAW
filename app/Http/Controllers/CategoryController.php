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
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $categories = collect(config('demo_data.categories'))->map(fn($c) => (object) $c);

            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower($request->search);
                $categories = $categories->filter(fn($c) => str_contains(strtolower($c->name), $search))->values();
            }

            return view('categories.index', ['categories' => $categories]);
        }

        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $categories = $query->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     */
    public function create()
    {
        if (session('demo_mode')) {
            return view('categories.create');
        }
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $request->validate(['name' => 'required|string|max:255']);
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan! (Simulasi - Data tidak tersimpan)');
        }

        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
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
    public function edit($id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('categories.index')->with('info', 'Edit kategori dinonaktifkan pada demo mode.');
        }

        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage (UPDATE Logic).
     */
    public function update(Request $request, $id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui! (Simulasi - Data tidak tersimpan)');
        }

        $category = Category::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
        ]);

        $category->update($request->only('name'));

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (DELETE Logic).
     */
    public function destroy($id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus! (Simulasi - Data tidak tersimpan)');
        }

        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('categories.index')
                             ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Illuminate\Database\QueryException $e) {
             return redirect()->route('categories.index')
                              ->with('error', 'Gagal menghapus kategori. Pastikan tidak ada Produk yang menggunakan kategori ini.');
        }
    }
}
