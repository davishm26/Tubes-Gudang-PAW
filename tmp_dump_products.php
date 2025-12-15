<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$products = App\Models\Product::select('id','name','image')->take(10)->get();
echo $products->toJson(JSON_PRETTY_PRINT) . PHP_EOL;
