<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = DB::table('products')->select('id','name','featured_image')->whereNotNull('featured_image')->get();
echo "=== Products with images ===\n";
foreach ($products as $p) {
    echo "{$p->id} | {$p->name} | {$p->featured_image}\n";
}
echo "\nTotal with images: " . DB::table('products')->whereNotNull('featured_image')->count() . "\n";
