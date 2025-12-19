<?php
require 'vendor/autoload.php';
$r = new ReflectionClass('Illuminate\\Foundation\\Http\\Kernel');
foreach ($r->getDefaultProperties() as $k=>$v) { echo $k . PHP_EOL; }
