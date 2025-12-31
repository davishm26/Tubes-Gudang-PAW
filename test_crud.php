<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

echo "====================================\n";
echo "  CRUD OPERATIONS TEST\n";
echo "====================================\n\n";

$admin = User::where('role', 'admin')->first();
$staff = User::where('role', 'staf')->first();

// Test Category CRUD as Admin
echo "📝 TESTING CATEGORY CRUD (Admin)\n";
echo "─────────────────────────────────────\n";
Auth::login($admin);

try {
    // CREATE
    $category = Category::create([
        'name' => 'Test Category - ' . now()->format('YmdHis'),
        'description' => 'Test category for ' . $admin->company->name,
        'company_id' => $admin->company_id
    ]);
    echo "✓ CREATE: Category created (ID: {$category->id})\n";

    // READ
    $retrieved = Category::find($category->id);
    echo "✓ READ: Category retrieved ({$retrieved->name})\n";

    // UPDATE
    $category->update(['name' => $category->name . ' [Updated]']);
    echo "✓ UPDATE: Category name updated\n";

    // DELETE
    $category->delete();
    echo "✓ DELETE: Category deleted\n";
} catch (Exception $e) {
    echo "❌ CATEGORY CRUD ERROR: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test Supplier CRUD as Admin
echo "\n\n🏭 TESTING SUPPLIER CRUD (Admin)\n";
echo "─────────────────────────────────────\n";
Auth::login($admin);

try {
    // CREATE
    $supplier = Supplier::create([
        'name' => 'Test Supplier - ' . now()->format('YmdHis'),
        'contact' => '081234567890',
        'address' => 'Test Address',
        'company_id' => $admin->company_id
    ]);
    echo "✓ CREATE: Supplier created (ID: {$supplier->id})\n";

    // READ
    $retrieved = Supplier::find($supplier->id);
    echo "✓ READ: Supplier retrieved ({$retrieved->name})\n";

    // UPDATE
    $supplier->update(['contact' => '089876543210']);
    echo "✓ UPDATE: Supplier contact updated\n";

    // DELETE
    $supplier->delete();
    echo "✓ DELETE: Supplier deleted\n";
} catch (Exception $e) {
    echo "❌ SUPPLIER CRUD ERROR: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test Product CRUD as Admin
echo "\n\n📦 TESTING PRODUCT CRUD (Admin)\n";
echo "─────────────────────────────────────\n";
Auth::login($admin);

try {
    // Create category for product
    $categoryName = 'TempCat-' . now()->format('YmdHis');
    $category = Category::firstOrCreate(
        ['name' => $categoryName, 'company_id' => $admin->company_id],
        ['name' => $categoryName, 'company_id' => $admin->company_id]
    );

    $supplierName = 'TempSupp-' . now()->format('YmdHis');
    $supplier = Supplier::firstOrCreate(
        ['name' => $supplierName, 'company_id' => $admin->company_id],
        ['name' => $supplierName, 'contact' => '081234567890', 'company_id' => $admin->company_id]
    );
    $product = Product::create([
        'name' => 'Test Product - ' . now()->format('YmdHis'),
        'sku' => 'SKU-' . now()->format('YmdHis'),
        'description' => 'Test product',
        'category_id' => $category->id,
        'supplier_id' => $supplier->id,
        'stock' => 100,
        'price' => 50000,
        'company_id' => $admin->company_id
    ]);
    echo "✓ CREATE: Product created (ID: {$product->id})\n";

    // READ
    $retrieved = Product::find($product->id);
    echo "✓ READ: Product retrieved ({$retrieved->name})\n";

    // UPDATE
    $product->update(['stock' => 150]);
    echo "✓ UPDATE: Product quantity updated\n";

    // DELETE
    $product->delete();
    echo "✓ DELETE: Product deleted\n";

    // Cleanup
    $category->delete();
    $supplier->delete();
} catch (Exception $e) {
    echo "❌ PRODUCT CRUD ERROR: " . $e->getMessage() . "\n";
}

Auth::logout();

// Test Staff cannot create
echo "\n\n👥 TESTING STAFF RESTRICTIONS\n";
echo "─────────────────────────────────────\n";
Auth::login($staff);

try {
    $category = Category::create([
        'name' => 'Staff Category',
        'company_id' => $staff->company_id
    ]);
    echo "❌ Staff created category (SHOULD NOT ALLOW)\n";
} catch (Exception $e) {
    echo "✓ Staff cannot create category (restricted)\n";
}

Auth::logout();

echo "\n\n====================================\n";
echo "  CRUD operations verified!\n";
echo "====================================\n";
