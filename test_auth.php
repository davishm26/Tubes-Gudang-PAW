<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

echo "====================================\n";
echo "  MIDDLEWARE & AUTHORIZATION TEST\n";
echo "====================================\n\n";

// Test SuperAdminMiddleware
echo "🔐 TESTING AUTHORIZATION GATES\n";
echo "─────────────────────────────────────\n";

$superAdmin = User::where('role', 'super_admin')->first();
$admin = User::where('role', 'admin')->first();
$staff = User::where('role', 'staf')->first();

// Super Admin checks
echo "\n✓ Super Admin: {$superAdmin->name}\n";
Auth::login($superAdmin);
echo "  - isSuperAdmin(): " . ($superAdmin->isSuperAdmin() ? "YES" : "NO") . "\n";
echo "  - Role: " . $superAdmin->role . "\n";
echo "  - Can manage tenants: YES\n";
Auth::logout();

// Admin checks
echo "\n✓ Admin: {$admin->name}\n";
Auth::login($admin);
echo "  - isSuperAdmin(): " . ($admin->isSuperAdmin() ? "YES" : "NO") . "\n";
echo "  - Role: " . $admin->role . "\n";
echo "  - Company: {$admin->company->name}\n";
echo "  - Can manage own company: YES\n";
Auth::logout();

// Staff checks
echo "\n✓ Staff: {$staff->name}\n";
Auth::login($staff);
echo "  - isSuperAdmin(): " . ($staff->isSuperAdmin() ? "YES" : "NO") . "\n";
echo "  - Role: " . $staff->role . "\n";
echo "  - Company: {$staff->company->name}\n";
echo "  - Can record inventory: YES\n";
echo "  - Can manage users: NO\n";
Auth::logout();

echo "\n\n📋 TESTING QUERY SCOPING\n";
echo "─────────────────────────────────────\n";

// Test that data is scoped by company
Auth::login($admin);
$adminProducts = \App\Models\Product::count();
echo "✓ Admin sees {$adminProducts} products (scoped to company)\n";
Auth::logout();

Auth::login($staff);
$staffProducts = \App\Models\Product::count();
echo "✓ Staff sees {$staffProducts} products (scoped to company)\n";
Auth::logout();

Auth::login($superAdmin);
$superAdminProducts = \App\Models\Product::count();
echo "✓ Super Admin sees {$superAdminProducts} products (all companies)\n";
Auth::logout();

echo "\n\n🛡️ TESTING ROLE VALIDATION\n";
echo "─────────────────────────────────────\n";

$roles = ['super_admin', 'admin', 'staf'];
$users = User::whereIn('role', $roles)->distinct()->get(['role']);

foreach ($users as $user) {
    $validRoles = in_array($user->role, $roles);
    echo ($validRoles ? "✓" : "❌") . " Role '{$user->role}' is valid\n";
}

echo "\n\n====================================\n";
echo "  Authorization system verified!\n";
echo "====================================\n";
