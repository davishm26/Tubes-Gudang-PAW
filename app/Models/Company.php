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
        'subscription_expires_at',
        'subscription_end_date',
        'subscription_price',
        'subscription_paid_at',
    ];

    protected $casts = [
        'suspended' => 'boolean',
        'meta' => 'array',
        'subscription_expires_at' => 'datetime',
        'subscription_end_date' => 'date',
        'subscription_paid_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
