<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('sort_order')->orderByDesc('created_at')->get();
        $profile = Profile::first();
        return view('admin.experiences.index', compact('experiences', 'profile'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company'           => 'required|string|max:255',
            'role'              => 'required|string|max:255',
            'duration'          => 'required|string|max:100',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'is_active'         => 'boolean',
            'body_content'      => 'nullable|string', // JSON from Editor
            'bg_media_type'     => 'nullable|string|in:image,video,slideshow',
            'bg_media_file'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm|max:102400',
            'bg_gallery_files.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['sort_order'] = Experience::max('sort_order') + 1;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['bg_media_type'] = $validated['bg_media_type'] ?? 'image';

        if (!empty($validated['body_content'])) {
            $validated['body_content'] = json_decode($validated['body_content'], true);
        }

        if ($request->filled('image_base64')) {
            $data = $request->input('image_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $ext = strtolower($type[1]);
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        $filename = 'experiences/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, $data);
                        $validated['image_path'] = $filename;
                    }
                }
            }
        } elseif ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('experiences', 'public');
        }

        // Handle Background Media
        if ($request->filled('bg_media_base64') && $validated['bg_media_type'] === 'image') {
            $data = $request->input('bg_media_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $ext = strtolower($type[1]);
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        $filename = 'experiences/bg/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, $data);
                        $validated['bg_media_path'] = $filename;
                    }
                }
            }
        } elseif ($request->hasFile('bg_media_file')) {
            $validated['bg_media_path'] = $request->file('bg_media_file')->store('experiences/bg', 'public');
        }

        $finalGallery = [];
        if ($request->filled('reordered_bg_gallery')) {
            $slidesData = json_decode($request->input('reordered_bg_gallery'), true);
            if (is_array($slidesData)) {
                foreach ($slidesData as $slide) {
                    if ($slide['type'] === 'new' && !empty($slide['data'])) {
                        // Decode base64 and save
                        $data = $slide['data'];
                        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                            $data = substr($data, strpos($data, ',') + 1);
                            $ext = strtolower($type[1]);
                            if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                                $data = base64_decode($data);
                                if ($data !== false) {
                                    $filename = 'experiences/bg_gallery/' . uniqid() . '.' . $ext;
                                    Storage::disk('public')->put($filename, $data);
                                    $finalGallery[] = $filename;
                                }
                            }
                        }
                    }
                }
                $validated['bg_gallery_images'] = array_values($finalGallery);
            }
        }
        unset($validated['image'], $validated['bg_media_file'], $validated['bg_gallery_files']);
        Experience::create($validated);
        return redirect()->route('admin.experiences.index')->with('success', 'Work experience added!');
    }

    public function edit($id)
    {
        $experience = Experience::findOrFail($id);
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);
        $validated = $request->validate([
            'company'           => 'required|string|max:255',
            'role'              => 'required|string|max:255',
            'duration'          => 'required|string|max:100',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'is_active'         => 'boolean',
            'body_content'      => 'nullable|string', // JSON from Editor
            'bg_media_type'     => 'nullable|string|in:image,video,slideshow',
            'bg_media_file'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm|max:102400',
            'bg_gallery_files.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'delete_bg_gallery' => 'nullable|array',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['bg_media_type'] = $validated['bg_media_type'] ?? 'image';

        if (!empty($validated['body_content'])) {
            $validated['body_content'] = json_decode($validated['body_content'], true);
        } else {
            $validated['body_content'] = null;
        }

        if ($request->filled('image_base64')) {
            $data = $request->input('image_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $ext = strtolower($type[1]);
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        if ($experience->image_path) {
                            Storage::disk('public')->delete($experience->image_path);
                        }
                        $filename = 'experiences/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, $data);
                        $validated['image_path'] = $filename;
                    }
                }
            }
        } elseif ($request->hasFile('image')) {
            if ($experience->image_path) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('experiences', 'public');
        }

        if ($request->filled('bg_media_base64') && $validated['bg_media_type'] === 'image') {
            $data = $request->input('bg_media_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $ext = strtolower($type[1]);
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        if ($experience->bg_media_path) {
                            Storage::disk('public')->delete($experience->bg_media_path);
                        }
                        $filename = 'experiences/bg/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, $data);
                        $validated['bg_media_path'] = $filename;
                    }
                }
            }
        } elseif ($request->hasFile('bg_media_file')) {
            if ($experience->bg_media_path) {
                Storage::disk('public')->delete($experience->bg_media_path);
            }
            $validated['bg_media_path'] = $request->file('bg_media_file')->store('experiences/bg', 'public');
        }

        $finalGallery = [];
        if ($request->filled('reordered_bg_gallery')) {
            $slidesData = json_decode($request->input('reordered_bg_gallery'), true);
            if (is_array($slidesData)) {
                $existingPaths = $experience->bg_gallery_images ?? [];
                
                foreach ($slidesData as $slide) {
                    if ($slide['type'] === 'existing' && in_array($slide['path'], $existingPaths)) {
                        $finalGallery[] = $slide['path'];
                    } elseif ($slide['type'] === 'new' && !empty($slide['data'])) {
                        // Decode base64 and save
                        $data = $slide['data'];
                        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                            $data = substr($data, strpos($data, ',') + 1);
                            $ext = strtolower($type[1]);
                            if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                                $data = base64_decode($data);
                                if ($data !== false) {
                                    $filename = 'experiences/bg_gallery/' . uniqid() . '.' . $ext;
                                    Storage::disk('public')->put($filename, $data);
                                    $finalGallery[] = $filename;
                                }
                            }
                        }
                    }
                }
                
                // Delete any existing paths that are no longer in $finalGallery
                foreach ($existingPaths as $oldPath) {
                    if (!in_array($oldPath, $finalGallery)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                $validated['bg_gallery_images'] = array_values($finalGallery);
            }
        }

        unset($validated['image'], $validated['bg_media_file'], $validated['bg_gallery_files'], $validated['delete_bg_gallery']);
        $experience->update($validated);
        return redirect()->route('admin.experiences.index')->with('success', 'Experience updated!');
    }

    public function destroy($id)
    {
        $experience = Experience::findOrFail($id);
        if ($experience->image_path) Storage::disk('public')->delete($experience->image_path);
        if ($experience->bg_media_path) Storage::disk('public')->delete($experience->bg_media_path);
        if (!empty($experience->bg_gallery_images)) {
            foreach ($experience->bg_gallery_images as $path) Storage::disk('public')->delete($path);
        }
        $experience->delete();
        return redirect()->route('admin.experiences.index')->with('success', 'Experience deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:experiences,id']);
        foreach ($request->order as $position => $id) {
            Experience::where('id', $id)->update(['sort_order' => $position]);
        }
        return response()->json(['ok' => true]);
    }

    public function uploadBodyMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,webm|max:102400',
        ]);

        $path = $request->file('file')->store('experiences/body', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'exp_default_bg_mode' => 'required|string|in:cycle,custom',
            'exp_default_bg_type' => 'nullable|string|in:image,video,slideshow',
            'bg_media_file'       => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm|max:102400',
        ]);

        $profile = Profile::first();
        if (!$profile) {
            $profile = Profile::create([]);
        }

        $profile->exp_default_bg_mode = $validated['exp_default_bg_mode'];
        $profile->exp_default_bg_type = $validated['exp_default_bg_type'] ?? 'image';

        if ($request->filled('single_cropped_base64') && $profile->exp_default_bg_type === 'image') {
            $data = $request->input('single_cropped_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $ext = strtolower($type[1]);
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        if ($profile->exp_default_bg_media_path) {
                            Storage::disk('public')->delete($profile->exp_default_bg_media_path);
                        }
                        $filename = 'profiles/exp_bg/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, $data);
                        $profile->exp_default_bg_media_path = $filename;
                    }
                }
            }
        } elseif ($request->hasFile('bg_media_file')) {
            if ($profile->exp_default_bg_media_path) {
                Storage::disk('public')->delete($profile->exp_default_bg_media_path);
            }
            $profile->exp_default_bg_media_path = $request->file('bg_media_file')->store('profiles/exp_bg', 'public');
        }

        $finalGallery = [];
        if ($request->filled('reordered_bg_gallery')) {
            $slidesData = json_decode($request->input('reordered_bg_gallery'), true);
            if (is_array($slidesData)) {
                $existingPaths = $profile->exp_default_bg_gallery_images ?? [];
                
                foreach ($slidesData as $slide) {
                    if ($slide['type'] === 'existing' && in_array($slide['path'], $existingPaths)) {
                        $finalGallery[] = $slide['path'];
                    } elseif ($slide['type'] === 'new' && !empty($slide['data'])) {
                        // Decode base64 and save
                        $data = $slide['data'];
                        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                            $data = substr($data, strpos($data, ',') + 1);
                            $ext = strtolower($type[1]);
                            if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                                $data = base64_decode($data);
                                if ($data !== false) {
                                    $filename = 'profiles/exp_bg_gallery/' . uniqid() . '.' . $ext;
                                    Storage::disk('public')->put($filename, $data);
                                    $finalGallery[] = $filename;
                                }
                            }
                        }
                    }
                }
                
                // Delete any existing paths that are no longer in $finalGallery
                foreach ($existingPaths as $oldPath) {
                    if (!in_array($oldPath, $finalGallery)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }
        } else {
            // Keep existing gallery if not filled, but since we always submit the JSON, 
            // empty array means they deleted everything.
            if ($request->has('reordered_bg_gallery')) { // Form was submitted but empty
                $existingPaths = $profile->exp_default_bg_gallery_images ?? [];
                foreach ($existingPaths as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            } else {
                $finalGallery = $profile->exp_default_bg_gallery_images ?? [];
            }
        }

        if ($request->has('reordered_bg_gallery')) {
            $profile->exp_default_bg_gallery_images = $finalGallery;
        }

        $profile->save();

        return redirect()->back()->with('success', 'Experience settings updated successfully!');
    }
}
