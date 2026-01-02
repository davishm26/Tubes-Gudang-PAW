<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToCompany;
use App\Traits\Auditable;

class Supplier extends Model
{
    use HasFactory, BelongsToCompany, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
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
