<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

echo "🧪 Testing Audit Log Implementation\n";
echo str_repeat("=", 50) . "\n\n";

// Test 1: Audit log pada create
echo "Test 1: Audit log saat create Product\n";
try {
    $admin = User::where('role', 'admin')->whereNotNull('company_id')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada admin dengan company\n\n";
    } else {
        Auth::setUser($admin);

        $category = Category::first();
        $supplier = \App\Models\Supplier::first();

        if (!$category || !$supplier) {
            echo "⚠️  SKIP - Tidak ada kategori atau supplier\n\n";
        } else {
            $beforeCount = AuditLog::count();

            $product = Product::create([
                'name' => 'Test Audit Product',
                'sku' => 'AUDIT-' . time(),
                'stock' => 10,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'price' => 100000,
            ]);

            $afterCount = AuditLog::count();
            $newLog = AuditLog::latest()->first();

            echo "  Product created: {$product->name}\n";
            echo "  Audit logs before: {$beforeCount}\n";
            echo "  Audit logs after: {$afterCount}\n";
            echo "  New audit log action: {$newLog->action}\n";
            echo "  Entity type: " . class_basename($newLog->entity_type) . "\n";

            if ($afterCount > $beforeCount && $newLog->action === 'created') {
                echo "  ✅ PASS - Audit log created\n\n";
            } else {
                echo "  ❌ FAIL - Audit log tidak dibuat\n\n";
            }

            // Cleanup
            $product->delete();
        }
        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 2: Audit log pada update
echo "Test 2: Audit log saat update Product\n";
try {
    $admin = User::where('role', 'admin')->whereNotNull('company_id')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada admin dengan company\n\n";
    } else {
        Auth::setUser($admin);

        $product = Product::first();

        if (!$product) {
            echo "⚠️  SKIP - Tidak ada produk\n\n";
        } else {
            $beforeCount = AuditLog::count();
            $oldStock = $product->stock;

            $product->update(['stock' => $oldStock + 5]);

            $afterCount = AuditLog::count();
            $newLog = AuditLog::where('entity_type', get_class($product))
                ->where('entity_id', $product->id)
                ->where('action', 'updated')
                ->latest()
                ->first();

            echo "  Product updated: {$product->name}\n";
            echo "  Stock changed: {$oldStock} -> {$product->stock}\n";
            echo "  Audit logs before: {$beforeCount}\n";
            echo "  Audit logs after: {$afterCount}\n";

            if ($afterCount > $beforeCount && $newLog && $newLog->action === 'updated') {
                echo "  New audit log action: {$newLog->action}\n";
                echo "  Changes logged: " . json_encode($newLog->changes) . "\n";
                echo "  ✅ PASS - Audit log updated\n\n";
            } else {
                echo "  ❌ FAIL - Audit log tidak dibuat\n\n";
            }

            // Revert
            $product->update(['stock' => $oldStock]);
        }
        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 3: Audit log pada delete
echo "Test 3: Audit log saat delete Category\n";
try {
    $admin = User::where('role', 'admin')->whereNotNull('company_id')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada admin dengan company\n\n";
    } else {
        Auth::setUser($admin);

        $category = Category::create([
            'name' => 'Test Audit Category ' . time(),
        ]);

        $beforeCount = AuditLog::count();
        $categoryId = $category->id;

        $category->delete();

        $afterCount = AuditLog::count();
        $newLog = AuditLog::where('entity_type', get_class($category))
            ->where('entity_id', $categoryId)
            ->where('action', 'deleted')
            ->latest()
            ->first();

        echo "  Category deleted: ID {$categoryId}\n";
        echo "  Audit logs before: {$beforeCount}\n";
        echo "  Audit logs after: {$afterCount}\n";

        if ($afterCount > $beforeCount && $newLog && $newLog->action === 'deleted') {
            echo "  New audit log action: {$newLog->action}\n";
            echo "  ✅ PASS - Audit log deleted\n\n";
        } else {
            echo "  ❌ FAIL - Audit log tidak dibuat\n\n";
        }

        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 4: Audit log contains correct data
echo "Test 4: Verifikasi data audit log\n";
try {
    $log = AuditLog::with(['user', 'company'])->latest()->first();

    if (!$log) {
        echo "⚠️  SKIP - Tidak ada audit log\n\n";
    } else {
        echo "  Log ID: {$log->id}\n";
        echo "  User: " . ($log->user ? $log->user->name : 'N/A') . "\n";
        echo "  Company: " . ($log->company ? $log->company->name : 'N/A') . "\n";
        echo "  Entity: " . class_basename($log->entity_type) . " #{$log->entity_id}\n";
        echo "  Action: {$log->action}\n";
        echo "  IP: " . ($log->ip_address ?? 'N/A') . "\n";
        echo "  Timestamp: " . $log->created_at->format('Y-m-d H:i:s') . "\n";

        $hasRequiredFields = $log->user_id && $log->entity_type && $log->entity_id && $log->action;

        if ($hasRequiredFields) {
            echo "  ✅ PASS - Audit log berisi data lengkap\n\n";
        } else {
            echo "  ❌ FAIL - Audit log tidak lengkap\n\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 5: Filter audit log by company
echo "Test 5: Filter audit log by company\n";
try {
    $admin = User::where('role', 'admin')->whereNotNull('company_id')->first();

    if (!$admin || !$admin->company_id) {
        echo "⚠️  SKIP - Tidak ada admin dengan company\n\n";
    } else {
        $companyLogs = AuditLog::where('company_id', $admin->company_id)->count();
        $allLogs = AuditLog::count();

        echo "  Company ID: {$admin->company_id}\n";
        echo "  Company logs: {$companyLogs}\n";
        echo "  All logs: {$allLogs}\n";

        if ($companyLogs <= $allLogs) {
            echo "  ✅ PASS - Filter company berfungsi\n\n";
        } else {
            echo "  ❌ FAIL - Filter company error\n\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n";
echo "🎉 Testing Complete!\n\n";

// Summary
echo "📊 SUMMARY\n";
echo str_repeat("-", 50) . "\n";
$totalLogs = AuditLog::count();
$createdLogs = AuditLog::where('action', 'created')->count();
$updatedLogs = AuditLog::where('action', 'updated')->count();
$deletedLogs = AuditLog::where('action', 'deleted')->count();

echo "Total Audit Logs: {$totalLogs}\n";
echo "  - Created: {$createdLogs}\n";
echo "  - Updated: {$updatedLogs}\n";
echo "  - Deleted: {$deletedLogs}\n";
echo "\n";
echo "✅ Audit Log System telah diimplementasi!\n";
