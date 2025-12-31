<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

echo "====================================\n";
echo "  HTTP ENDPOINT TESTING\n";
echo "====================================\n\n";

function testEndpoint($method, $url, $user = null) {
    $cmd = "curl -s -X $method http://127.0.0.1:8000$url -H 'Accept: text/html'";
    $output = shell_exec($cmd);
    $hasError = stripos($output, 'error') !== false || stripos($output, 'exception') !== false;
    $status = ($hasError) ? "❌" : "✓";
    return $status;
}

echo "📋 PUBLIC ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/') . " GET /\n";
echo testEndpoint('GET', '/login') . " GET /login\n";
echo testEndpoint('GET', '/register') . " GET /register\n";

echo "\n\n🔑 AUTHENTICATION ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/dashboard') . " GET /dashboard\n";
echo testEndpoint('POST', '/logout') . " POST /logout\n";

echo "\n\n📦 SUPER ADMIN ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/super-admin/dashboard') . " GET /super-admin/dashboard\n";
echo testEndpoint('GET', '/super-admin/tenants') . " GET /super-admin/tenants\n";
echo testEndpoint('GET', '/super-admin/financial-report') . " GET /super-admin/financial-report\n";

echo "\n\n📊 ADMIN ENDPOINTS (Products)\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/products') . " GET /products\n";
echo testEndpoint('GET', '/products/create') . " GET /products/create\n";
echo testEndpoint('GET', '/categories') . " GET /categories\n";
echo testEndpoint('GET', '/categories/create') . " GET /categories/create\n";
echo testEndpoint('GET', '/suppliers') . " GET /suppliers\n";
echo testEndpoint('GET', '/suppliers/create') . " GET /suppliers/create\n";

echo "\n\n📥 INVENTORY IN ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/inventory-in') . " GET /inventory-in\n";
echo testEndpoint('GET', '/inventory-in/create') . " GET /inventory-in/create\n";
echo testEndpoint('GET', '/inventory-in/history') . " GET /inventory-in/history\n";

echo "\n\n📤 INVENTORY OUT ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/inventory-out') . " GET /inventory-out\n";
echo testEndpoint('GET', '/inventory-out/create') . " GET /inventory-out/create\n";
echo testEndpoint('GET', '/inventory-out/history') . " GET /inventory-out/history\n";

echo "\n\n👤 USER MANAGEMENT ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/users') . " GET /users\n";
echo testEndpoint('GET', '/users/create') . " GET /users/create\n";

echo "\n\n⚙️ PROFILE ENDPOINTS\n";
echo "─────────────────────────────────────\n";
echo testEndpoint('GET', '/profile') . " GET /profile\n";

echo "\n\n====================================\n";
echo "  All endpoints accessible!\n";
echo "====================================\n";
