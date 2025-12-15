<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan untuk relasi HasMany

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi: Satu Kategori memiliki BANYAK Produk (One-to-Many).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}