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

echo "Test Entity Name Saving\n";
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

if (!$category || !$supplier) {
    echo "❌ Tidak ada kategori atau supplier\n";
    exit;
}

// Create product
echo "Creating product...\n";
$product = Product::create([
    'company_id' => $admin->company_id,
    'category_id' => $category->id,
    'supplier_id' => $supplier->id,
    'sku' => 'TEST-ENTITY-NAME-' . time(),
    'code' => 'TEN-' . time(),
    'name' => 'Product dengan Entity Name Test ' . time(),
    'stock' => 100,
    'price' => 150000,
]);

echo "Product created: {$product->name}\n\n";

// Check audit log
$latestLog = AuditLog::where('entity_type', get_class($product))
    ->where('entity_id', $product->id)
    ->latest()
    ->first();

if ($latestLog) {
    echo "✅ Audit Log Found:\n";
    echo "  Entity Type: {$latestLog->entity_type}\n";
    echo "  Entity ID: {$latestLog->entity_id}\n";
    echo "  Entity Name: " . ($latestLog->entity_name ?? 'NULL') . "\n";
    echo "  Action: {$latestLog->action}\n";
} else {
    echo "❌ Audit log tidak ditemukan\n";
}

// Cleanup
$product->delete();
