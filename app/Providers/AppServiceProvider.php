<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // WAJIB: Import Facade Gate
use App\Models\User; // WAJIB: Import Model User
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definisikan Gate 'is_admin'
        // Gate ini memeriksa apakah role pengguna yang sedang login adalah 'admin'
        Gate::define('is_admin', function (User $user) {
            // Kita asumsikan kolom role di tabel users berisi string 'admin'
            return $user->role === 'admin';
        });

        // Set locale Carbon agar diffForHumans menggunakan bahasa aplikasi
        Carbon::setLocale(config('app.locale'));
    }
}
