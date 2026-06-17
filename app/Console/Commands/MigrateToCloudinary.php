<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigrateToCloudinary extends Command
{
    protected $signature = 'migrate:cloudinary';
    protected $description = 'Migrate all local public storage and public videos to Cloudinary';

    public function handle()
    {
        $this->info('Starting migration to Cloudinary...');
        
        $files = Storage::disk('public')->allFiles();
        
        // Also include public/videos if there are any there
        $publicVideos = [];
        if (File::exists(public_path('videos'))) {
            $filesInVideos = File::allFiles(public_path('videos'));
            foreach ($filesInVideos as $file) {
                $publicVideos[] = [
                    'path' => 'videos/' . $file->getFilename(),
                    'full_path' => $file->getPathname(),
                    'contents' => file_get_contents($file->getPathname())
                ];
            }
        }

        $total = count($files) + count($publicVideos);
        $this->info("Found {$total} files to migrate.");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $success = 0;
        $failed = 0;
        
        // Migrate storage/app/public files
        foreach ($files as $file) {
            try {
                if (!Storage::disk('cloudinary')->exists($file)) {
                    $fullPath = Storage::disk('public')->path($file);
                    Storage::disk('cloudinary')->putFileAs(dirname($file) == '.' ? '' : dirname($file), new \Illuminate\Http\File($fullPath), basename($file));
                }
                $success++;
            } catch (\Exception $e) {
                $this->error("\nFailed to upload: {$file}. Error: " . $e->getMessage());
                $failed++;
            }
            $bar->advance();
        }

        // Migrate public/videos files
        foreach ($publicVideos as $video) {
            try {
                if (!Storage::disk('cloudinary')->exists($video['path'])) {
                    Storage::disk('cloudinary')->putFileAs('videos', new \Illuminate\Http\File($video['full_path']), basename($video['full_path']));
                }
                $success++;
            } catch (\Exception $e) {
                $this->error("\nFailed to upload: {$video['path']}. Error: " . $e->getMessage());
                $failed++;
            }
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("Migration Complete!");
        $this->info("Successfully uploaded: {$success}");
        if ($failed > 0) {
            $this->error("Failed to upload: {$failed}");
        }
    }
}
