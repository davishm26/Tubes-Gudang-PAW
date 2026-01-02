<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "Testing extractEntityName directly\n";
echo str_repeat("=", 80) . "\n\n";

// Create a dummy product
$product = new Product();
$product->name = "Test Product";
$product->id = 999;

// Use reflection to call the protected method
$method = new ReflectionMethod(Product::class, 'extractEntityName');
$method->setAccessible(true);

$result = $method->invoke(null, $product);

echo "Product name: " . $product->name . "\n";
echo "Product ID: " . $product->id . "\n";
echo "Extracted name: " . ($result ?? 'NULL') . "\n";

echo "\n\nChecking getAttributes:\n";
$attrs = $product->getAttributes();
echo "Attributes: " . json_encode($attrs) . "\n";
