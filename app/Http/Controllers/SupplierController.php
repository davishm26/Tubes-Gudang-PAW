<?php

namespace App\Http\Controllers;

use App\Models\Supplier; // <-- WAJIB: Import Model Supplier
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- WAJIB: Import Rule untuk validasi unique saat update
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     */
    public function index(Request $request)
    {
        $isDemo = session('is_demo') || session('demo_mode');

        if ($isDemo) {
            $suppliers = collect(config('demo_data.suppliers', []))->map(fn($s) => (object) $s);

            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $suppliers = $suppliers->filter(function ($s) use ($search) {
                    return str_contains(strtolower($s->name), $search)
                        || str_contains(strtolower($s->contact ?? ''), $search);
                })->values();
            }

            return view('suppliers.index', ['suppliers' => $suppliers]);
        }
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
        $isDemo = session('is_demo') || session('demo_mode');
        if ($isDemo) {
            return view('suppliers.create');
        }
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
        $isDemo = session('is_demo') || session('demo_mode');
        if ($isDemo) {
            $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'nullable|string|max:255',
            ]);
            return redirect()->route('suppliers.index')->with('success', 'Pemasok demo ditambahkan! (Simulasi - tidak tersimpan)');
        }
        $companyId = Auth::user()?->company_id ?? $request->get('company_id');
        // 1. Validasi Data
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name')->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'contact' => 'nullable|string|max:255',
        ]);
        Supplier::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'company_id' => $companyId,
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Pemasok baru berhasil ditambahkan.');
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
    public function edit($id)
    {
        $isDemo = session('is_demo') || session('demo_mode');
        if ($isDemo) {
            $demo = collect(config('demo_data.suppliers', []))->firstWhere('id', (int)$id);
            if (!$demo) {
                return redirect()->route('suppliers.index')->with('error', 'Pemasok demo tidak ditemukan.');
            }
            $supplier = (object)$demo;
            return view('suppliers.edit', compact('supplier'));
        }
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage (UPDATE Logic).
     */
    public function update(Request $request, $id)
    {
        $isDemo = session('is_demo') || session('demo_mode');
        if ($isDemo) {
            return redirect()->route('suppliers.index')->with('success', 'Pemasok demo diperbarui! (Simulasi - tidak tersimpan)');
        }
        $companyId = Auth::user()?->company_id ?? $request->get('company_id');
        // 1. Validasi Data
        $request->validate([
            // Nama harus unik, kecuali dirinya sendiri ($supplier->id)
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'contact' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);

        // 2. Update Data
        $supplier->update($request->only(['name', 'contact']));

        // 3. Redirect
        return redirect()->route('suppliers.index')
                         ->with('success', 'Data Pemasok berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (DELETE Logic).
     */
    public function destroy($id)
    {
        $isDemo = session('is_demo') || session('demo_mode');
        if ($isDemo) {
            return redirect()->route('suppliers.index')->with('success', 'Pemasok demo dihapus! (Simulasi - tidak tersimpan)');
        }
        $supplier = Supplier::findOrFail($id);
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
