<?php
// App\Models\InventoryIn.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryIn extends Model
{
    use HasFactory;

    // --- WAJIB: Izinkan Mass Assignment untuk kolom-kolom ini ---
    // ...
    protected $fillable = [
        'product_id',
        'supplier_id', // <--- TAMBAHKAN
        'quantity',
        'date',
        'description', // <--- TAMBAHKAN
        'user_id'
    ];
    // ...
    // -----------------------------------------------------------

    // Definisikan relasi
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        // Asumsi user_id merujuk ke App\Models\User
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    // Tambahkan Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    // ...
}
