<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

echo "Debug Entity Name in AuditLog\n";
echo str_repeat("=", 80) . "\n\n";

// Set user
$admin = User::where('role', 'admin')->whereNotNull('company_id')->first();
if (!$admin) {
    echo "❌ Tidak ada admin dengan company\n";
    exit;
}

Auth::setUser($admin);

// Get category dan supplier
$category = Category::first();
$supplier = Supplier::first();

// Check latest audit log before creation
$countBefore = AuditLog::count();
echo "Audit logs before: " . $countBefore . "\n\n";

// Create product
echo "Creating product...\n";
$product = Product::create([
    'company_id' => $admin->company_id,
    'category_id' => $category->id,
    'supplier_id' => $supplier->id,
    'sku' => 'TEST-DEBUG-' . time(),
    'code' => 'DBG-' . time(),
    'name' => 'Debug Product Name ' . time(),
    'stock' => 50,
    'price' => 75000,
]);

echo "Product created with ID: {$product->id}\n";
echo "Product name: {$product->name}\n";
echo "Product attributes: " . json_encode($product->getAttributes()) . "\n\n";

// Check audit log
$countAfter = AuditLog::count();
echo "Audit logs after: " . $countAfter . "\n";

$logs = AuditLog::latest()->limit(3)->get();
echo "\nLast 3 logs:\n";
foreach ($logs as $log) {
    echo "  Log {$log->id}: entity_id={$log->entity_id}, entity_name=" . ($log->entity_name ?? 'NULL') . ", action={$log->action}\n";
}

// Cleanup
$product->delete();
