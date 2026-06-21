<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Cloudinary\Cloudinary;

$oldCloudName = 'dq2nuqpqr';
$newCloudName = 'dhxflpxe2';

// Ensure the old account is loaded for deletion
$oldCloudinary = new Cloudinary(env('CLOUDINARY_VIDEO_URL')); 
// Ensure the new account is loaded for upload
$newCloudinary = new Cloudinary(env('CLOUDINARY_URL'));       

$tables = [
    'skills', 'tool_items', 'intro_slides', 'achievements', 'experiences', 'projects', 'profiles'
];

$urlMap = []; // Cache to prevent re-uploading the same URL multiple times

function processUrl($url, $oldCloudinary, $newCloudinary, $oldCloudName, &$urlMap) {
    if (!is_string($url)) return $url;
    if (!str_contains($url, 'res.cloudinary.com/' . $oldCloudName)) return $url;
    if (str_contains($url, '/video/upload/')) return $url; // Skip videos

    if (isset($urlMap[$url])) {
        return $urlMap[$url];
    }

    echo "Found image: $url\n";
    
    // Download image
    $tempFile = tempnam(sys_get_temp_dir(), 'mig_');
    $imgData = @file_get_contents($url);
    if ($imgData === false) {
        echo "  -> Error: Could not download image (404?). Skipping.\n";
        @unlink($tempFile);
        return $url;
    }
    file_put_contents($tempFile, $imgData);

    $parts = explode('/', $url);
    $publicIdWithExt = end($parts);
    $publicId = explode('.', $publicIdWithExt)[0];
    
    $folderStr = "";
    $uploadIndex = array_search('upload', $parts);
    if ($uploadIndex !== false && count($parts) > $uploadIndex + 2) {
        $folderParts = array_slice($parts, $uploadIndex + 2, count($parts) - ($uploadIndex + 2) - 1);
        $folderStr = implode('/', $folderParts);
    }
    
    try {
        $options = [
            'folder' => $folderStr ?: 'portfolio/migrated',
            'public_id' => $publicId,
            'resource_type' => 'image',
            'use_filename' => true,
            'unique_filename' => false,
            'overwrite' => true,
        ];
        
        $uploaded = $newCloudinary->uploadApi()->upload($tempFile, $options);
        $newUrl = $uploaded['secure_url'];
        
        // Delete from old bucket (Destructive!)
        try {
            $oldCloudinary->uploadApi()->destroy(($folderStr ? $folderStr . '/' : '') . $publicId);
            echo "  -> Deleted from old bucket.\n";
        } catch (\Exception $e) {
            echo "  -> Failed to delete original: " . $e->getMessage() . "\n";
        }
        
        @unlink($tempFile);
        echo "  -> Migrated to: $newUrl\n";
        $urlMap[$url] = $newUrl;
        return $newUrl;
    } catch (\Exception $e) {
        echo "  -> Failed to upload to new bucket: " . $e->getMessage() . "\n";
        @unlink($tempFile);
        return $url;
    }
}

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) continue;
    
    $columns = Schema::getColumnListing($table);
    $records = DB::table($table)->get();
    
    foreach ($records as $record) {
        $updated = false;
        $updateData = [];
        
        foreach ($columns as $column) {
            $val = $record->$column;
            if (empty($val) || !is_string($val)) continue;
            
            // If it's a direct URL
            if (str_starts_with($val, 'http') && str_contains($val, $oldCloudName)) {
                $newVal = processUrl($val, $oldCloudinary, $newCloudinary, $oldCloudName, $urlMap);
                if ($newVal !== $val) {
                    $updateData[$column] = $newVal;
                    $updated = true;
                }
            }
            // If it's JSON (array or object)
            else if (str_contains($val, $oldCloudName) && (str_starts_with($val, '[') || str_starts_with($val, '{'))) {
                // Find all Cloudinary URLs in the JSON string
                $newVal = preg_replace_callback('/"https:\/\/res\.cloudinary\.com\/'.$oldCloudName.'\/image\/upload\/[^"]+"/', function($matches) use ($oldCloudinary, $newCloudinary, $oldCloudName, &$urlMap) {
                    $url = trim($matches[0], '"');
                    $newUrl = processUrl($url, $oldCloudinary, $newCloudinary, $oldCloudName, $urlMap);
                    return '"' . $newUrl . '"';
                }, $val);
                
                if ($newVal !== $val) {
                    $updateData[$column] = $newVal;
                    $updated = true;
                }
            }
        }
        
        if ($updated) {
            DB::table($table)->where('id', $record->id)->update($updateData);
            echo "Updated DB record ID {$record->id} in table '$table'.\n";
        }
    }
}

echo "\nMigration complete!\n";
