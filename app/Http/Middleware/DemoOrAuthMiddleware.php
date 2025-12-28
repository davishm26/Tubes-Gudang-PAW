<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DemoOrAuthMiddleware
{
    /**
     * Handle an incoming request.
     * Allow access if user is authenticated OR in demo mode
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if demo mode is active (support both is_demo and demo_mode)
        if (Session::get('is_demo') || Session::get('demo_mode')) {
            // Demo mode - allow access
            return $next($request);
        }

        // Normal mode - require authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
