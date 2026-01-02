<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // WAJIB: Import Auth Facade
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek demo mode terlebih dahulu (support both session keys)
        $isDemoMode = session('is_demo') || session('demo_mode') === 'true';
        if ($isDemoMode && session('demo_role') === 'admin') {
            return $next($request);
        }

        // 2. Cek apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, alihkan ke halaman login
            return redirect('/login');
        }

        // 3. Cek apakah role user adalah 'admin' atau 'super_admin'
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            // Jika user login tapi rolenya BUKAN admin/super_admin, alihkan ke dashboard
            return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        // 4. Cek apakah company user dalam status suspended
        $user = Auth::user();
        if ($user->company_id && $user->company) {
            if ($user->company->subscription_status === 'suspended' || $user->company->suspended) {
                Auth::logout();
                return redirect()->route('subscription.suspended')
                    ->with('error', 'Akun perusahaan Anda telah di-suspend. Silakan hubungi administrator.');
            }
        }

        // 5. Jika role adalah admin dan tidak suspended, lanjutkan request
        return $next($request);
    }
}
