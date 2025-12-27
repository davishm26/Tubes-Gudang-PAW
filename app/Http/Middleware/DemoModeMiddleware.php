<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class DemoModeMiddleware
{
    /**
     * Handle an incoming request.
     * Inject demo user jika session demo_mode aktif dan bypass auth requirement
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah demo mode aktif dari session
        if (Session::get('demo_mode')) {
            $role = Session::get('demo_role', 'staff');

            // Buat object user dummy sebagai array untuk view
            $demoUser = [
                'id' => $role === 'admin' ? 999 : 998,
                'name' => $role === 'admin' ? 'Demo Admin' : 'Demo Staff',
                'email' => $role === 'admin' ? 'admin@demo.com' : 'staff@demo.com',
                'role' => $role,
                'company_id' => 999,
                'is_demo' => true
            ];

            // Jangan gunakan Auth::setUser() untuk demo mode
            // Cukup share ke view
            view()->share('isDemoMode', true);
            view()->share('demoUser', (object) $demoUser);
            view()->share('user', (object) $demoUser); // Untuk compatibility dengan blade yang pakai Auth::user()

            // Mark request as demo
            $request->attributes->set('is_demo', true);
            $request->attributes->set('demo_user', (object) $demoUser);
        } else {
            view()->share('isDemoMode', false);
        }

        return $next($request);
    }
}
