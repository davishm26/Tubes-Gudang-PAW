<?php

namespace App\Http\Controllers;

use App\Models\Supplier; // <-- WAJIB: Import Model Supplier
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- WAJIB: Import Rule untuk validasi unique saat update

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('contact', 'like', '%' . $search . '%');
        }

        $suppliers = $query->get();

        // Tampilkan view index pemasok, kirim data supplier
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     */
    public function create()
    {
        // Tampilkan form untuk menambahkan pemasok baru
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            // Nama pemasok harus unik
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact' => 'nullable|string|max:255', // Kontak boleh kosong
        ]);

        // 2. Simpan ke Database
        Supplier::create($request->only(['name', 'contact']));

        // 3. Redirect dengan pesan sukses
        return redirect()->route('suppliers.index')
                         ->with('success', 'Pemasok baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource (SHOW).
     */
    // Menggunakan Route Model Binding: $supplier otomatis berisi data Supplier
    public function show(Supplier $supplier)
    {
        // Tidak perlu implementasi detail jika tidak ada halaman SHOW khusus
        return redirect()->route('suppliers.index');
    }

    /**
     * Show the form for editing the specified resource (UPDATE Form).
     */
    public function edit(Supplier $supplier)
    {
        // Tampilkan form edit dengan data supplier yang sudah ada
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage (UPDATE Logic).
     */
    public function update(Request $request, Supplier $supplier)
    {
        // 1. Validasi Data
        $request->validate([
            // Nama harus unik, kecuali dirinya sendiri ($supplier->id)
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')->ignore($supplier->id),
            ],
            'contact' => 'nullable|string|max:255',
        ]);

        // 2. Update Data
        $supplier->update($request->only(['name', 'contact']));

        // 3. Redirect
        return redirect()->route('suppliers.index')
                         ->with('success', 'Data Pemasok berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (DELETE Logic).
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Hapus data pemasok
            $supplier->delete();
            return redirect()->route('suppliers.index')
                             ->with('success', 'Pemasok berhasil dihapus.');

        } catch (\Illuminate\Database\QueryException $e) {
             // Tangani error jika pemasok masih digunakan (melanggar Foreign Key)
             return redirect()->route('suppliers.index')
                              ->with('error', 'Gagal menghapus pemasok. Pastikan tidak ada Produk yang terkait dengan pemasok ini.');
        }
    }
}
