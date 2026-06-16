<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateToGoogleDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:gdrive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all local public storage files to Google Drive';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration to Google Drive...');
        
        $files = Storage::disk('public')->allFiles();
        $total = count($files);
        $this->info("Found {$total} files to migrate.");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $success = 0;
        $failed = 0;
        
        foreach ($files as $file) {
            try {
                // Optional: avoid re-uploading if you've already started a migration
                if (!Storage::disk('google')->exists($file)) {
                    $contents = Storage::disk('public')->get($file);
                    Storage::disk('google')->put($file, $contents);
                }
                $success++;
            } catch (\Exception $e) {
                $this->error("\nFailed to upload: {$file}. Error: " . $e->getMessage());
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
