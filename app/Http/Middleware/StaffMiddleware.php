<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     * - Admin users are allowed everywhere.
     * - Staff users are only allowed to access a small set of route names.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        // Admin: full access
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Allowed route names for staff (minimal: only record stock)
        // Staff may only access the forms to record incoming/outgoing stock
        $allowed = [
            'inventory-in.create',
            'inventory-in.store',
            'inventory-out.create',
            'inventory-out.store',
        ];

        $routeName = optional($request->route())->getName();

        if (in_array($routeName, $allowed, true)) {
            return $next($request);
        }

        // Deny access
        abort(403, 'Akses ditolak.');
    }
}
