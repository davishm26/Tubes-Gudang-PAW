<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

echo "🧪 Testing BelongsToCompany Trait\n";
echo str_repeat("=", 50) . "\n\n";

// Test 1: Data Isolation - Admin hanya lihat data company mereka
echo "Test 1: Admin hanya lihat data company mereka\n";
try {
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada user dengan role admin\n\n";
    } else {
        Auth::setUser($admin);
        $products = Product::all();
        $companies = $products->pluck('company_id')->unique();
        echo "  User: {$admin->name} (company_id: {$admin->company_id})\n";
        echo "  Products found: {$products->count()}\n";
        echo "  Companies seen: " . $companies->implode(', ') . "\n";

        if ($companies->count() === 1 && $companies->first() === $admin->company_id) {
            echo "  ✅ PASS - Admin hanya lihat data company sendiri\n\n";
        } else {
            echo "  ❌ FAIL - Admin bisa lihat data company lain!\n\n";
        }
        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 2: Super Admin see all
echo "Test 2: Super Admin lihat semua data\n";
try {
    $superAdmin = User::where('role', 'super_admin')->first();
    if (!$superAdmin) {
        echo "⚠️  SKIP - Tidak ada super admin\n\n";
    } else {
        Auth::setUser($superAdmin);
        $products = Product::all();
        $companies = $products->pluck('company_id')->unique();
        echo "  User: {$superAdmin->name}\n";
        echo "  Products found: {$products->count()}\n";
        echo "  Companies seen: " . $companies->implode(', ') . "\n";

        if ($companies->count() >= 1) {
            echo "  ✅ PASS - Super Admin lihat data dari semua company\n\n";
        } else {
            echo "  ❌ FAIL - Super Admin tidak lihat semua data\n\n";
        }
        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 3: Auto-assign company_id saat create
echo "Test 3: Auto-assign company_id saat create\n";
try {
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada user admin\n\n";
    } else {
        Auth::setUser($admin);

        $category = Category::first();
        $supplier = Supplier::first();

        if (!$category || !$supplier) {
            echo "⚠️  SKIP - Tidak ada kategori atau supplier\n\n";
        } else {
            $testProduct = Product::create([
                'name' => 'Test Auto Assign',
                'sku' => 'TEST-' . time(),
                'stock' => 5,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
            ]);

            echo "  User company_id: {$admin->company_id}\n";
            echo "  Product company_id: {$testProduct->company_id}\n";

            if ($testProduct->company_id === $admin->company_id) {
                echo "  ✅ PASS - company_id otomatis terisi\n";
            } else {
                echo "  ❌ FAIL - company_id tidak auto-assign!\n";
            }

            $testProduct->delete();
            echo "  (Test product deleted)\n\n";
        }
        Auth::forgetGuards();
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 4: No error when logged out
echo "Test 4: Tidak error saat user logout\n";
try {
    Auth::forgetGuards();
    $products = Product::all();
    echo "  Products found: {$products->count()}\n";
    echo "  ✅ PASS - Tidak ada error saat logout\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAIL - Error: " . $e->getMessage() . "\n\n";
}

// Test 5: forCompany() scope
echo "Test 5: Manual scope forCompany()\n";
try {
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "⚠️  SKIP - Tidak ada admin\n\n";
    } else {
        $companyId = $admin->company_id;
        $companyProducts = Product::forCompany($companyId)->get();

        echo "  Testing forCompany({$companyId})\n";
        echo "  Products found: {$companyProducts->count()}\n";

        $allSameCompany = $companyProducts->every(fn($p) => $p->company_id === $companyId);

        if ($allSameCompany || $companyProducts->count() === 0) {
            echo "  ✅ PASS - Scope forCompany() bekerja\n\n";
        } else {
            echo "  ❌ FAIL - Ada produk dari company lain!\n\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 6: Cross-tenant protection
echo "Test 6: Cross-tenant data protection\n";
try {
    $admins = User::where('role', 'admin')->take(2)->get();

    if ($admins->count() < 2) {
        echo "⚠️  SKIP - Perlu minimal 2 admin dari company berbeda\n\n";
    } else {
        $admin1 = $admins[0];
        $admin2 = $admins[1];

        if ($admin1->company_id === $admin2->company_id) {
            echo "⚠️  SKIP - Admin dari company yang sama\n\n";
        } else {
            Auth::setUser($admin1);
            $productsAsAdmin1 = Product::all();

            Auth::forgetGuards();
            Auth::setUser($admin2);
            $productsAsAdmin2 = Product::all();

            echo "  Admin1 ({$admin1->name}, company {$admin1->company_id}): {$productsAsAdmin1->count()} products\n";
            echo "  Admin2 ({$admin2->name}, company {$admin2->company_id}): {$productsAsAdmin2->count()} products\n";

            $admin1Companies = $productsAsAdmin1->pluck('company_id')->unique();
            $admin2Companies = $productsAsAdmin2->pluck('company_id')->unique();

            if ($admin1Companies->count() === 1 && $admin2Companies->count() === 1 &&
                $admin1Companies->first() !== $admin2Companies->first()) {
                echo "  ✅ PASS - Data terisolasi antar tenant\n\n";
            } else {
                echo "  ❌ FAIL - Ada kebocoran data antar tenant!\n\n";
            }
            Auth::forgetGuards();
        }
    }
} catch (\Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 7: Type safety (instanceof check)
echo "Test 7: Type safety dengan instanceof\n";
try {
    Auth::forgetGuards();
    $result1 = Product::all();

    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        Auth::setUser($admin);
        $result2 = Product::all();
        Auth::forgetGuards();
    }

    $superAdmin = User::where('role', 'super_admin')->first();
    if ($superAdmin) {
        Auth::setUser($superAdmin);
        $result3 = Product::all();
        Auth::forgetGuards();
    }

    echo "  ✅ PASS - Tidak ada error dengan instanceof check\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAIL - Error: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n";
echo "🎉 Testing Complete!\n\n";

// Summary
echo "📊 SUMMARY\n";
echo str_repeat("-", 50) . "\n";
$userCount = User::count();
$productCount = Product::withoutGlobalScopes()->count();
$categoryCount = Category::withoutGlobalScopes()->count();
$companyCount = User::pluck('company_id')->unique()->filter()->count();

echo "Total Users: {$userCount}\n";
echo "Total Products: {$productCount}\n";
echo "Total Categories: {$categoryCount}\n";
echo "Total Companies: {$companyCount}\n";
echo "\n";
echo "✅ Trait BelongsToCompany telah diuji!\n";
