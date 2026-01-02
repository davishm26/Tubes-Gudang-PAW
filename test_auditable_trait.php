<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "🔍 Debugging Auditable Trait\n";
echo str_repeat("=", 50) . "\n\n";

// Cek traits yang digunakan
echo "1. Checking Product traits:\n";
$traits = class_uses_recursive(Product::class);
foreach ($traits as $trait) {
    echo "   - " . $trait . "\n";
}
echo "\n";

// Cek apakah bootAuditable exists
echo "2. Checking boot methods:\n";
$reflection = new ReflectionClass(Product::class);
$methods = $reflection->getMethods(ReflectionMethod::IS_STATIC);
foreach ($methods as $method) {
    if (str_starts_with($method->getName(), 'boot')) {
        echo "   - " . $method->getName() . " (defined in " . $method->getDeclaringClass()->getName() . ")\n";
    }
}
echo "\n";

// Cek apakah ada user
echo "3. Checking users:\n";
$adminCount = User::where('role', 'admin')->count();
$superAdminCount = User::where('role', 'super_admin')->count();
echo "   Admin users: $adminCount\n";
echo "   Super admin users: $superAdminCount\n";
echo "\n";

// Cek company
echo "4. Checking companies:\n";
$companyCount = \App\Models\Company::count();
echo "   Total companies: $companyCount\n";
if ($companyCount > 0) {
    $firstCompany = \App\Models\Company::first();
    echo "   First company: {$firstCompany->name} (ID: {$firstCompany->id})\n";
}
echo "\n";

// Test create dengan user
echo "5. Testing Product creation with audit:\n";
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    $admin = User::where('role', 'super_admin')->first();
}

if ($admin) {
    Auth::setUser($admin);
    echo "   User set: {$admin->name} (role: {$admin->role}, company_id: {$admin->company_id})\n";
    echo "   Auth check: " . (Auth::check() ? 'YES' : 'NO') . "\n";
    echo "   Auth user: " . (Auth::user() ? Auth::user()->name : 'NULL') . "\n";

    // Get first category and supplier
    $category = Category::first();
    $supplier = \App\Models\Supplier::first();

    if ($category && $supplier) {
        echo "\n   Creating product...\n";
        $auditCountBefore = AuditLog::count();

        try {
            $product = Product::create([
                'company_id' => $admin->company_id ?? \App\Models\Company::first()->id,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'sku' => 'SKU-' . time(),
                'code' => 'TEST-AUDIT-' . time(),
                'name' => 'Test Audit Product',
                'stock' => 10,
                'price' => 50000,
            ]);

            echo "   Product created: {$product->name} (ID: {$product->id})\n";

            $auditCountAfter = AuditLog::count();
            echo "   Audit logs before: $auditCountBefore\n";
            echo "   Audit logs after: $auditCountAfter\n";

            if ($auditCountAfter > $auditCountBefore) {
                $latestAudit = AuditLog::latest()->first();
                echo "   ✅ Audit created: {$latestAudit->action} - {$latestAudit->entity_type}\n";
                echo "      Changes: " . json_encode($latestAudit->changes) . "\n";
            } else {
                echo "   ❌ No audit log created!\n";
                echo "\n   Checking if trait events are registered:\n";

                // Check if Product has observers
                $dispatcher = Product::getEventDispatcher();
                if ($dispatcher) {
                    echo "   Event dispatcher exists: YES\n";
                    $hasCreatedEvent = $dispatcher->hasListeners('eloquent.created: ' . Product::class);
                    echo "   Has 'created' listener: " . ($hasCreatedEvent ? 'YES' : 'NO') . "\n";
                } else {
                    echo "   Event dispatcher exists: NO\n";
                }
            }

        } catch (\Exception $e) {
            echo "   ❌ ERROR: " . $e->getMessage() . "\n";
            echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
    } else {
        echo "   ⚠️  No category or supplier found\n";
    }
} else {
    echo "   ⚠️  No user found\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 Debug Complete!\n";
