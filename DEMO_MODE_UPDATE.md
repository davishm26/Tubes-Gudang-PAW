# 🎭 Update Demo Mode - Dokumentasi

**Status**: ✅ **Completed** - Demo mode telah diupdate sesuai dengan mode real

**Tanggal Update**: 3 Januari 2026

---

## 📋 Ringkasan Perubahan

### 1. **Data Dummy yang Diperluas**
Demo mode sekarang dilengkapi dengan data dummy yang lengkap dan realistis:

- **17 Produk** (naik dari 2)
  - 3 Monitor
  - 3 Laptop  
  - 3 Keyboard
  - 3 Mouse
  - 3 Headset
  - 2 Printer

- **6 Supplier** (naik dari 2)
- **7 Kategori** (naik dari 2)
- **17 Data Stok Masuk** (inventory_in)
- **10 Data Stok Keluar** (inventory_out)

### 2. **Struktur Data yang Konsisten dengan Mode Real**
Semua data dummy sekarang mengikuti struktur yang sama dengan mode real:
- Lengkap dengan timestamps
- Deskripsi yang detail dan realistis
- Harga dan stok yang variatif
- Relasi antar data yang valid

### 3. **Perubahan File Utama**

#### `config/demo_data.php`
✅ Diupdate dengan:
- Data lengkap untuk 7 kategori
- 6 supplier dengan detail alamat
- 17 produk dengan spesifikasi detail
- 17 inventory in records
- 10 inventory out records
- User data untuk admin dan staff
- Statistik demo

#### `app/Http/Controllers/DemoController.php`
✅ Diupdate untuk:
- Load semua data dari config
- Seed semua data ke session
- Support kedua session keys (`is_demo` dan `demo_mode`)
- Flash message dengan info ringkas data yang dimuat
- Endpoint `/demo/info` untuk debugging

#### `app/Http/Controllers/SubscriptionController.php`
✅ Diupdate untuk:
- Konsisten menggunakan config/demo_data.php
- Support kedua session keys
- Data lengkap saat start demo

---

## 🎯 Fitur-Fitur yang Dapat Dicoba di Demo Mode

### 1. **Dashboard** ✅
- Statistik inventory
- Total produk: 17
- Total supplier: 6
- Grafik dan laporan

### 2. **Manajemen Produk** ✅
- View 17 produk dengan kategori dan supplier
- Bisa menambah produk baru (disimpan di session)
- Edit dan delete produk (disimpan di session)
- Search dan filter produk

### 3. **Manajemen Kategori** ✅
- View 7 kategori
- CRUD operations (disimpan di session)
- Relasi dengan produk

### 4. **Manajemen Supplier** ✅
- View 6 supplier
- CRUD operations (disimpan di session)
- Detail lengkap per supplier

### 5. **Stok Masuk (Inventory In)** ✅
- View 17 history stok masuk
- Buat stok masuk baru (disimpan di session)
- Lihat detail per transaksi
- Filter berdasarkan tanggal dan produk

### 6. **Stok Keluar (Inventory Out)** ✅
- View 10 history stok keluar
- Buat stok keluar baru (disimpan di session)
- Laporan penjualan/pengiriman
- Filter dan search

### 7. **User Management** (Admin only) ✅
- Lihat user yang ada
- Menambah user baru
- Assigned roles (admin, staff)

---

## 🔄 Session Keys yang Digunakan

Untuk kompatibilitas, demo mode support dua session keys:

```php
session([
    // Session keys lama (backward compatible)
    'is_demo' => true,
    'demo_role' => 'admin|staff',
    'demo_user' => [...],
    
    // Session keys baru (lebih deskriptif)
    'demo_mode' => true,
    'demo_categories' => [...],
    'demo_suppliers' => [...],
    'demo_products' => [...],
    'demo_inventory_in' => [...],
    'demo_inventory_out' => [...],
    'demo_users' => [...],
    'demo_statistics' => [...],
])
```

---

## 🚀 Cara Menggunakan Demo Mode

### Untuk User Akhir

1. **Buka Landing Page**
   ```
   http://your-domain.com/
   ```

2. **Klik Tombol "🚀 Coba Demo"**
   - Pilih Role: Admin atau Staff
   - Demo mode akan aktif otomatis

3. **Explore Fitur**
   - Dashboard melihat statistik
   - Manajemen produk, kategori, supplier
   - Stok masuk dan keluar
   - User management (admin only)

4. **Keluar Demo**
   - Klik "Keluar Demo" atau buka `/demo-exit`
   - Semua session akan dibersihkan
   - Kembali ke landing page

### Untuk Developer

#### Masuk ke Demo Mode
```php
// Route: GET /demo/{role}
// Role: 'admin' atau 'staff'
Route::get('/demo/{role}', [DemoController::class, 'enter'])->name('demo.enter');
```

#### Keluar Demo Mode
```php
// Route: GET /demo-exit
Route::get('/demo-exit', [DemoController::class, 'exit'])->name('demo.exit');
```

#### Info Debug
```bash
# Check current demo status
curl http://your-domain.com/demo-info
```

Response:
```json
{
  "is_demo": true,
  "demo_role": "admin",
  "demo_user": {
    "id": 999,
    "name": "Demo Admin",
    "email": "admin@demo.com",
    "role": "admin"
  },
  "demo_data_loaded": {
    "categories": 7,
    "suppliers": 6,
    "products": 17,
    "inventory_in": 17,
    "inventory_out": 10
  }
}
```

---

## 🔐 Middleware & Proteksi

Demo mode diproteksi dengan middleware:

### `DemoOrAuthMiddleware`
- Mengizinkan akses jika user authenticated OR dalam demo mode
- Redirect ke login jika tidak keduanya

### `DemoModeMiddleware`
- Inject demo user ke view jika session demo aktif
- Share variable `isDemoMode` dan `demoUser` ke blade

---

## 📊 Data Statistics di Demo Mode

```php
'statistics' => [
    'total_products' => 17,
    'total_suppliers' => 6,
    'total_categories' => 7,
    'total_inventory_in' => 17,
    'total_inventory_out' => 10,
    'low_stock_items' => [
        'Monitor Ultra Wide 34" 3440x1440' => 8,
        'Headset Wireless Premium ANC' => 12,
        'Headset USB-C Studio' => 9,
    ],
    'total_value' => 250000000, // Estimated
]
```

---

## ⚠️ Catatan Penting

1. **Data Session, Bukan Database**
   - Semua data demo hanya tersimpan di session browser
   - Tidak menyentuh database real
   - Hilang otomatis saat logout atau browser ditutup

2. **Perubahan Data Hanya di Session**
   - Ketika user menambah/edit/delete data di demo mode
   - Hanya tersimpan di session, tidak ke database
   - Saat refresh halaman, data awal akan kembali

3. **Session Cleanup**
   - Saat exit demo, semua session keys akan dihapus
   - Data bersih untuk demo session berikutnya

4. **Dukungan Role**
   - Admin: Akses penuh ke semua fitur
   - Staff: Akses terbatas (lihat implementasi di middleware/controller)

---

## 📁 File-File yang Diupdate

```
✅ config/demo_data.php
✅ app/Http/Controllers/DemoController.php
✅ app/Http/Controllers/SubscriptionController.php
✅ app/Http/Middleware/DemoModeMiddleware.php (no changes, already correct)
✅ app/Http/Middleware/DemoOrAuthMiddleware.php (no changes, already correct)
```

---

## 🔗 Routes Demo Mode

```php
// New demo routes (from DemoController)
Route::get('/demo/{role}', [DemoController::class, 'enter'])->name('demo.enter');
Route::get('/demo-exit', [DemoController::class, 'exit'])->name('demo.exit');
Route::get('/demo-info', [DemoController::class, 'info'])->name('demo.info');

// Old demo routes (from SubscriptionController - untuk backward compatibility)
Route::post('/demo/start', [SubscriptionController::class, 'startDemo'])->name('demo.start');
Route::post('/demo/exit', [SubscriptionController::class, 'exitDemo'])->name('demo.exit.old');
```

---

## ✅ Checklist Update

- [x] Expand data dummy di config/demo_data.php
- [x] Update DemoController untuk seed semua data
- [x] Update SubscriptionController untuk konsistensi
- [x] Support dual session keys (is_demo & demo_mode)
- [x] Add statistics ke demo data
- [x] Add description detail untuk setiap produk
- [x] Add timestamps dan relations yang realistic
- [x] Test middleware compatibility
- [x] Create documentation

---

## 🎓 Best Practices

### Untuk Testing Demo Mode
```bash
# Test admin demo
curl http://localhost:8000/demo/admin

# Test staff demo
curl http://localhost:8000/demo/staff

# Check status
curl http://localhost:8000/demo-info

# Exit demo
curl http://localhost:8000/demo-exit
```

### Untuk Development
Lihat `demo_data.php` untuk struktur data yang tepat saat membuat fitur baru.

---

## 📞 Support

Jika ada issue atau pertanyaan:
1. Cek `/demo-info` untuk debugging
2. Lihat session keys yang tersimpan
3. Check middleware logs
4. Verify config/demo_data.php structure

---

**Last Updated**: 3 Januari 2026
**Version**: 2.0 (Updated)
