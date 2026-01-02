<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admins = \App\Models\User::where('role', 'admin')->get();
echo "Admins:\n";
foreach ($admins as $admin) {
    echo "  - {$admin->name} (ID: {$admin->id}, company_id: " . ($admin->company_id ?? 'NULL') . ")\n";
}
