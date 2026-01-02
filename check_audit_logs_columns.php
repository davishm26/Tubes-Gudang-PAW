<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::getSchemaBuilder()->getColumnListing('audit_logs');
echo "Audit Logs Columns:\n";
echo json_encode($columns, JSON_PRETTY_PRINT) . "\n";
