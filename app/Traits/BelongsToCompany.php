<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(fn (Builder $query) => $query->whenCompanyContext());

        static::creating(function ($model) {
            $user = Auth::user();
            if (!$model->company_id && $user && $user instanceof \App\Models\User) {
                $model->company_id = $user->company_id;
            }
        });
    }

    public function scopeWhenCompanyContext(Builder $query): Builder
    {
        $user = Auth::user();

        // Check if user is authenticated and not a super admin
        if ($user && $user instanceof \App\Models\User && !$user->isSuperAdmin()) {
            return $query->where('company_id', $user->company_id);
        }

        return $query;
    }

    public function scopeForCompany(Builder $query, $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}
