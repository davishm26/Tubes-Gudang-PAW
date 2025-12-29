<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToCompany;

class Category extends Model
{
    use BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
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
