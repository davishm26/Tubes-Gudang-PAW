# 📚 Dokumentasi Mode Demo Statis - Laravel Sistem Gudang

## 🎯 Deskripsi Fitur

Fitur **Mode Demo Statis** memungkinkan calon pengguna untuk mencoba aplikasi Sistem Gudang sebagai **Admin** atau **Staff** tanpa perlu registrasi atau login database. Semua data yang ditampilkan adalah data dummy statis dari file konfigurasi, dan semua perubahan **tidak akan tersimpan** ke database.

---

## 🚀 Cara Menggunakan

### 1. Akses Mode Demo

Ada dua cara untuk masuk ke mode demo:

#### Cara 1: Melalui URL Langsung
```
http://localhost:8000/demo/admin   → Masuk sebagai Admin
http://localhost:8000/demo/staff   → Masuk sebagai Staff
```

#### Cara 2: Melalui Landing Page Utama
- Buka `http://localhost:8000/`
- Klik tombol **"Coba Demo"** di hero section
- Pilih role (Admin atau Staff) di modal yang muncul

### 2. Keluar dari Mode Demo

Untuk keluar dari mode demo, klik tombol **"Keluar dari Mode Demo"** di dropdown user (kanan atas), atau akses:
```
http://localhost:8000/demo-exit
```

---

## 📂 File yang Dibuat/Dimodifikasi

### ✅ File Baru

1. **`config/demo_data.php`**
   - Berisi data dummy untuk produk, kategori, supplier, transaksi masuk/keluar
   - Data profesional dan realistis

2. **`app/Http/Controllers/DemoController.php`**
   - Menangani entry point mode demo (`/demo/{role}`)
   - Mengelola session mode demo
   - Method: `enter()`, `exit()`, `info()`

3. **`resources/views/subscription/landing.blade.php`** (Modified)
   - Menambahkan modal "Pilih Role Demo" di landing page utama
   - Tombol "Coba Demo" di hero section
   - Fungsi JavaScript untuk redirect ke `/demo/{role}`

### 🔧 File yang Dimodifikasi

1. **`routes/web.php`**
   ```php
   // Tambahkan route untuk mode demo
   Route::get('/demo/{role}', [DemoController::class, 'enter'])->name('demo.enter');
   Route::get('/demo-exit', [DemoController::class, 'exit'])->name('demo.exit');
   ```

2. **`resources/views/layouts/navigation.blade.php`**
   - Menambahkan logik untuk mengecek session `is_demo` dan `demo_role`
   - Menampilkan badge "MODE DEMO" saat aktif
   - Menyembunyikan menu tertentu untuk role staff
   - Menampilkan tombol "Keluar dari Mode Demo"

3. **`resources/views/products/index.blade.php`**
   - Menambahkan logika untuk menyembunyikan tombol "Hapus" untuk staff di mode demo
   - Kompatibel dengan mode demo dan mode normal

---

## 🎨 Cara Kerja Implementasi

### A. Masuk Mode Demo

Ketika user mengakses `/demo/admin` atau `/demo/staff`:

```php
// DemoController.php
public function enter($role)
{
    // 1. Validasi role
    if (!in_array($role, ['admin', 'staff'])) {
        return redirect('/')->with('error', 'Role tidak valid');
    }

    // 2. Set session
    Session::put('is_demo', true);
    Session::put('demo_role', $role);
    Session::put('demo_user', config("demo_data.user.{$role}"));

    // 3. Redirect ke dashboard
    return redirect()->route('dashboard');
}
```

### B. Controller Logic

Contoh di `ProductController`:

```php
public function index(Request $request)
{
    // Cek mode demo
    $isDemoMode = session('is_demo') || session('demo_mode');
    
    if ($isDemoMode) {
        // Ambil data dummy dari config
        $products = collect(config('demo_data.products'));
        
        // Filter pencarian jika ada
        if ($request->has('search')) {
            $products = $products->filter(/* ... */);
        }
        
        // Konversi ke object untuk kompatibilitas blade
        $products = $products->map(function ($item) {
            $product = (object) $item;
            $product->category = (object) ['name' => $item['category_name']];
            $product->supplier = (object) ['name' => $item['supplier_name']];
            return $product;
        });
        
        return view('products.index', compact('products'));
    }
    
    // Mode normal: query database
    $products = Product::with(['category', 'supplier'])->get();
    return view('products.index', compact('products'));
}
```

### C. Operasi Create/Update/Delete

```php
public function store(Request $request)
{
    // Mode Demo: Tidak simpan ke database
    if (Session::get('is_demo')) {
        // Validasi tetap dilakukan
        $request->validate([/* ... */]);
        
        // Return dengan flash message simulasi
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan! (Simulasi - Data tidak tersimpan)');
    }
    
    // Mode normal: simpan ke database
    Product::create($request->all());
    return redirect()->route('products.index')
        ->with('success', 'Produk berhasil ditambahkan.');
}
```

### D. Blade View Logic

**Navigation:**
```blade
@php
    $isDemo = session('is_demo', false);
    $demoRole = session('demo_role', null);
    
    if ($isDemo) {
        $currentUser = (object) session('demo_user');
    } else {
        $currentUser = Auth::user();
    }
@endphp

{{-- Badge mode demo --}}
@if($isDemo)
    <span class="badge">🎭 MODE DEMO ({{ strtoupper($demoRole) }})</span>
@endif

{{-- Sembunyikan menu untuk staff --}}
@if(!$isDemo || $demoRole !== 'staff')
    <x-nav-link :href="route('users.index')">Manajemen User</x-nav-link>
@endif
```

**Product Index:**
```blade
@php
    $isDemo = session('is_demo', false);
    $demoRole = session('demo_role', null);
    $isAdmin = $isDemo ? ($demoRole === 'admin') : (Auth::user()->role === 'admin');
@endphp

{{-- Tombol hapus - sembunyikan untuk staff --}}
@if($isAdmin && (!$isDemo || $demoRole !== 'staff'))
    <button type="submit">Hapus</button>
@endif
```

---

## 🔐 Keamanan dan Batasan

### ✅ Fitur Keamanan
1. **Tidak menyimpan ke database** - Semua operasi hanya simulasi
2. **Session-based** - Data hanya tersimpan di session browser
3. **Validasi role** - Hanya menerima role `admin` atau `staff`

### ⚠️ Perbedaan Mode Demo vs Mode Normal

| Aspek | Mode Demo | Mode Normal |
|-------|-----------|-------------|
| Data Source | `config/demo_data.php` | Database (MySQL/PostgreSQL) |
| Create/Update/Delete | **Simulasi saja** | **Tersimpan** ke database |
| Authentication | Bypass (menggunakan session) | Login dengan email & password |
| User Management | Tidak bisa akses jika staff | Akses penuh untuk admin |
| Persistence | Hilang saat session clear | Permanen di database |

---

## 📊 Perbedaan Hak Akses

### 👨‍💼 Demo Admin
- ✅ Lihat semua data produk, supplier, kategori
- ✅ Tambah produk baru (simulasi)
- ✅ Edit produk (simulasi)
- ✅ Hapus produk (simulasi)
- ✅ Akses menu "Manajemen User"
- ✅ Lihat dashboard & laporan

### 👨‍💻 Demo Staff
- ✅ Lihat daftar produk
- ✅ Catat stok masuk & keluar (simulasi)
- ✅ Lihat dashboard
- ❌ **Tidak bisa** hapus data
- ❌ **Tidak bisa** akses "Manajemen User"
- ❌ **Tidak bisa** ubah pengaturan sistem

---

## 🛠️ Implementasi untuk Controller Lain

Jika ingin menerapkan mode demo ke controller lain (misal: `SupplierController`, `CategoryController`), ikuti pola ini:

```php
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    public function index()
    {
        if (Session::get('is_demo')) {
            $suppliers = collect(config('demo_data.suppliers'))
                ->map(fn($s) => (object)$s);
            return view('suppliers.index', compact('suppliers'));
        }
        
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }
    
    public function store(Request $request)
    {
        if (Session::get('is_demo')) {
            $request->validate([/* ... */]);
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan! (Simulasi)');
        }
        
        Supplier::create($request->all());
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }
    
    public function destroy($id)
    {
        if (Session::get('is_demo')) {
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier berhasil dihapus! (Simulasi)');
        }
        
        Supplier::findOrFail($id)->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
```

---

## 🎨 Kustomisasi Data Dummy

Edit file `config/demo_data.php` untuk mengubah data demo:

```php
return [
    'products' => [
        [
            'id' => 1,
            'name' => 'Produk Custom Anda',
            'code' => 'PRD-001',
            'price' => 100000,
            'stock' => 50,
            // ... dst
        ],
    ],
    'suppliers' => [/* ... */],
    'categories' => [/* ... */],
];
```

---

## 🐛 Troubleshooting

### Problem 1: Session tidak tersimpan
**Solusi:** Pastikan session driver di `.env` sudah di-set:
```env
SESSION_DRIVER=file
```

### Problem 2: Data dummy tidak muncul
**Solusi:** Clear cache config:
```bash
php artisan config:clear
```

### Problem 3: Redirect loop
**Solusi:** Pastikan middleware `DemoOrAuthMiddleware` sudah terdaftar di route group yang benar.

---

## 📝 Testing Mode Demo

### Manual Testing
1. Akses `http://localhost:8000/demo/admin`
2. Verifikasi badge "MODE DEMO" muncul
3. Coba tambah produk → Cek flash message "(Simulasi)"
4. Coba hapus produk → Cek data tidak hilang setelah refresh
5. Logout demo → Cek redirect ke landing page

### Demo Flow
```
User → /demo/admin 
     → Session dibuat (is_demo=true, role=admin)
     → Redirect ke /dashboard
     → Lihat data dari config/demo_data.php
     → Operasi CRUD → Flash message "Simulasi"
     → Klik "Keluar Demo"
     → Session cleared
     → Redirect ke /
```

---

## 🎉 Keuntungan Fitur Ini

1. **Tanpa Database** - Calon user bisa coba tanpa setup database
2. **Zero Risk** - Tidak ada data asli yang terpengaruh
3. **Role-Based** - User bisa merasakan perbedaan hak akses
4. **Professional** - Data dummy terlihat realistis
5. **Fast** - Tidak perlu registrasi atau verifikasi email

---

## 📞 Support

Jika ada pertanyaan atau bug, silakan hubungi tim developer atau buat issue di repository.

**Happy Coding! 🚀**
