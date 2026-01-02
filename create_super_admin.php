<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "👤 Membuat Super Admin User\n";
echo str_repeat("=", 80) . "\n\n";

// Check if super admin already exists
$existing = User::where('role', 'super_admin')->first();

if ($existing) {
    echo "⚠️  Super admin sudah ada: {$existing->name} ({$existing->email})\n";
    echo "Tidak membuat user baru.\n";
} else {
    // Create super admin
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@gudang.com',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
        'company_id' => null,
        'email_verified_at' => now(),
    ]);

    echo "✅ Super Admin berhasil dibuat!\n\n";
    echo "Details:\n";
    echo "  Name     : {$superAdmin->name}\n";
    echo "  Email    : {$superAdmin->email}\n";
    echo "  Password : password\n";
    echo "  Role     : {$superAdmin->role}\n";
    echo "\n";
    echo "💡 Login dengan kredensial di atas untuk mengakses sistem.\n";
}
