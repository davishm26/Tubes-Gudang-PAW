<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$companies = \App\Models\Company::all();
echo 'Companies count: ' . $companies->count() . PHP_EOL;

foreach ($companies as $c) {
    echo $c->name . ': ' . ($c->subscription_expires_at ?? 'null') . PHP_EOL;
}
