<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🗑️  Menghapus Semua Data dari Database\n";
echo str_repeat("=", 80) . "\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

$tables = [
    'audit_logs',
    'notifications',
    'inventory_outs',
    'inventory_ins',
    'products',
    'suppliers',
    'categories',
    'users',
    'companies',
    'password_reset_tokens',
    'sessions',
    'cache',
    'cache_locks',
    'jobs',
    'job_batches',
    'failed_jobs',
];

echo "Menghapus data dari tabel:\n";
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $count = DB::table($table)->count();
        DB::table($table)->truncate();
        echo "  ✅ {$table} ({$count} rows deleted)\n";
    } else {
        echo "  ⚠️  {$table} (table not found)\n";
    }
}

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ Semua data berhasil dihapus!\n";
echo "💡 Database sekarang kosong dan siap untuk data baru.\n";
