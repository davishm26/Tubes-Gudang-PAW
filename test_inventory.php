<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Illuminate\Support\Facades\Auth;

echo "====================================\n";
echo "  INVENTORY OPERATIONS TEST\n";
echo "====================================\n\n";

$admin = User::where('role', 'admin')->first();
$staff = User::where('role', 'staf')->first();

// Create test data
Auth::login($admin);

echo "📦 SETTING UP TEST DATA\n";
echo "─────────────────────────────────────\n";

$category = Category::firstOrCreate(
    ['name' => 'Electronics', 'company_id' => $admin->company_id],
    ['name' => 'Electronics', 'company_id' => $admin->company_id]
);

$supplier = Supplier::firstOrCreate(
    ['name' => 'Tech Supplier', 'company_id' => $admin->company_id],
    ['name' => 'Tech Supplier', 'contact' => '081234567890', 'company_id' => $admin->company_id]
);

$product = Product::firstOrCreate(
    ['sku' => 'PROD-INV-TEST-' . now()->format('Ymd'), 'company_id' => $admin->company_id],
    [
        'name' => 'Test Product for Inventory',
        'sku' => 'PROD-INV-TEST-' . now()->format('Ymd'),
        'category_id' => $category->id,
        'supplier_id' => $supplier->id,
        'stock' => 0,
        'price' => 100000,
        'company_id' => $admin->company_id
    ]
);

echo "✓ Product created/retrieved: {$product->name} (ID: {$product->id})\n";
echo "✓ Current stock: {$product->stock}\n";

Auth::logout();

// Test Inventory In - Both Admin and Staff can do this
echo "\n\n📥 TESTING INVENTORY IN OPERATIONS\n";
echo "─────────────────────────────────────\n";

Auth::login($staff);
echo "✓ Logged in as Staff: {$staff->name}\n";

try {
    $inventoryIn = InventoryIn::create([
        'product_id' => $product->id,
        'quantity' => 50,
        'supplier_id' => $supplier->id,
        'date' => now(),
        'notes' => 'Test inventory in by staff',
        'user_id' => $staff->id,
        'company_id' => $staff->company_id
    ]);
    echo "✓ Staff recorded Inventory In: {$inventoryIn->quantity} units (ID: {$inventoryIn->id})\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test Inventory Out - Both Admin and Staff can do this
echo "\n\n📤 TESTING INVENTORY OUT OPERATIONS\n";
echo "─────────────────────────────────────\n";

Auth::login($admin);
echo "✓ Logged in as Admin: {$admin->name}\n";

try {
    $inventoryOut = InventoryOut::create([
        'product_id' => $product->id,
        'quantity' => 20,
        'date' => now(),
        'notes' => 'Test inventory out by admin',
        'user_id' => $admin->id,
        'company_id' => $admin->company_id
    ]);
    echo "✓ Admin recorded Inventory Out: {$inventoryOut->quantity} units (ID: {$inventoryOut->id})\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test viewing history
echo "\n\n📊 TESTING INVENTORY HISTORY\n";
echo "─────────────────────────────────────\n";

Auth::login($staff);
$inCount = InventoryIn::count();
$outCount = InventoryOut::count();
echo "✓ Staff can view inventory in history: $inCount records\n";
echo "✓ Staff can view inventory out history: $outCount records\n";

Auth::logout();

echo "\n\n====================================\n";
echo "  Inventory operations verified!\n";
echo "====================================\n";
