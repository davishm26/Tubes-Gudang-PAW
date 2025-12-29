<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->company_id) {
            abort(403, 'User must belong to a company/tenant');
        }

        if ($user->company && $user->company->suspended) {
            return response()->view('subscription.suspended', [], 403);
        }

        return $next($request);
    }
}
