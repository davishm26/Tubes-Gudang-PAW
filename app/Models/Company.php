<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_status', // e.g., active, trial, canceled
        'suspended',
        'meta',
    ];

    protected $casts = [
        'suspended' => 'boolean',
        'meta' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
