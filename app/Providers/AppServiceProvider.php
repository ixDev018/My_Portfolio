<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            \Illuminate\Support\Facades\Storage::extend('google', function($app, $config) {
                $options = [];

                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                if (!empty($config['sharedFolderId'] ?? null)) {
                    $options['sharedFolderId'] = $config['sharedFolderId'];
                }

                $client = new \Google\Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);
                
                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new class($driver, $adapter) extends \Illuminate\Filesystem\FilesystemAdapter {
                    public function url($path)
                    {
                        if (empty($path)) return '';
                        if (str_starts_with($path, 'http')) return $path;
                        
                        if (file_exists(public_path($path))) {
                            return asset($path);
                        }

                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                        }

                        // Get direct Google Drive URL to bypass our server and save RAM!
                        try {
                            return \Illuminate\Support\Facades\Cache::remember('gdrive_url_' . md5($path), 86400, function() use ($path) {
                                try {
                                    $adapter = \Illuminate\Support\Facades\Storage::disk('google')->getAdapter();
                                    $meta = $adapter->getMetadata($path);
                                    if ($meta && is_callable([$meta, 'extraMetadata'])) {
                                        $extra = $meta->extraMetadata();
                                        if (isset($extra['id'])) {
                                            return 'https://drive.google.com/uc?id=' . $extra['id'];
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Ignore API errors during cache generation
                                }
                                return route('media.serve', ['path' => $path]);
                            });
                        } catch (\Exception $e) {
                            return route('media.serve', ['path' => $path]);
                        }
                    }
                };
            });
        } catch(\Exception $e) {
            // fail silently or log
        }
    }
}
