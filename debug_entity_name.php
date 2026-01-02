<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AuditLog;
use App\Models\Product;

// Cek audit logs
$logs = AuditLog::with(['user', 'company'])->limit(5)->get();

echo "Debug Entity Name Helper\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($logs as $log) {
    echo "Log ID: {$log->id}\n";
    echo "Entity Type: {$log->entity_type}\n";
    echo "Entity ID: {$log->entity_id}\n";
    echo "Action: {$log->action}\n";

    // Debug helper function
    $entityType = class_basename($log->entity_type);
    $entityId = $log->entity_id;
    echo "Class Basename: {$entityType}\n";

    try {
        $modelClass = 'App\\Models\\' . $entityType;
        echo "Model Class: {$modelClass}\n";
        echo "Class Exists: " . (class_exists($modelClass) ? 'YES' : 'NO') . "\n";

        if (class_exists($modelClass)) {
            $entity = $modelClass::find($entityId);
            echo "Entity Found: " . ($entity ? 'YES' : 'NO') . "\n";

            if ($entity) {
                echo "Entity Name: " . ($entity->name ?? 'NO NAME FIELD') . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    echo str_repeat("-", 80) . "\n\n";
}
