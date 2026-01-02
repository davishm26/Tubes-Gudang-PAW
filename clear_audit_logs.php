<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AuditLog;

echo "Hapus Semua Audit Logs\n";
echo str_repeat("=", 80) . "\n\n";

$count = AuditLog::count();
echo "Total audit logs saat ini: {$count}\n\n";

if ($count > 0) {
    echo "Menghapus semua audit logs...\n";
    AuditLog::truncate();
    echo "✅ Berhasil menghapus {$count} audit logs\n\n";
} else {
    echo "⚠️  Tidak ada audit logs untuk dihapus\n\n";
}

$remaining = AuditLog::count();
echo "Total audit logs sekarang: {$remaining}\n";
