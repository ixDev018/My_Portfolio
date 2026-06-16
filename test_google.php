<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$url = \Illuminate\Support\Facades\Storage::url('images/intro/profile.png');
echo "TEST_URL=" . $url . "\n";
