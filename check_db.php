<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Profile Hero Media: " . App\Models\Profile::first()->hero_media . "\n";
echo "Profile Intro Media: " . App\Models\Profile::first()->intro_video . "\n";

foreach (App\Models\ToolItem::all() as $tool) {
    echo "Tool: " . $tool->image . "\n";
}

foreach (App\Models\Skill::all() as $skill) {
    echo "Skill: " . $skill->image . "\n";
}
