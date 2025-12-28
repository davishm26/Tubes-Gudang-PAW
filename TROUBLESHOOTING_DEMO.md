# 🔧 Troubleshooting Mode Demo

## Masalah yang Sudah Diperbaiki:

### ✅ Session Key Inconsistency
- **Problem:** DemoController menggunakan `is_demo`, middleware menggunakan `demo_mode`
- **Solusi:** Semua file sekarang support kedua key dengan `session('is_demo') || session('demo_mode')`

### ✅ Middleware yang Sudah Diupdate:
1. ✅ `DemoOrAuthMiddleware` - Support is_demo dan demo_mode
2. ✅ `DemoModeMiddleware` - Support is_demo dan demo_mode  
3. ✅ `NotSuperAdminMiddleware` - Bypass untuk demo mode
4. ✅ `AdminMiddleware` - Support demo mode dengan role admin
5. ✅ `StaffMiddleware` - Bypass untuk demo mode

### ✅ Controller yang Sudah Diupdate:
1. ✅ `DashboardController` - Menggunakan data dari config/demo_data.php
2. ✅ `ProductController` - Semua method (index, create, store, edit, update, destroy) support demo mode

---

## 🧪 Testing Steps:

1. **Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

2. **Restart Server:**
```bash
# Stop dengan Ctrl+C, lalu:
php artisan serve
```

3. **Test Akses:**
```
http://localhost:8000/demo/admin   ✅
http://localhost:8000/demo/staff   ✅
```

4. **Verifikasi:**
- ✅ Badge "MODE DEMO (ADMIN)" muncul di navigation
- ✅ Data produk muncul (dari config/demo_data.php)
- ✅ Dashboard menampilkan statistik
- ✅ Tambah produk → Flash: "(Simulasi - Data tidak tersimpan)"
- ✅ Edit produk → Flash: "(Simulasi - Data tidak tersimpan)"
- ✅ Hapus produk → Flash: "(Simulasi - Data tidak tersimpan)"
- ✅ Data tidak berubah setelah refresh

---

## 🐛 Jika Masih Error:

### Error: "Undefined variable $currentUser"
**Lokasi:** navigation.blade.php  
**Solusi:** Sudah diperbaiki - menggunakan session demo_user

### Error: "Call to a member function on null"
**Lokasi:** Dashboard atau Controller lain  
**Solusi:** Pastikan Auth::user() di-wrap dengan pengecekan:
```php
$isDemoMode = session('is_demo') || session('demo_mode');
if ($isDemoMode) {
    // Use demo data
} else {
    $user = Auth::user();
}
```

### Error: Route not found
**Cek:** routes/web.php sudah ada route demo
```php
Route::get('/demo/{role}', [DemoController::class, 'enter']);
```

### Error: Session not persisting
**Cek:** .env file
```env
SESSION_DRIVER=file
```

---

## 📝 Checklist File yang Harus Ada:

- [x] config/demo_data.php
- [x] app/Http/Controllers/DemoController.php
- [x] app/Http/Middleware/DemoOrAuthMiddleware.php
- [x] app/Http/Middleware/DemoModeMiddleware.php
- [x] resources/views/subscription/landing.blade.php (modal demo)
- [x] routes/web.php (route /demo/{role})

---

## 🎯 URL yang Harus Berfungsi:

```
http://localhost:8000/demo/admin          → Masuk sebagai Admin
http://localhost:8000/demo/staff          → Masuk sebagai Staff
http://localhost:8000/demo-exit           → Keluar dari mode demo
http://localhost:8000/                    → Landing page (klik "Coba Demo")
http://localhost:8000/dashboard           → Dashboard (setelah masuk demo)
http://localhost:8000/products            → Daftar produk (data dummy)
```

---

## 💡 Tips:

1. Gunakan **Incognito/Private Window** untuk test fresh session
2. Clear browser cache jika data lama masih muncul
3. Check Laravel log: `storage/logs/laravel.log`
4. Enable debug mode di .env: `APP_DEBUG=true`

---

**Last Updated:** 28 Des 2025
