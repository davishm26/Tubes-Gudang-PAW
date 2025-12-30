<?php
// Update user to super_admin
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    $user->role = 'super_admin';
    $user->save();
    echo "User {$user->email} updated to super_admin\n";
} else {
    echo "No users found\n";
}
