<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan untuk relasi HasMany

class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact',
    ];

    /**
     * Relasi: Satu Pemasok memiliki BANYAK Produk (One-to-Many).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
