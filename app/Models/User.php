<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <--- PENTING: TAMBAHKAN KOLOM 'role' DI SINI
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Opsional: Definisikan relasi ke transaksi stok
    // Relasi: Satu User (Admin/Staf) membuat BANYAK transaksi Stok Masuk
    public function inventoryIns(): HasMany
    {
        return $this->hasMany(InventoryIn::class);
    }

    // Relasi: Satu User (Admin/Staf) membuat BANYAK transaksi Stok Keluar
    public function inventoryOuts(): HasMany
    {
        return $this->hasMany(InventoryOut::class);
    }

    /**
     * Relation: User belongs to a Company (nullable for super_admin)
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Helper: Is Super Admin?
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
