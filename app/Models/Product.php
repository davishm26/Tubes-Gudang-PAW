<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan HasFactory
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'stock',
        'category_id',
        'supplier_id',
        'image', // KOREKSI: Menggunakan 'image' agar sesuai dengan kolom di DB
    ];

    /**
     * Relasi: Satu Produk dimiliki oleh SATU Kategori (BelongsTo).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Satu Produk dimiliki oleh SATU Pemasok (BelongsTo).
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi: Satu Produk memiliki BANYAK Transaksi Stok Masuk (HasMany).
     */
    public function inventoryIns(): HasMany
    {
        return $this->hasMany(InventoryIn::class);
    }

    /**
     * Relasi: Satu Produk memiliki BANYAK Transaksi Stok Keluar (HasMany).
     */
    public function inventoryOuts(): HasMany
    {
        return $this->hasMany(InventoryOut::class);
    }

    /**
     * Accessor: `image_path` untuk kompatibilitas view.
     * Mengembalikan URL publik jika `image` ada, atau null jika tidak.
     */
    public function getImagePathAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        return Storage::url($this->image);
    }
}
