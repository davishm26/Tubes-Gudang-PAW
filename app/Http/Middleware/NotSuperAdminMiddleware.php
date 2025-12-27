<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotSuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow demo mode
        if (session('demo_mode') === 'true') {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && $user->role === 'super_admin') {
            abort(403, 'Super Admin cannot access this area.');
        }

        return $next($request);
    }
}
