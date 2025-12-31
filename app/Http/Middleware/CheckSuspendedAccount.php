<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspendedAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip check jika:
        // 1. User belum login
        // 2. User adalah super_admin
        // 3. Sedang mengakses halaman suspended atau request reactivation
        // 4. Sedang logout
        if (!$user ||
            $user->role === 'super_admin' ||
            $request->routeIs('subscription.suspended') ||
            $request->routeIs('subscription.reactivation.request') ||
            $request->routeIs('logout')) {
            return $next($request);
        }

        // Cek apakah user memiliki company dan company tersebut di-suspend
        if ($user->company_id) {
            $company = $user->company;

            if ($company && ($company->suspended || $company->subscription_status === 'suspended')) {
                // Simpan company_id ke session untuk akses di halaman suspended
                session(['company_id' => $company->id]);
                session(['suspend_reason' => $company->suspend_reason]);
                session(['suspend_reason_type' => $company->suspend_reason_type]);

                // Logout user dan redirect ke halaman suspended
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('subscription.suspended')
                    ->with('error', 'Akun perusahaan Anda telah di-suspend. Silakan hubungi administrator.');
            }
        }

        return $next($request);
    }
}
