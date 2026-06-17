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

                        // Return a fast local route to prevent blocking the HTML page load!
                        return route('media.serve', ['path' => $path]);
                    }
                };
            });
        } catch(\Exception $e) {
            // fail silently or log
        }

        try {
            \Illuminate\Support\Facades\Storage::extend('cloudinary', function ($app, $config) {
                if (isset($config['url'])) {
                    $cloudinary = new \Cloudinary\Cloudinary($config['url']);
                } else {
                    $cloudinary = new \Cloudinary\Cloudinary([
                        'cloud' => [
                            'cloud_name' => $config['cloud'] ?? null,
                            'api_key' => $config['key'] ?? null,
                            'api_secret' => $config['secret'] ?? null,
                        ],
                        'url' => [
                            'secure' => $config['secure'] ?? true,
                        ],
                    ]);
                }

                $adapter = new \CloudinaryLabs\CloudinaryLaravel\CloudinaryStorageAdapter($cloudinary, null, $config['prefix'] ?? null);

                return new class(new \League\Flysystem\Filesystem($adapter, $config), $adapter, $config) extends \Illuminate\Filesystem\FilesystemAdapter {
                    public function url($path)
                    {
                        if (empty($path)) return '';
                        if (str_starts_with($path, 'http')) return $path;
                        
                        try {
                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            if (in_array($extension, ['mp4', 'mov', 'webm', 'ogg', 'avi']) || str_contains($path, '/hero_videos/')) {
                                return (string) cloudinary()->video($path);
                            } elseif (in_array($extension, ['doc', 'docx', 'txt', 'zip', 'rar']) || str_contains($path, '/documents/') && $extension !== 'pdf') {
                                return (string) cloudinary()->raw($path);
                            } else {
                                return (string) cloudinary()->image($path);
                            }
                        } catch (\Throwable $e) {
                            return '';
                        }
                    }
                };
            });
        } catch (\Exception $e) {
            // fail silently or log
        }
    }
}
