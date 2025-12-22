<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalTenants = Company::count();

        // List all tenant companies with subscription status
        $tenants = Company::with('users')->orderBy('name')->get();

        // All users (global)
        $allUsers = User::with('company')->orderBy('name')->get();

        // Dummy revenue: sum of random values per company (temporary)
        $totalRevenue = $tenants->reduce(function ($carry, $company) {
            return $carry + rand(1000, 10000);
        }, 0);

        return view('super_admin.dashboard', compact('totalTenants', 'tenants', 'allUsers', 'totalRevenue'));
    }
}
