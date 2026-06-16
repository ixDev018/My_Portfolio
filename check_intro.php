<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$profile = App\Models\Profile::first();
if ($profile) {
    echo "Profile data:\n";
    foreach ($profile->toArray() as $key => $val) {
        if (is_array($val)) {
            echo "  {$key}: " . json_encode($val) . "\n";
        } else {
            echo "  {$key}: {$val}\n";
        }
    }
}
