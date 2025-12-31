<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Company;

echo "===== USERS IN SYSTEM =====\n\n";
$users = User::select('id', 'name', 'email', 'role', 'company_id')->get();

if ($users->isEmpty()) {
    echo "❌ No users found. Creating test users...\n";

    // Create super admin
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => bcrypt('password123'),
        'role' => 'super_admin',
        'company_id' => null
    ]);
    echo "✓ Created Super Admin: {$superAdmin->email}\n";

    // Create company first
    $company = Company::create([
        'name' => 'Test Company',
        'subscription_type' => 'basic',
        'subscription_end_date' => now()->addMonths(1)
    ]);

    // Create admin
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
        'company_id' => $company->id
    ]);
    echo "✓ Created Admin: {$admin->email}\n";

    // Create staff
    $staff = User::create([
        'name' => 'Staff User',
        'email' => 'staff@test.com',
        'password' => bcrypt('password123'),
        'role' => 'staff',
        'company_id' => $company->id
    ]);
    echo "✓ Created Staff: {$staff->email}\n";
} else {
    echo "Found " . $users->count() . " users:\n\n";
    $users->each(function($u) {
        $company = $u->company ? " [Company: {$u->company->name}]" : "";
        echo "ID: {$u->id} | {$u->name} ({$u->email}) | Role: {$u->role}{$company}\n";
    });
}

echo "\n===== AVAILABLE ROLES =====\n";
echo "- super_admin (No company, can manage all tenants)\n";
echo "- admin (Company-scoped, can manage users & all data)\n";
echo "- staff (Company-scoped, can only view & record inventory)\n";

echo "\n===== TEST CREDENTIALS =====\n";
echo "SuperAdmin:\n  Email: superadmin@test.com\n  Password: password123\n\n";
echo "Admin:\n  Email: admin@test.com\n  Password: password123\n\n";
echo "Staff:\n  Email: staff@test.com\n  Password: password123\n";
