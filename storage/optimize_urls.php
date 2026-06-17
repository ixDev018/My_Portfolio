<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'projects' => ['main_video_path', 'thumbnail_video_path', 'featured_thumbnail', 'content'],
    'experiences' => ['bg_media_path', 'content'],
    'profiles' => ['intro_video_path', 'exp_default_bg_media_path', 'resume_path', 'about_content'],
    'skills' => ['image_path'],
    'intro_slides' => ['media_path']
];

foreach ($tables as $table => $columns) {
    $rows = DB::table($table)->get();
    foreach ($rows as $row) {
        $updates = [];
        foreach ($columns as $column) {
            if (!property_exists($row, $column)) continue;
            $value = $row->$column;
            if ($value && is_string($value)) {
                $originalValue = $value;
                
                // Add Cloudinary on-the-fly transformations before the version string
                $value = preg_replace('#(video/upload/)(v\d+/)#', '$1q_auto,f_auto,vc_auto/$2', $value);
                $value = preg_replace('#(image/upload/)(v\d+/)#', '$1q_auto,f_auto/$2', $value);
                
                if ($value !== $originalValue) {
                    $updates[$column] = $value;
                }
            }
        }
        if (!empty($updates)) {
            DB::table($table)->where('id', $row->id)->update($updates);
            echo "Optimized URLs for {$table} (ID: {$row->id})\n";
        }
    }
}

echo "\nDone! All Cloudinary URLs have been updated with automatic optimization flags.\n";
