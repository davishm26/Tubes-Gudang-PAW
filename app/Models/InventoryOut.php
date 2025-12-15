<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // WAJIB
use Illuminate\Database\Eloquent\Model;

class InventoryOut extends Model
{
    use HasFactory;

    // --- WAJIB: Izinkan Mass Assignment untuk kolom-kolom ini ---
    protected $fillable = [
        'product_id',
        'quantity',
        'date',
        'description', // <--- TAMBAHKAN
        'user_id'
    ];
    // -----------------------------------------------------------

    /**
     * Relasi ke Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke User (siapa yang mencatat)
     */
    public function user()
    {
        // Asumsi user_id merujuk ke App\Models\User
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
