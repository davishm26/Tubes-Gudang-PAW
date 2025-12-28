# 🚀 Quick Start - Mode Demo Statis

## Akses Cepat

### 1. Mode Demo Admin (Akses Penuh)
```
http://localhost:8000/demo/admin
```
- ✅ CRUD Produk, Supplier, Kategori
- ✅ Manajemen User
- ✅ Dashboard & Laporan

### 2. Mode Demo Staff (Akses Terbatas)
```
http://localhost:8000/demo/staff
```
- ✅ Lihat Produk
- ✅ Catat Stok Masuk/Keluar
- ❌ Tidak bisa hapus data
- ❌ Tidak bisa akses Manajemen User

### 3. Halaman Demo Landing
```
http://localhost:8000/demo-landing
```

---

## Contoh Code Controller (Copy-Paste Ready)

### ProductController.php
```php
public function index()
{
    // DEMO MODE
    if (session('is_demo')) {
        $products = collect(config('demo_data.products'))
            ->map(fn($p) => (object)$p);
        return view('products.index', compact('products'));
    }
    
    // REAL MODE
    $products = Product::all();
    return view('products.index', compact('products'));
}

public function store(Request $request)
{
    if (session('is_demo')) {
        $request->validate([/* ... */]);
        return back()->with('success', 'Berhasil! (Simulasi)');
    }
    
    Product::create($request->all());
    return back()->with('success', 'Berhasil disimpan!');
}

public function destroy($id)
{
    if (session('is_demo')) {
        return back()->with('success', 'Dihapus! (Simulasi)');
    }
    
    Product::destroy($id);
    return back()->with('success', 'Berhasil dihapus!');
}
```

### Blade View (products/index.blade.php)
```blade
@php
    $isDemo = session('is_demo', false);
    $demoRole = session('demo_role', null);
    $isAdmin = $isDemo ? ($demoRole === 'admin') : (Auth::user()->role === 'admin');
@endphp

{{-- Tombol Tambah - Hanya Admin --}}
@if($isAdmin)
    <a href="{{ route('products.create') }}">+ Tambah Produk</a>
@endif

{{-- Tombol Hapus - Admin saja, Staff di demo tidak bisa --}}
@if($isAdmin && (!$isDemo || $demoRole !== 'staff'))
    <button>Hapus</button>
@endif
```

---

## Testing Checklist

- [ ] Akses `/demo/admin` → Berhasil masuk, lihat badge "MODE DEMO (ADMIN)"
- [ ] Tambah produk → Muncul message "(Simulasi)"
- [ ] Hapus produk → Data tidak hilang setelah refresh
- [ ] Akses `/demo/staff` → Menu "Manajemen User" tersembunyi
- [ ] Staff tidak bisa hapus data
- [ ] Klik "Keluar Demo" → Redirect ke landing page
- [ ] Session cleared setelah keluar demo

---

## File Penting

1. `config/demo_data.php` → Data dummy
2. `app/Http/Controllers/DemoController.php` → Entry point
3. `routes/web.php` → Route demo
4. `resources/views/demo/landing.blade.php` → Landing page
5. `resources/views/layouts/navigation.blade.php` → Badge & menu

**Dokumentasi Lengkap:** Lihat `DEMO_MODE_STATIC.md`
