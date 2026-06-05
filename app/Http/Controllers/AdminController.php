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

        return view('admin.dashboard', compact(
            'projectsCount', 'skillsCount',
            'unreadMessagesCount', 'readMessagesCount', 'totalMessagesCount',
            'recentMessages', 'profile', 'mostViewedProject'
        ));
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
            if ($profile->avatar_path) {
                Storage::disk('public')->delete($profile->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle CV File Upload
        if ($request->hasFile('cv')) {
            if ($profile->cv_path) {
                Storage::disk('public')->delete($profile->cv_path);
            }
            $validated['cv_path'] = $request->file('cv')->store('documents', 'public');
        }

        // Handle Hero Video Upload
        if ($request->hasFile('hero_video')) {
            if ($profile->hero_video_path) {
                Storage::disk('public')->delete($profile->hero_video_path);
            }
            $validated['hero_video_path'] = $request->file('hero_video')->store('videos', 'public');
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
            if ($profile->avatar_path) {
                Storage::disk('public')->delete($profile->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('cv')) {
            if ($profile->cv_path) {
                Storage::disk('public')->delete($profile->cv_path);
            }
            $validated['cv_path'] = $request->file('cv')->store('documents', 'public');
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
        $projects = Project::orderBy('created_at', 'desc')->get();
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
            'featured'            => 'nullable|boolean',
            'is_best_work'        => 'nullable|boolean',
            'main_media_type'     => 'nullable|string|in:image,video',
            'main_media_upload.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
            'video_loop_start'    => 'nullable|numeric|min:0',
            'video_loop_end'      => 'nullable|numeric|min:0',
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
        $validated['main_media_type'] = $request->input('main_media_type', 'image');
        $validated['use_custom_thumbnail'] = $request->input('use_custom_thumbnail') == '1';
        
        // Add description mapping since frontend removed the field
        $validated['description']  = $validated['subtitle'] ?? '';

        // Main Media Processing
        if ($request->hasFile('main_media_upload')) {
            $files = $request->file('main_media_upload');
            if ($validated['main_media_type'] === 'video') {
                $validated['main_video_path'] = $files[0]->store('projects/main_videos', 'public');
            } else {
                if (count($files) === 1) {
                    $validated['main_image_path'] = $files[0]->store('projects/main_images', 'public');
                    $validated['main_images'] = null;
                } else {
                    $paths = [];
                    foreach ($files as $f) {
                        $paths[] = $f->store('projects/main_images', 'public');
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
                $data = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc
                $data = base64_decode($data);
                $filename = 'projects/thumbnails/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($filename, $data);
                $validated['thumbnail_path'] = $filename;
                $validated['thumbnail_type'] = 'image';
            }
        }

        // Featured Thumbnail Processing (Cropper Base64)
        if ($validated['featured'] && $request->input('featured_thumbnail_base64')) {
            $base64 = $request->input('featured_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $data = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc
                $data = base64_decode($data);
                $filename = 'projects/featured_thumbnails/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($filename, $data);
                $validated['featured_thumbnail'] = $filename;
            }
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $project = Project::create($validated);

        return redirect()->route('admin.projects.edit', $project->id)->with('success', 'Project created successfully!');
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
            'featured'            => 'nullable|boolean',
            'is_best_work'        => 'nullable|boolean',
            'main_media_type'     => 'nullable|string|in:image,video',
            'main_media_upload.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
            'video_loop_start'    => 'nullable|numeric|min:0',
            'video_loop_end'      => 'nullable|numeric|min:0',
            'use_custom_thumbnail'=> 'nullable|boolean',
            'custom_thumbnail_base64' => 'nullable|string',
            'featured_thumbnail_base64' => 'nullable|string',
            'gallery.*'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'delete_gallery'      => 'nullable|array',
        ]);

        $validated['slug']           = Str::slug($validated['title']);
        $validated['featured']       = $request->input('featured') == '1';
        $validated['is_best_work']   = $request->input('is_best_work') == '1';
        
        $validated['main_media_type'] = $request->input('main_media_type', $project->main_media_type ?? 'image');
        $validated['use_custom_thumbnail'] = $request->input('use_custom_thumbnail') == '1';
        
        // Add description mapping since frontend removed the field
        $validated['description']    = $validated['subtitle'] ?? '';

        // Featured Thumbnail Deletion
        if ($request->input('remove_featured_thumbnail') == '1' || !$validated['is_best_work']) {
            if ($project->featured_thumbnail) {
                Storage::disk('public')->delete($project->featured_thumbnail);
            }
            $validated['featured_thumbnail'] = null;
        }

        // Custom Thumbnail Deletion
        if ($request->input('remove_thumbnail') == '1') {
            if ($project->thumbnail_video_path) Storage::disk('public')->delete($project->thumbnail_video_path);
            if ($project->thumbnail_path) Storage::disk('public')->delete($project->thumbnail_path);
            if ($project->thumbnail_images) {
                foreach ($project->thumbnail_images as $oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $validated['thumbnail_video_path'] = null;
            $validated['thumbnail_path'] = null;
            $validated['thumbnail_images'] = null;
            $validated['thumbnail_type'] = 'image';
            $validated['use_custom_thumbnail'] = false;
        }

        // Main Media Processing
        if ($request->hasFile('main_media_upload')) {
            $files = $request->file('main_media_upload');
            if ($validated['main_media_type'] === 'video') {
                if ($project->main_video_path) {
                    Storage::disk('public')->delete($project->main_video_path);
                }
                $validated['main_video_path'] = $files[0]->store('projects/main_videos', 'public');
            } else {
                if ($project->main_image_path) {
                    Storage::disk('public')->delete($project->main_image_path);
                }
                if ($project->main_images) {
                    foreach ($project->main_images as $oldImg) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
                
                if (count($files) === 1) {
                    $validated['main_image_path'] = $files[0]->store('projects/main_images', 'public');
                    $validated['main_images'] = null;
                } else {
                    $paths = [];
                    foreach ($files as $f) {
                        $paths[] = $f->store('projects/main_images', 'public');
                    }
                    $validated['main_images'] = $paths;
                    $validated['main_image_path'] = $paths[0];
                }
            }
        }

        // Custom Thumbnail Processing (Cropper Base64)
        if ($validated['use_custom_thumbnail'] && $request->input('custom_thumbnail_base64')) {
            $base64 = $request->input('custom_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                
                // delete old custom thumbnail if it exists
                if ($project->thumbnail_path) Storage::disk('public')->delete($project->thumbnail_path);
                
                $data = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc
                $data = base64_decode($data);
                $filename = 'projects/thumbnails/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($filename, $data);
                $validated['thumbnail_path'] = $filename;
                $validated['thumbnail_type'] = 'image';
            }
        }

        // Featured Thumbnail Processing (Cropper Base64)
        if ($validated['featured'] && $request->input('featured_thumbnail_base64')) {
            $base64 = $request->input('featured_thumbnail_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                
                // delete old featured thumbnail if it exists
                if ($project->featured_thumbnail) Storage::disk('public')->delete($project->featured_thumbnail);
                
                $data = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc
                $data = base64_decode($data);
                $filename = 'projects/featured_thumbnails/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($filename, $data);
                $validated['featured_thumbnail'] = $filename;
            }
        }

        $currentGallery = $project->gallery_images ?? [];

        if ($request->has('delete_gallery')) {
            foreach ($request->input('delete_gallery') as $indexToDelete) {
                if (isset($currentGallery[$indexToDelete])) {
                    Storage::disk('public')->delete($currentGallery[$indexToDelete]);
                    unset($currentGallery[$indexToDelete]);
                }
            }
            $currentGallery = array_values($currentGallery);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $currentGallery[] = $image->store('projects/gallery', 'public');
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
            Storage::disk('public')->delete($project->thumbnail_path);
        }
        if ($project->thumbnail_video_path) {
            Storage::disk('public')->delete($project->thumbnail_video_path);
        }
        if (is_array($project->gallery_images)) {
            foreach ($project->gallery_images as $image) {
                Storage::disk('public')->delete($image);
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
                Storage::disk('public')->delete($project->thumbnail_path);
            }
            if ($project->thumbnail_video_path) {
                Storage::disk('public')->delete($project->thumbnail_video_path);
            }
            if (is_array($project->gallery_images)) {
                foreach ($project->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $project->delete();
        }

        return redirect()->route('admin.projects.index')->with('success', 'Selected projects deleted successfully.');
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

        $path = $request->file('file')->store('projects/body', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
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
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $imageBase64 = base64_decode($imageParts[1]);
                $fileName = 'skill_' . time() . '_' . uniqid() . '.png';
                $imagePath = 'skills/' . $fileName;
                Storage::disk('public')->put($imagePath, $imageBase64);
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
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $imageBase64 = base64_decode($imageParts[1]);
                $fileName = 'skill_' . time() . '_' . uniqid() . '.png';
                $imagePath = 'skills/' . $fileName;
                Storage::disk('public')->put($imagePath, $imageBase64);
                
                if ($skill->image_path) {
                    Storage::disk('public')->delete($skill->image_path);
                }
                $skill->image_path = $imagePath;
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
            $data = preg_replace('/^data:image\/\w+;base64,/', '', $validated['image_data']);
            $data = base64_decode($data);
            $filename = 'tools/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, $data);
            $imagePath = $filename;
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
            Storage::disk('public')->delete($tool->image_path);
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
                Storage::disk('public')->delete($tool->image_path);
            }
            
            // Strip the data URI header, decode and save as PNG
            $data = preg_replace('/^data:image\/\w+;base64,/', '', $validated['image_data']);
            $data = base64_decode($data);
            $filename = 'tools/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, $data);
            $tool->image_path = $filename;
        }

        $tool->name = $validated['name'];
        $tool->tooltip_info = $validated['tooltip_info'] ?? null;
        $tool->row_label = $validated['row_label'];
        $tool->proficiency = $validated['proficiency'];
        $tool->save();

        return redirect()->route('admin.skills.index')->with('success', 'Tool item updated!');
    }
}
