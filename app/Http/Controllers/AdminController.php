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
        $projectsCount = Project::count();
        $skillsCount = Skill::count();
        $unreadMessagesCount = ContactMessage::where('is_read', false)->count();
        
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('projectsCount', 'skillsCount', 'unreadMessagesCount', 'recentMessages'));
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
            'hero_top_text'   => 'nullable|string|max:255',
            'hero_title'      => 'nullable|string|max:255',
            'hero_subtitle'   => 'nullable|string|max:255',
            'github_url'      => 'nullable|url|max:255',
            'linkedin_url'    => 'nullable|url|max:255',
            'twitter_url'     => 'nullable|url|max:255',
            'email'           => 'nullable|email|max:255',
            'location'        => 'nullable|string|max:255',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv'              => 'nullable|mimes:pdf|max:5120',
            'hero_video'      => 'nullable|mimes:mp4,mov,webm,ogg|max:102400',
        ]);

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

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'client' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:100',
            'medium' => 'nullable|string|max:255',
            'collaborators' => 'nullable|string',
            'description' => 'required|string',
            'body_content' => 'nullable|string',
            'tags' => 'nullable|string',
            'demo_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['featured'] = $request->has('featured');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('projects', 'public');
        }
        
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'client' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:100',
            'medium' => 'nullable|string|max:255',
            'collaborators' => 'nullable|string',
            'description' => 'required|string',
            'body_content' => 'nullable|string',
            'tags' => 'nullable|string',
            'demo_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'delete_gallery' => 'nullable|array'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['featured'] = $request->has('featured');

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail_path) {
                Storage::disk('public')->delete($project->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $currentGallery = $project->gallery_images ?? [];
        
        // Handle deletions of existing gallery images
        if ($request->has('delete_gallery')) {
            foreach ($request->input('delete_gallery') as $indexToDelete) {
                if (isset($currentGallery[$indexToDelete])) {
                    Storage::disk('public')->delete($currentGallery[$indexToDelete]);
                    unset($currentGallery[$indexToDelete]);
                }
            }
            // Reindex array
            $currentGallery = array_values($currentGallery);
        }

        // Add new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $currentGallery[] = $image->store('projects/gallery', 'public');
            }
        }
        
        $validated['gallery_images'] = $currentGallery;

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    public function projectsDestroy($id)
    {
        $project = Project::findOrFail($id);
        if ($project->thumbnail_path) {
            Storage::disk('public')->delete($project->thumbnail_path);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Skills CRUD (Inline List Management)
    |--------------------------------------------------------------------------
    */
    public function skillsIndex()
    {
        $skills = Skill::orderBy('category')->orderBy('proficiency', 'desc')->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function skillsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|in:Frontend,Backend,Tools',
            'proficiency' => 'required|integer|min:0|max:100',
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skills.index')->with('success', 'Skill added successfully!');
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
            'name'       => 'required|string|max:100',
            'row_label'  => 'required|string|max:100',
            'image_data' => 'nullable|string', // base64 from CropperJS
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
            'name'       => $validated['name'],
            'row_label'  => $validated['row_label'],
            'image_path' => $imagePath,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Tool item added!');
    }

    public function toolItemsDestroy($id)
    {
        $tool = ToolItem::findOrFail($id);
        if ($tool->image_path) {
            Storage::disk('public')->delete($tool->image_path);
        }
        $tool->delete();

        return redirect()->route('admin.tools.index')->with('success', 'Tool item removed.');
    }
}
