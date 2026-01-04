# StockMaster Brand Color Migration

## Overview
Skema warna aplikasi web StockMaster telah diperbarui untuk selaras dengan identitas brand yang baru. Semua perubahan hanya pada CSS/class warna tanpa mengubah struktur HTML atau logic aplikasi.

## Palette Warna Brand StockMaster

| Kategori | Warna | Hex Code | Penggunaan |
|----------|-------|----------|-----------|
| **Primary Green** | Hijau Tua | `#1F8F6A` | Navbar, button primary, links aktif, badges |
| **Primary Dark** | Hijau Gelap | `#166B50` | Hover states, active states |
| **Primary Light** | Hijau Muda | `#E9F6F1` | Background cards, alerts, highlights |
| **Text Primary** | Abu-abu Gelap | `#1F2937` | Teks utama, headings |
| **Border** | Abu-abu Ringan | `#E5E7EB` | Borders, separators |
| **Info Blue** | Biru | `#3B82F6` | Info alerts, informational elements |
| **Success Green** | Hijau Ceria | `#22C55E` | Success messages, checkmarks |
| **Danger Red** | Merah | `#EF4444` | Error alerts, destructive actions |
| **Warning Orange** | Orange | `#F97316` | Warning alerts, caution elements |

## Mapping Perubahan Warna

### Button & Background Colors
- `bg-emerald-600` → `bg-[#1F8F6A]` (Primary Green)
- `bg-emerald-700` → `bg-[#166B50]` (Primary Dark)
- `hover:bg-emerald-700` → `hover:bg-[#166B50]` (Primary Dark)
- `bg-emerald-50` → `bg-[#E9F6F1]` (Primary Light)
- `bg-emerald-100` → `bg-[#F0FAF7]` (Light variant)
- `bg-emerald-500` → `bg-[#1F8F6A]` (Primary Green)

### Text Colors
- `text-emerald-600` → `text-[#1F8F6A]` (Primary Green)
- `text-emerald-700` → `text-[#166B50]` (Primary Dark)
- `text-emerald-800` → `text-[#1F2937]` (Text Primary)
- `text-emerald-400` → `text-[#1F8F6A]` (Primary Green)
- `text-emerald-100` → `text-[#E9F6F1]` (Primary Light)

### Border Colors
- `border-emerald-200` → `border-[#E5E7EB]` (Border)
- `border-emerald-100` → `border-[#E5E7EB]` (Border)
- `border-emerald-300` → `border-[#C8E6DF]` (Light border)

### Focus & Ring Colors
- `focus:ring-emerald-500` → `focus:ring-[#1F8F6A]` (Primary Green)
- `focus:border-emerald-500` → `focus:border-[#1F8F6A]` (Primary Green)

### Gradient Colors
- `from-emerald-600 to-emerald-700` → `from-[#1F8F6A] to-[#166B50]`
- `from-emerald-50 to-emerald-100` → `from-[#E9F6F1] to-[#D1EDE5]`
- `from-emerald-50 via-emerald-50 to-white` → `from-[#E9F6F1] via-[#E9F6F1] to-white`

### Hover & Active States
- `hover:from-emerald-700 hover:to-emerald-800` → `hover:from-[#166B50] hover:to-[#0F4C37]`
- `hover:text-emerald-600` → `hover:text-[#1F8F6A]`
- `hover:text-emerald-900` → `hover:text-[#0F4C37]`
- `hover:bg-emerald-50/30` → `hover:bg-[#E9F6F1]/30`

## Files yang Diubah

### CSS Files
- ✅ `resources/css/app.css` - Updated dengan CSS variables brand baru

### Blade Template Files (63 files total)
- ✅ `resources/views/welcome.blade.php`
- ✅ `resources/views/layouts/app.blade.php`
- ✅ `resources/views/layouts/guest.blade.php`
- ✅ `resources/views/layouts/navigation.blade.php`
- ✅ `resources/views/categories/**/*.blade.php`
- ✅ `resources/views/suppliers/**/*.blade.php`
- ✅ `resources/views/users/**/*.blade.php`
- ✅ `resources/views/products/**/*.blade.php`
- ✅ `resources/views/subscription/**/*.blade.php`
- ✅ `resources/views/super_admin/**/*.blade.php`
- ✅ Dan semua file blade.php lainnya

## CSS Component Classes

### Available Custom Classes
```css
.btn-primary {
    /* Primary button dengan warna brand hijau #1F8F6A */
}

.btn-ghost {
    /* Ghost button dengan border dan text warna brand */
}

.btn-danger {
    /* Danger button untuk aksi destructive */
}

.badge-soft {
    /* Badge dengan background light dan text brand green */
}

.card {
    /* Card component dengan styling standard */
}

.card-muted {
    /* Muted card dengan background light */
}
```

## Checklist Verifikasi

- ✅ Navbar dan navigation menggunakan Primary Green (#1F8F6A)
- ✅ Primary buttons menggunakan warna brand yang konsisten
- ✅ Tab aktif menunjukkan Primary Green
- ✅ Cards tetap putih dengan accent warna brand
- ✅ Borders menggunakan #E5E7EB yang konsisten
- ✅ Focus states menggunakan Primary Green
- ✅ Alert semantic colors (success, danger, warning) diterapkan
- ✅ Kontras teks tetap nyaman dibaca
- ✅ Hover states menunjukkan Primary Dark (#166B50)
- ✅ Tidak ada perubahan struktur HTML
- ✅ Tidak ada perubahan layout atau grid
- ✅ Tidak ada perubahan logic backend/frontend

## Browser Compatibility

Perubahan warna menggunakan:
- ✅ Tailwind CSS custom arbitrary values `[#hexcode]`
- ✅ CSS Variables di `:root`
- ✅ Compatible dengan semua modern browsers

## Testing Checklist

Sebelum deploy ke production, pastikan:
- [ ] Test tampilan di berbagai browser (Chrome, Firefox, Safari, Edge)
- [ ] Test responsive design (mobile, tablet, desktop)
- [ ] Verify kontras warna untuk accessibility
- [ ] Test semua interactive elements (buttons, links, forms)
- [ ] Check dark mode (jika ada)
- [ ] Verify print styles (jika ada)

## Rollback Instructions

Jika diperlukan rollback, gunakan git untuk revert ke commit sebelumnya:
```bash
git revert <commit-hash>
```

atau restore dari backup CSS:
```bash
git checkout HEAD~1 -- resources/css/app.css
git checkout HEAD~1 -- resources/views
```

---

**Migration Date:** January 4, 2026  
**Migration Type:** Color Scheme Update  
**Status:** ✅ Completed  
**Author:** GitHub Copilot
