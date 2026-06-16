<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$adapter = Storage::disk('google')->getAdapter();
$meta = $adapter->getMetadata('skills/skill_1780648975_6a228c0f77170.png');

if ($meta && is_callable([$meta, 'extraMetadata'])) {
    $extra = $meta->extraMetadata();
    echo "ID: " . $extra['id'] . "\n";
} else {
    echo "extraMetadata not available\n";
}
