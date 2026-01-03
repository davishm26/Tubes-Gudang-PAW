# 🎭 Fitur Demo Mode - Sistem Gudang

## Deskripsi
Fitur Demo Mode memungkinkan calon pengguna untuk mencoba aplikasi Sistem Gudang tanpa perlu registrasi atau login. Semua data dalam mode demo hanya disimpan di browser (localStorage) dan tidak akan mempengaruhi database.

## ✨ Fitur Utama

### 1. **Akses Tanpa Login**
- User dapat langsung mencoba aplikasi dari landing page
- Tidak memerlukan email atau password
- Instant access ke dashboard

### 2. **Pilihan Role**
- **Admin**: Akses penuh ke semua fitur termasuk user management
- **Staff**: Akses terbatas ke fitur operasional (produk, stok, dll)

### 3. **UI Only - Data di Browser**
- Semua perubahan hanya tersimpan di localStorage browser
- Tidak ada request ke database/API
- Data tetap ada selama session browser aktif
- Reset otomatis saat keluar demo atau clear browser data

### 4. **Fitur Lengkap**
User dapat mencoba:
- ✅ Dashboard dengan statistik
- ✅ Manajemen Produk (CRUD)
- ✅ Manajemen Kategori (CRUD)
- ✅ Manajemen Supplier (CRUD)
- ✅ Stok Masuk (Create)
- ✅ Stok Keluar (Create)
- ✅ User Management (Admin only)

## 🚀 Cara Menggunakan

### Untuk User

1. **Buka Landing Page**
   ```
   http://your-domain.com/
   ```

2. **Klik Tombol "🚀 Coba Demo"**
   - Tombol terletak di hero section

3. **Pilih Role**
   - **Admin**: Untuk mencoba semua fitur
   - **Staff**: Untuk mencoba fitur operasional

4. **Explore!**
   - Dashboard akan terbuka dengan data dummy
   - Semua form dapat diisi
   - Data tersimpan di localStorage

5. **Keluar Demo**
   - Klik tombol "Keluar Demo" di banner orange di atas
   - Atau kunjungi `/demo/exit`

### Untuk Developer

#### File-file Penting

```
📁 Project Root
├── 📁 app/Http/Middleware/
│   ├── DemoModeMiddleware.php          # Inject demo user
│   ├── DemoOrAuthMiddleware.php        # Allow demo or auth
│   └── AdminMiddleware.php             # Updated untuk demo mode
│
├── 📁 app/Http/Controllers/
│   └── SubscriptionController.php      # startDemo() & exitDemo()
│
├── 📁 public/js/
│   ├── demo-mode.js                    # Form interception & localStorage save
│   └── demo-display.js                 # Data rendering dari localStorage
│
├── 📁 resources/views/
│   ├── subscription/landing.blade.php  # Modal pilihan role
│   └── layouts/app.blade.php           # Demo banner & script loader
│
└── 📁 routes/
    └── web.php                          # Demo routes
```

## 🔧 Implementasi Teknis (Updated v2.0)

### 1. Session Management - Seeding dari Config
```php
// Load semua data dari config/demo_data.php
$demoData = config('demo_data');

// Start demo dengan data lengkap
session([
    'is_demo' => true,
    'demo_mode' => true,
    'demo_role' => 'admin' // or 'staff'
    'demo_user' => [...],
    'demo_categories' => [...],  // 7 kategori
    'demo_suppliers' => [...],   // 6 supplier
    'demo_products' => [...],    // 17 produk
    'demo_inventory_in' => [...],   // 17 data
    'demo_inventory_out' => [...],  // 10 data
    'demo_users' => [...],
    'demo_statistics' => [...]
]);

// Exit demo
session()->forget([
    'is_demo', 'demo_mode', 'demo_role', 'demo_user',
    'demo_categories', 'demo_suppliers', 'demo_products',
    'demo_inventory_in', 'demo_inventory_out', 
    'demo_users', 'demo_statistics'
]);
```

### 2. Data Structure dari config/demo_data.php
```php
// Updated dengan data lengkap
[
    'categories' => [ /* 7 kategori */ ],
    'suppliers' => [ /* 6 supplier dengan address */ ],
    'products' => [ /* 17 produk detail lengkap */ ],
    'inventory_ins' => [ /* 17 stok masuk history */ ],
    'inventory_outs' => [ /* 10 stok keluar history */ ],
    'users' => [ /* admin dan staff user */ ],
    'statistics' => [ /* statistik demo */ ],
    'demo_user' => [
        'admin' => [ /* admin user */ ],
        'staff' => [ /* staff user */ ]
    ]
]
```

### 3. Dual Session Key Support (Backward Compatible)
```
✅ session('is_demo')        // Lama - masih support
✅ session('demo_mode')      // Baru - lebih jelas
✅ session('demo_role')      // Role user (admin/staff)
✅ session('demo_user')      // User data
✅ session('demo_products')  // 17 produk lengkap
✅ session('demo_statistics') // Stats demo
// ... dll
```

### 4. Middleware Chain
```php
Route::middleware([\App\Http\Middleware\DemoOrAuthMiddleware::class, \App\Http\Middleware\DemoModeMiddleware::class])->group(function () {
    // Routes yang bisa diakses via demo atau auth
    // DemoOrAuthMiddleware: Allow if authenticated OR demo_mode
    // DemoModeMiddleware: Inject demo user jika session aktif
});
```

### 5. Routes Demo Mode
```php
// Masuk demo (GET)
Route::get('/demo/{role}', [DemoController::class, 'enter'])->name('demo.enter');
// Contoh: /demo/admin atau /demo/staff

// Keluar demo (GET)
Route::get('/demo-exit', [DemoController::class, 'exit'])->name('demo.exit');

// Info debug (GET)
Route::get('/demo-info', [DemoController::class, 'info'])->name('demo.info');

// Legacy routes untuk backward compatibility
Route::post('/demo/start', [SubscriptionController::class, 'startDemo'])->name('demo.start');
Route::post('/demo/exit', [SubscriptionController::class, 'exitDemo'])->name('demo.exit.old');
```

## 🎨 UI Elements

### Demo Banner
```blade
@if(session('demo_mode') === 'true')
    <div class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400">
        <!-- Banner content -->
        <a href="{{ route('demo.exit') }}">Keluar Demo</a>
    </div>
@endif
```

### Modal Pilihan Role
- Gradient buttons untuk Admin (blue-purple) dan Staff (green-teal)
- Icons yang jelas
- Deskripsi singkat untuk setiap role
- Info box tentang mode demo

## 📊 Data Dummy Default

### Products (5 items)
- Laptop Dell
- Mouse Wireless
- Meja Kantor
- Kursi Ergonomis
- Pulpen Box

### Categories (3 items)
- Elektronik
- Furniture
- Alat Tulis

### Suppliers (3 items)
- PT Elektronik Jaya
- CV Furniture Indo
- Toko ATK Sejahtera

### Inventory Transactions
- 2 Stok Masuk
- 2 Stok Keluar

## 🔒 Security Considerations

### What's Safe ✅
- No database writes
- No file uploads to server
- All data in browser only
- Session-based access control

### What to Watch ⚠️
- Demo user has no real authentication
- Don't use demo mode for sensitive operations
- Always validate real user auth in production endpoints
- Demo mode should be disabled in production if not needed

## 🧪 Testing

### Test Cases

1. **Start Demo as Admin**
   - ✅ Can access user management
   - ✅ Can create products
   - ✅ Can see all menu items

2. **Start Demo as Staff**
   - ✅ Cannot access user management
   - ✅ Can create products
   - ✅ Limited menu access

3. **Form Submissions**
   - ✅ Create product → saved to localStorage
   - ✅ Update product → updated in localStorage
   - ✅ Delete product → removed from localStorage

4. **Data Persistence**
   - ✅ Refresh page → data still there
   - ✅ Navigate between pages → data intact
   - ✅ Exit demo → session cleared

5. **Exit Demo**
   - ✅ Click "Keluar Demo" → redirect to landing
   - ✅ Session cleared
   - ✅ Can start new demo

## 🐛 Troubleshooting

### Issue: Data tidak muncul
**Solution**: 
- Cek console browser untuk errors
- Pastikan `demo_initialized` di localStorage ada
- Coba clear localStorage dan start ulang demo

### Issue: Form tidak tersimpan
**Solution**:
- Pastikan script `demo-mode.js` dan `demo-display.js` loaded
- Cek console untuk JavaScript errors
- Pastikan `demo_mode` session aktif

### Issue: Redirect ke login
**Solution**:
- Pastikan middleware `demo_or_auth` aktif
- Cek session `demo_mode` masih 'true'
- Restart demo dari landing page

## 📝 Future Enhancements

- [ ] Add more realistic dummy data
- [ ] Export/Import demo data
- [ ] Share demo session via URL
- [ ] Demo mode analytics
- [ ] Time-limited demo sessions
- [ ] Demo tutorial/guided tour

## 👥 Credits

Developed for Sistem Gudang - Warehouse Management System
Demo Mode Feature - December 2025

---

**Note**: Demo mode is designed for showcase and trial purposes. For production use, please register and use the full authenticated system.
