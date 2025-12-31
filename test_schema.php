<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "====================================\n";
echo "  DATABASE TABLE STRUCTURE\n";
echo "====================================\n\n";

$tables = ['products', 'categories', 'suppliers', 'users', 'companies'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "📋 Table: $table\n";
        $columns = Schema::getColumns($table);
        foreach ($columns as $column) {
            echo "  - {$column['name']} ({$column['type']})";
            if ($column['nullable']) echo " [nullable]";
            if (isset($column['default'])) echo " [default: {$column['default']}]";
            echo "\n";
        }
        echo "\n";
    }
}
