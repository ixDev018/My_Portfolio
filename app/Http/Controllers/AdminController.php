<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\ToolItem;
use App\Models\ContactMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    
    protected function deleteMedia($path) {
        if (empty($path)) return;
        if (Str::startsWith($path, 'http')) {
            $parts = explode('/', $path);
            $publicIdWithExt = end($parts);
            $publicId = explode('.', $publicIdWithExt)[0];
            
            // Extract the cloud name from the URL
            // Cloudinary URL format: https://res.cloudinary.com/<cloud_name>/image/upload/v12345/folder/file.jpg
            $cloudName = count($parts) > 3 ? $parts[3] : '';
            
            try {
                // If the URL cloud name matches the video account, use the video SDK instance
                $videoUrl = env('CLOUDINARY_VIDEO_URL', '');
                if ($videoUrl && str_contains($videoUrl, '@' . $cloudName)) {
                    $cloudinaryVideo = new \Cloudinary\Cloudinary($videoUrl);
                    $cloudinaryVideo->uploadApi()->destroy("portfolio/skills/" . $publicId);
                    $cloudinaryVideo->uploadApi()->destroy("portfolio/projects/" . $publicId);
                    $cloudinaryVideo->uploadApi()->destroy("portfolio/tools/" . $publicId);
                    $cloudinaryVideo->uploadApi()->destroy("portfolio/projects/gallery/" . $publicId);
                    $cloudinaryVideo->uploadApi()->destroy("portfolio/projects/body/" . $publicId);
                } else {
                    // Default fallback to the main account
                    cloudinary()->uploadApi()->destroy("portfolio/skills/" . $publicId);
                    cloudinary()->uploadApi()->destroy("portfolio/projects/" . $publicId);
                    cloudinary()->uploadApi()->destroy("portfolio/tools/" . $publicId);
                    cloudinary()->uploadApi()->destroy("portfolio/projects/gallery/" . $publicId);
                    cloudinary()->uploadApi()->destroy("portfolio/projects/body/" . $publicId);
                }
            } catch (\Exception $e) {
                \Log::error("Cloudinary delete failed: " . $e->getMessage());
            }
            return;
        }
        Storage::disk(config('filesystems.default'))->delete($path);
    }

    protected function uploadMedia($data, $folder) {
        try {
            if ($data instanceof \Illuminate\Http\UploadedFile) {
                $mimeType = $data->getMimeType();
                $extension = strtolower($data->getClientOriginalExtension());

                // Determine resource_type based on file type
                if (in_array($extension, ['mp4', 'mov', 'webm', 'ogg', 'avi']) || str_starts_with($mimeType, 'video/')) {
                    $resourceType = 'video';
                } elseif (in_array($extension, ['doc', 'docx', 'txt', 'zip', 'rar']) || str_contains($folder, 'documents') && $extension !== 'pdf') {
                    $resourceType = 'raw';
                } else {
                    // pdfs and images are uploaded as 'image' resource type so they can be delivered inline
                    $resourceType = 'image';
                }

                $options = [
                    'folder'        => "portfolio/{$folder}",
                    'resource_type' => $resourceType,
                ];

                if ($resourceType === 'raw') {
                    $originalName = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
                    $slugifiedName = \Illuminate\Support\Str::slug($originalName);
                    $options['public_id'] = $slugifiedName . '_' . \Illuminate\Support\Str::random(6) . '.' . $extension;
                } else {
                    $options['use_filename'] = true;
                    $options['unique_filename'] = true;
                }

                if ($resourceType === 'video') {
                    $cloudinaryVideo = new \Cloudinary\Cloudinary(env('CLOUDINARY_VIDEO_URL'));
                    $uploaded = $cloudinaryVideo->uploadApi()->upload($data->getRealPath(), $options);
                } else {
                    $uploaded = cloudinary()->uploadApi()->upload($data->getRealPath(), $options);
                }
                
                return $uploaded['secure_url'];
            } else if (is_string($data) && preg_match('/^data:image\/(\w+);base64,/', $data)) {
                $uploaded = cloudinary()->uploadApi()->upload($data, ['folder' => "portfolio/{$folder}"]);
                return $uploaded['secure_url'];
            } else if (is_string($data) && preg_match('/^data:video\/(\w+);base64,/', $data)) {
                $cloudinaryVideo = new \Cloudinary\Cloudinary(env('CLOUDINARY_VIDEO_URL'));
                $uploaded = $cloudinaryVideo->uploadApi()->upload($data, [
                    'folder' => "portfolio/{$folder}",
                    'resource_type' => 'video',
                ]);
                return $uploaded['secure_url'];
            } else {
                return "";
            }
        } catch (\Exception $e) {
            \Log::error("Cloudinary upload failed: " . $e->getMessage());
            return "";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Auth Handlers
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $envUsername = env('ADMIN_USERNAME', 'admin');
        $envPassword = env('ADMIN_PASSWORD', 'adminpassword');

        if ($request->username === $envUsername && $request->password === $envPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        return redirect()->back()->withErrors(['auth' => 'Invalid username or password credentials.']);
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('portfolio.index')->with('success', 'Logged out successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $projectsCount        = Project::count();
        $skillsCount          = Skill::count();
        $unreadMessagesCount  = ContactMessage::where('is_read', false)->count();
        $readMessagesCount    = ContactMessage::where('is_read', true)->count();
        $totalMessagesCount   = ContactMessage::count();
        $recentMessages       = ContactMessage::orderBy('created_at', 'desc')->take(6)->get();
        $profile              = Profile::first();

        // Most-viewed project — placeholder until view tracking is added
        $mostViewedProject = Project::orderBy('created_at', 'desc')->first();

        // Storage calculations
        $mediaPath = storage_path('app/public');
        $mediaBreakdown = $this->getStorageBreakdown($mediaPath);
        $mediaSizeBytes = array_sum($mediaBreakdown);
        
        $dbConnection = config('database.default');
        $dbSizeBytes = 0;
        if ($dbConnection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (file_exists($dbPath)) {
                $dbSizeBytes = filesize($dbPath);
            }
        }
        
        $totalSizeBytes = $mediaSizeBytes + $dbSizeBytes;
        
        // Define a 1GB limit (standard free tier target warning threshold)
        $limitBytes = 1024 * 1024 * 1024; // 1 GB
        $usagePercent = min(100, max(0.1, round(($totalSizeBytes / $limitBytes) * 100, 2)));

        // Calculate segmented percentages based on limit for the progress bar
        $dbPercent = ($dbSizeBytes / $limitBytes) * 100;
        $imgPercent = ($mediaBreakdown['images'] / $limitBytes) * 100;
        $vidPercent = ($mediaBreakdown['videos'] / $limitBytes) * 100;
        $docPercent = ($mediaBreakdown['documents'] / $limitBytes) * 100;
        $othPercent = ($mediaBreakdown['other'] / $limitBytes) * 100;

        return view('admin.dashboard', compact(
            'projectsCount', 'skillsCount',
            'unreadMessagesCount', 'readMessagesCount', 'totalMessagesCount',
            'recentMessages', 'profile', 'mostViewedProject',
            'mediaSizeBytes', 'dbSizeBytes', 'totalSizeBytes', 'usagePercent',
            'mediaBreakdown', 'dbPercent', 'imgPercent', 'vidPercent', 'docPercent', 'othPercent'
        ));
    }

    private function getStorageBreakdown($path)
    {
        $breakdown = [
            'images' => 0,
            'videos' => 0,
            'documents' => 0,
            'other' => 0,
        ];
        
        $path = realpath($path);
        if ($path !== false && is_dir($path)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $file) {
                if ($file->isFile()) {
                    $size = $file->getSize();
                    $ext = strtolower($file->getExtension());
                    
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp'])) {
                        $breakdown['images'] += $size;
                    } elseif (in_array($ext, ['mp4', 'mov', 'webm', 'ogg', 'avi'])) {
                        $breakdown['videos'] += $size;
                    } elseif (in_array($ext, ['pdf', 'doc', 'docx', 'txt', 'rtf'])) {
                        $breakdown['documents'] += $size;
                    } else {
                        $breakdown['other'] += $size;
                    }
                }
            }
        }
        return $breakdown;
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Settings
    |--------------------------------------------------------------------------
    */
    public function editProfile()
    {
        $profile = Profile::first();
        return view('admin.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $profile = Profile::first();
        if (!$profile) {
            $profile = new Profile();
        }

        $validated = $request->validate([
            'hero_top_text'      => 'nullable|string|max:255',
            'hero_title'         => 'nullable|string|max:255',
            'hero_subtitle'      => 'nullable|string|max:255',
            'github_url'         => 'nullable|url|max:255',
            'linkedin_url'       => 'nullable|url|max:255',
            'twitter_url'        => 'nullable|url|max:255',
            'email'              => 'nullable|email|max:255',
            'location'           => 'nullable|string|max:255',
            'avatar'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv'                 => 'nullable|mimes:pdf|max:5120',
            'hero_video'         => 'nullable|mimes:mp4,mov,webm,ogg|max:102400',
            'hero_blur_amount'          => 'nullable|integer|min:0|max:100',
            'hero_html_content'         => 'nullable|string',
            'hero_gradient_enabled'     => 'nullable',
            'hero_gradient_type'        => 'nullable|in:linear,radial',
            'hero_gradient_angle'       => 'nullable|integer|min:0|max:360',
            'hero_gradient_opacity'     => 'nullable|integer|min:0|max:100',
            'hero_gradient_stops'       => 'nullable|string', // JSON string from frontend
        ]);

        $validated['hero_gradient_enabled'] = $request->has('hero_gradient_enabled');
        if (isset($validated['hero_gradient_stops'])) {
            $validated['hero_gradient_stops'] = json_decode($validated['hero_gradient_stops'], true);
        }

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) $this->deleteMedia($profile->avatar_path);
            $url = $this->uploadMedia($request->file('avatar'), 'profiles/avatars');
            if ($url) $validated['avatar_path'] = $url;
        }

        // Handle CV File Upload
        if ($request->hasFile('cv')) {
            if ($profile->cv_path) $this->deleteMedia($profile->cv_path);
            $url = $this->uploadMedia($request->file('cv'), 'profiles/documents');
            if ($url) $validated['cv_path'] = $url;
        }

        // Handle Hero Video Upload
        if ($request->hasFile('hero_video')) {
            if ($profile->hero_video_path) $this->deleteMedia($profile->hero_video_path);
            $url = $this->uploadMedia($request->file('hero_video'), 'profiles/hero_videos');
            if ($url) $validated['hero_video_path'] = $url;
        }

        // Remove file input keys not in model fillable
        unset($validated['avatar'], $validated['cv'], $validated['hero_video']);

        $profile->fill($validated);
        $profile->save();

        return redirect()->route('admin.profile')->with('success', 'Hero updated successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Settings (Contact, Social, Avatar, CV)
    |--------------------------------------------------------------------------
    */
    public function editProfileSettings()
    {
        $profile = Profile::first();
        return view('admin.profile-settings', compact('profile'));
    }

    public function updateProfileSettings(Request $request)
    {
        $profile = Profile::first();
        if (!$profile) {
            $profile = new Profile();
        }

        $validated = $request->validate([
            'github_url'   => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url'  => 'nullable|url|max:255',
            'email'        => 'nullable|email|max:255',
            'location'     => 'nullable|string|max:255',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv'           => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) $this->deleteMedia($profile->avatar_path);
            $url = $this->uploadMedia($request->file('avatar'), 'profiles/avatars');
            if ($url) $validated['avatar_path'] = $url;
        }

        if ($request->hasFile('cv')) {
            if ($profile->cv_path) $this->deleteMedia($profile->cv_path);
            $url = $this->uploadMedia($request->file('cv'), 'profiles/documents');
            if ($url) $validated['cv_path'] = $url;
        }

        unset($validated['avatar'], $validated['cv']);

        $profile->fill($validated);
        $profile->save();

        return redirect()->route('admin.profile_settings')->with('success', 'Profile settings saved!');
    }

    /*
    |--------------------------------------------------------------------------
    | Projects CRUD
    |--------------------------------------------------------------------------
    */
    public function projectsIndex()
    {
        $projects = Project::where('is_archived', false)->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function projectsCreate()
    {
        return view('admin.projects.create');
    }

    public function projectsStore(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'category'            => 'nullable|string|max:100',
            'client'              => 'nullable|string|max:255',
            'role'                => 'nullable|string|max:255',
            'year'                => 'nullable|string|max:100',
            'date_published'      => 'nullable|string|max:100',
            'medium'              => 'nullable|string|max:255',
            'collaborators'       => 'nullable|string',
            'body_content'        => 'nullable|string',
            'tags'                => 'nullable|string',
            'demo_url'            => 'nullable|url',
            'github_url'          => 'nullable|url',
            'video_url'           => 'nullable|url',
            'full_video_url'      => 'nullable|url',
            'embed_url'           => 'nullable|string|max:500',
            'featured'            => 'nullable|boolean',
            'is_best_work'        => 'nullable|boolean',
            'is_archived'         => 'nullable|boolean',
            'is_top'              => 'nullable|boolean',
            'main_media_type'     => 'nullable|string|in:image,video',
            'main_media_upload.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
            'video_loop_start'    => 'nullable|numeric|min:0',
            'video_loop_end'      => 'nullable|numeric|min:0',
            'main_media_base64'   => 'nullable|string',
            'use_custom_thumbnail'=> 'nullable|boolean',
            'custom_thumbnail_base64' => 'nullable|string',
            'featured_thumbnail_base64' => 'nullable|string',
            'gallery.*'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['featured'] = $request->input('featured') == '1';
        $validated['is_best_work'] = $request->input('is_best_work') == '1';
        $validated['is_archived'] = $request->input('is_archived') == '1';
        $validated['is_top'] = $request->input('is_top') == '1';
        $validated['show_story'] = $request->input('show_story', '1') == '1';
        $validated['coming_soon_gallery_ratio'] = $request->input('coming_soon_gallery_ratio', '16:9');
        $validated['coming_soon_gallery'] = json_decode($request->input('coming_soon_gallery_json'), true) ?: [];
        $validated['main_media_type'] = $request->input('main_media_type', 'image');
        $validated['use_custom_thumbnail'] = $request->input('use_custom_thumbnail') == '1';
        
        // Add description mapping since frontend removed the field
        $validated['description']  = $validated['subtitle'] ?? '';

        // Main Media Processing
        if ($validated['main_media_type'] === 'image' && $request->input('main_media_base64')) {
            $base64 = $request->input('main_media_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $validated['main_image_path'] = $this->uploadMedia($base64, 'projects/main_images');
                $validated['main_images'] = null;
            }
        } elseif ($request->hasFile('main_media_upload')) {
            $files = $request->file('main_media_upload');
            if ($validated['main_media_type'] === 'video') {
                $validated['main_video_path'] = $this->uploadMedia($files[0], 'projects/main_videos');
            } else {
                if (count($files) === 1) {
                    $validated['main_image_path'] = $this->uploadMedia($files[0], 'projects/main_images');
                    $validated['main_images'] = null;
                } else {
                    $paths = [];
                    foreach ($files as $f) {
                        $paths[] = $this->uploadMedia($f, 'projects/main_images');
                    }
                    $validated['main_images'] = $paths;
                    $validated['main_image_path'] = $paths[0]; // fallback cover
                }
            }
        }

        // Custom Thumbnail Processing (Cropper Base64)
        if ($validated['use_custom_thumbnail'] && $request->input('custom_thumbnail_base64')) {
            $base64 = $request->input('custom_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $validated['thumbnail_path'] = $this->uploadMedia($base64, 'projects/thumbnails');
            }
        }

        // Featured Thumbnail Processing (Cropper Base64)
        if ($request->input('featured_thumbnail_base64')) {
            $base64 = $request->input('featured_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $validated['featured_thumbnail'] = $this->uploadMedia($base64, 'projects/featured_thumbnails');
            }
        }

        $currentGallery = [];

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $url = $this->uploadMedia($image, 'projects/gallery');
                if ($url) $currentGallery[] = $url;
            }
        }

        $validated['gallery_images'] = $currentGallery;

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }

    public function projectsEdit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function projectsUpdate(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'category'            => 'nullable|string|max:100',
            'client'              => 'nullable|string|max:255',
            'role'                => 'nullable|string|max:255',
            'year'                => 'nullable|string|max:100',
            'date_published'      => 'nullable|string|max:100',
            'medium'              => 'nullable|string|max:255',
            'collaborators'       => 'nullable|string',
            'body_content'        => 'nullable|string',
            'tags'                => 'nullable|string',
            'demo_url'            => 'nullable|url',
            'github_url'          => 'nullable|url',
            'video_url'           => 'nullable|url',
            'full_video_url'      => 'nullable|url',
            'embed_url'           => 'nullable|string|max:500',
            'featured'            => 'nullable|boolean',
            'is_best_work'        => 'nullable|boolean',
            'is_archived'         => 'nullable|boolean',
            'is_top'              => 'nullable|boolean',
            'main_media_type'     => 'nullable|string|in:image,video',
            'main_media_upload.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
            'video_loop_start'    => 'nullable|numeric|min:0',
            'video_loop_end'      => 'nullable|numeric|min:0',
            'main_media_base64'   => 'nullable|string',
            'use_custom_thumbnail'=> 'nullable|boolean',
            'custom_thumbnail_base64' => 'nullable|string',
            'featured_thumbnail_base64' => 'nullable|string',
            'gallery.*'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['featured'] = $request->input('featured') == '1';
        $validated['is_best_work'] = $request->input('is_best_work') == '1';
        $validated['is_archived'] = $request->input('is_archived') == '1';
        $validated['is_top'] = $request->input('is_top') == '1';
        $validated['show_story'] = $request->input('show_story', '1') == '1';
        $validated['coming_soon_gallery_ratio'] = $request->input('coming_soon_gallery_ratio', '16:9');
        $validated['coming_soon_gallery'] = json_decode($request->input('coming_soon_gallery_json'), true) ?: [];
        $validated['main_media_type'] = $request->input('main_media_type', 'image');
        $validated['use_custom_thumbnail'] = $request->input('use_custom_thumbnail') == '1';
        
        // Add description mapping since frontend removed the field
        $validated['description']  = $validated['subtitle'] ?? '';

        // Main Media Processing
        if ($validated['main_media_type'] === 'image' && $request->input('main_media_base64')) {
            $base64 = $request->input('main_media_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                if ($project->main_image_path && !Str::startsWith($project->main_image_path, 'http')) { $this->deleteMedia($project->main_image_path); }
                if ($project->main_images) {
                    foreach ($project->main_images as $oldImg) {
                        if (!Str::startsWith($oldImg, 'http')) { $this->deleteMedia($oldImg); }
                    }
                }
                $validated['main_image_path'] = $this->uploadMedia($base64, 'projects/main_images');
                $validated['main_images'] = null;
            }
        } elseif ($request->hasFile('main_media_upload')) {
            $files = $request->file('main_media_upload');
            if ($validated['main_media_type'] === 'video') {
                if ($project->main_video_path && !Str::startsWith($project->main_video_path, 'http')) { $this->deleteMedia($project->main_video_path); }
                $validated['main_video_path'] = $this->uploadMedia($files[0], 'projects/main_videos');
            } else {
                if ($project->main_image_path && !Str::startsWith($project->main_image_path, 'http')) { $this->deleteMedia($project->main_image_path); }
                if ($project->main_images) {
                    foreach ($project->main_images as $oldImg) {
                        if (!Str::startsWith($oldImg, 'http')) { $this->deleteMedia($oldImg); }
                    }
                }
                
                if (count($files) === 1) {
                    $validated['main_image_path'] = $this->uploadMedia($files[0], 'projects/main_images');
                    $validated['main_images'] = null;
                } else {
                    $paths = [];
                    foreach ($files as $f) {
                        $paths[] = $this->uploadMedia($f, 'projects/main_images');
                    }
                    $validated['main_images'] = $paths;
                    $validated['main_image_path'] = $paths[0]; // fallback cover
                }
            }
        }

        // Custom Thumbnail Processing (Cropper Base64)
        if ($validated['use_custom_thumbnail'] && $request->input('custom_thumbnail_base64')) {
            $base64 = $request->input('custom_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                if ($project->thumbnail_path && !Str::startsWith($project->thumbnail_path, 'http')) { $this->deleteMedia($project->thumbnail_path); }
                $validated['thumbnail_path'] = $this->uploadMedia($base64, 'projects/thumbnails');
            }
        }

        // Featured Thumbnail Processing (Cropper Base64)
        if ($request->input('featured_thumbnail_base64')) {
            $base64 = $request->input('featured_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                if ($project->featured_thumbnail && !Str::startsWith($project->featured_thumbnail, 'http')) { $this->deleteMedia($project->featured_thumbnail); }
                $validated['featured_thumbnail'] = $this->uploadMedia($base64, 'projects/featured_thumbnails');
            }
        }

        $currentGallery = $project->gallery_images ?? [];

        if ($request->has('delete_gallery')) {
            foreach ($request->input('delete_gallery') as $indexToDelete) {
                if (isset($currentGallery[$indexToDelete])) {
                    if (!Str::startsWith($currentGallery[$indexToDelete], 'http')) { $this->deleteMedia($currentGallery[$indexToDelete]); }
                    unset($currentGallery[$indexToDelete]);
                }
            }
            $currentGallery = array_values($currentGallery);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $url = $this->uploadMedia($image, 'projects/gallery');
                if ($url) $currentGallery[] = $url;
            }
        }

        $validated['gallery_images'] = $currentGallery;

        $project->update($validated);

        return redirect()->back()->with('success', 'Project updated successfully!');
    }

    public function projectsDestroy($id)
    {
        $project = Project::findOrFail($id);
        if ($project->thumbnail_path) {
            if (!Str::startsWith($project->thumbnail_path, 'http')) { $this->deleteMedia($project->thumbnail_path); }
        }
        if ($project->thumbnail_video_path) {
            if (!Str::startsWith($project->thumbnail_video_path, 'http')) { $this->deleteMedia($project->thumbnail_video_path); }
        }
        if (is_array($project->gallery_images)) {
            foreach ($project->gallery_images as $image) {
                if (!Str::startsWith($image, 'http')) { $this->deleteMedia($image); }
            }
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function projectsBulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|string']);
        $ids = explode(',', $request->ids);

        $projects = Project::whereIn('id', $ids)->get();

        foreach ($projects as $project) {
            if ($project->thumbnail_path) {
                if (!Str::startsWith($project->thumbnail_path, 'http')) { $this->deleteMedia($project->thumbnail_path); }
            }
            if ($project->thumbnail_video_path) {
                if (!Str::startsWith($project->thumbnail_video_path, 'http')) { $this->deleteMedia($project->thumbnail_video_path); }
            }
            if (is_array($project->gallery_images)) {
                foreach ($project->gallery_images as $image) {
                    if (!Str::startsWith($image, 'http')) { $this->deleteMedia($image); }
                }
            }
            $project->delete();
        }

        return redirect()->route('admin.projects.index')->with('success', 'Selected projects deleted successfully.');
    }

    public function projectsArchiveIndex()
    {
        $projects = Project::where('is_archived', true)->orderBy('created_at', 'desc')->get();
        return view('admin.projects.archive', compact('projects'));
    }

    public function projectsArchiveSingle(Request $request)
    {
        $request->validate(['project_id' => 'required|exists:projects,id']);
        $project = Project::findOrFail($request->project_id);
        $project->is_archived = true;
        $project->save();
        return redirect()->back()->with('success', 'Project archived successfully.');
    }

    public function projectsRestoreSingle(Request $request)
    {
        $request->validate(['project_id' => 'required|exists:projects,id']);
        $project = Project::findOrFail($request->project_id);
        $project->is_archived = false;
        $project->save();
        return redirect()->back()->with('success', 'Project restored successfully.');
    }

    public function projectsBulkArchive(Request $request)
    {
        $request->validate(['ids' => 'required|string']);
        $ids = explode(',', $request->ids);
        Project::whereIn('id', $ids)->update(['is_archived' => true]);
        return redirect()->back()->with('success', 'Selected projects archived successfully.');
    }

    public function projectsBulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|string']);
        $ids = explode(',', $request->ids);
        Project::whereIn('id', $ids)->update(['is_archived' => false]);
        return redirect()->back()->with('success', 'Selected projects restored successfully.');
    }

    public function projectsReorder(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'integer|exists:projects,id'
        ]);

        foreach ($request->ordered_ids as $index => $id) {
            Project::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle inline media uploads from the TipTap body editor.
     * Returns JSON { url } for the editor to embed.
     */
    public function uploadBodyMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
        ]);

        $url = $this->uploadMedia($request->file('file'), 'projects/body');
        return response()->json(['url' => $url]);
    }

    /*
    |--------------------------------------------------------------------------
    | Skills CRUD (Inline List Management)
    |--------------------------------------------------------------------------
    */
    public function skillsIndex()
    {
        $skills = Skill::orderBy('category')->orderBy('id', 'asc')->get();
        $toolItems = \App\Models\ToolItem::orderBy('row_label')->orderBy('sort_order')->get();
        $groupedTools = $toolItems->groupBy('row_label');
        $rowLabels = $toolItems->pluck('row_label')->unique()->values();
        return view('admin.skills.index', compact('skills', 'toolItems', 'groupedTools', 'rowLabels'));
    }

    public function skillsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'tooltip_info' => 'nullable|string|max:255',
            'category' => 'required|string|in:Core,External',
            'proficiency' => 'required|integer|min:1|max:5',
            'image_data'   => 'nullable|string',
        ]);

        $imagePath = null;
        if (!empty($validated['image_data'])) {
            $imageParts = explode(";base64,", $validated['image_data']);
            if (count($imageParts) == 2) {
                $imagePath = $this->uploadMedia($validated['image_data'], 'skills');
            }
        }

        Skill::create([
            'name' => $validated['name'],
            'tooltip_info' => $validated['tooltip_info'] ?? null,
            'category' => $validated['category'],
            'proficiency' => $validated['proficiency'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill added successfully!');
    }

    public function skillsUpdate(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'tooltip_info' => 'nullable|string|max:255',
            'category' => 'required|string|in:Core,External',
            'proficiency' => 'required|integer|min:1|max:5',
            'image_data'   => 'nullable|string',
        ]);

        if (!empty($validated['image_data'])) {
            $imageParts = explode(";base64,", $validated['image_data']);
            if (count($imageParts) == 2) {
                if ($skill->image_path) {
                    $this->deleteMedia($skill->image_path);
                }
                $skill->image_path = $this->uploadMedia($validated['image_data'], 'skills');
            }
        }

        $skill->name = $validated['name'];
        $skill->tooltip_info = $validated['tooltip_info'] ?? null;
        $skill->category = $validated['category'];
        $skill->proficiency = $validated['proficiency'];
        $skill->save();

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully!');
    }

    public function skillsDestroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Inbox / Messages Management
    |--------------------------------------------------------------------------
    */
    public function messagesIndex()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        return view('admin.messages.index', compact('messages'));
    }

    public function messagesShow($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);
        return view('admin.messages.show', compact('message'));
    }

    public function messagesDestroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Tool Items CRUD (Marquee Section)
    |--------------------------------------------------------------------------
    */
    public function toolItemsIndex()
    {
        $toolItems = ToolItem::orderBy('row_label')->orderBy('sort_order')->get();
        $grouped = $toolItems->groupBy('row_label');
        $rowLabels = $toolItems->pluck('row_label')->unique()->values();
        return view('admin.tools.index', compact('toolItems', 'grouped', 'rowLabels'));
    }

    public function toolItemsStore(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'tooltip_info' => 'nullable|string|max:255',
            'row_label'    => 'required|string|max:100',
            'proficiency'  => 'required|integer|min:1|max:5',
            'image_data'   => 'nullable|string', // base64 from CropperJS
        ]);

        $imagePath = null;
        if (!empty($validated['image_data'])) {
            // Strip the data URI header, decode and save as PNG
            $imagePath = $this->uploadMedia($validated['image_data'], 'tools');
        }

        $maxOrder = ToolItem::where('row_label', $validated['row_label'])->max('sort_order') ?? -1;

        ToolItem::create([
            'name'         => $validated['name'],
            'tooltip_info' => $validated['tooltip_info'] ?? null,
            'row_label'    => $validated['row_label'],
            'proficiency'  => $validated['proficiency'],
            'image_path'   => $imagePath,
            'sort_order'   => $maxOrder + 1,
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Tool item added!');
    }

    public function toolItemsDestroy($id)
    {
        $tool = ToolItem::findOrFail($id);
        if ($tool->image_path) {
            if (!Str::startsWith($tool->image_path, 'http')) { $this->deleteMedia($tool->image_path); }
        }
        $tool->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Tool item removed.');
    }

    public function toolItemsUpdate(Request $request, $id)
    {
        $tool = ToolItem::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'tooltip_info' => 'nullable|string|max:255',
            'row_label'    => 'required|string|max:100',
            'proficiency'  => 'required|integer|min:1|max:5',
            'image_data'   => 'nullable|string', // base64 from CropperJS
        ]);

        if (!empty($validated['image_data'])) {
            // Delete old image if exists
            if ($tool->image_path) {
                if (!Str::startsWith($tool->image_path, 'http')) { $this->deleteMedia($tool->image_path); }
            }
            
            // Strip the data URI header, decode and save as PNG
            $tool->image_path = $this->uploadMedia($validated['image_data'], 'tools');
        }

        $tool->name = $validated['name'];
        $tool->tooltip_info = $validated['tooltip_info'] ?? null;
        $tool->row_label = $validated['row_label'];
        $tool->proficiency = $validated['proficiency'];
        $tool->save();

        return redirect()->route('admin.skills.index')->with('success', 'Tool item updated!');
    }

    public function toolItemsRenameRow(Request $request)
    {
        $request->validate([
            'old_label' => 'required|string|max:100',
            'new_label' => 'required|string|max:100',
        ]);

        ToolItem::where('row_label', $request->old_label)->update(['row_label' => $request->new_label]);

        return redirect()->route('admin.skills.index')->with('success', 'Marquee row renamed successfully!');
    }
}
