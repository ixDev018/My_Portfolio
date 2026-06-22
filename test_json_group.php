<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$interaction = \App\Models\Interaction::where('type', 'project_view')
    ->select('meta_data->title as title', \DB::raw('COUNT(*) as count'))
    ->groupBy('meta_data->title')
    ->orderBy('count', 'desc')
    ->first();

var_dump($interaction ? $interaction->title : 'null');
