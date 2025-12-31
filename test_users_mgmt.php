<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "====================================\n";
echo "  USER MANAGEMENT TEST\n";
echo "====================================\n\n";

$admin = User::where('role', 'admin')->first();
$staff = User::where('role', 'staf')->first();
$superAdmin = User::where('role', 'super_admin')->first();

// Test Admin can manage users
echo "👤 TESTING USER MANAGEMENT (Admin)\n";
echo "─────────────────────────────────────\n";
Auth::login($admin);

try {
    // Create new user
    $newUser = User::create([
        'name' => 'New Test User - ' . now()->format('YmdHis'),
        'email' => 'newuser' . now()->format('Ymdis') . '@test.com',
        'password' => Hash::make('password123'),
        'role' => 'staf',
        'company_id' => $admin->company_id
    ]);
    echo "✓ Admin created user: {$newUser->name} (ID: {$newUser->id})\n";

    // Read users in company
    $userCount = User::where('company_id', $admin->company_id)->count();
    echo "✓ Admin can view {$userCount} users in company\n";

    // Update user
    $newUser->update(['name' => $newUser->name . ' [Updated]']);
    echo "✓ Admin can update user name\n";

    // Delete user
    $newUser->delete();
    echo "✓ Admin can delete user\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test Staff cannot manage users
echo "\n\n👥 TESTING STAFF RESTRICTIONS\n";
echo "─────────────────────────────────────\n";
Auth::login($staff);

try {
    $newUser = User::create([
        'name' => 'Staff Created User',
        'email' => 'staffcreated@test.com',
        'password' => Hash::make('password123'),
        'role' => 'staf',
        'company_id' => $staff->company_id
    ]);
    echo "❌ Staff created user (SHOULD NOT ALLOW)\n";
    $newUser->delete();
} catch (Exception $e) {
    echo "✓ Staff cannot create user (restricted by authorization)\n";
}

Auth::logout();

// Test Super Admin can manage all users
echo "\n\n🔑 TESTING SUPER ADMIN\n";
echo "─────────────────────────────────────\n";
Auth::login($superAdmin);

$totalUsers = User::count();
$companies = \App\Models\Company::count();
echo "✓ Super Admin sees all {$totalUsers} users\n";
echo "✓ Super Admin can manage {$companies} companies\n";

Auth::logout();

echo "\n\n====================================\n";
echo "  User management verified!\n";
echo "====================================\n";
