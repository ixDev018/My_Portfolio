<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExperienceController extends Controller
{
    /**
     * Upload any media (UploadedFile or base64 string) to Cloudinary.
     * Returns the secure_url on success, empty string on failure.
     */
    protected function uploadMedia($data, string $folder): string
    {
        try {
            if ($data instanceof \Illuminate\Http\UploadedFile) {
                $uploaded = cloudinary()->uploadApi()->upload(
                    $data->getRealPath(),
                    ['folder' => "portfolio/{$folder}", 'resource_type' => 'auto']
                );
                return $uploaded['secure_url'];
            }

            if (is_string($data) && preg_match('/^data:(\w+)\/(\w+);base64,/', $data)) {
                $uploaded = cloudinary()->uploadApi()->upload(
                    $data,
                    ['folder' => "portfolio/{$folder}", 'resource_type' => 'auto']
                );
                return $uploaded['secure_url'];
            }

            return '';
        } catch (\Exception $e) {
            \Log::error("Cloudinary upload failed [{$folder}]: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Delete media from Cloudinary (if it's an http URL) or local storage.
     */
    protected function deleteMedia(string $path): void
    {
        if (empty($path)) return;

        if (Str::startsWith($path, 'http')) {
            // Extract public_id from Cloudinary URL
            // e.g. https://res.cloudinary.com/xxx/image/upload/v123/portfolio/experiences/abc.jpg
            // public_id = portfolio/experiences/abc
            try {
                if (preg_match('/\/upload\/(?:v\d+\/)?(.+?)(?:\.\w+)?$/', $path, $matches)) {
                    $publicId = $matches[1];
                    cloudinary()->uploadApi()->destroy($publicId, ['resource_type' => 'image']);
                    cloudinary()->uploadApi()->destroy($publicId, ['resource_type' => 'video']);
                }
            } catch (\Exception $e) {
                \Log::warning("Cloudinary delete failed for [{$path}]: " . $e->getMessage());
            }
            return;
        }

        Storage::disk(config('filesystems.default'))->delete($path);
    }

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
            'body_content'      => 'nullable|string',
            'bg_media_type'     => 'nullable|string|in:image,video,slideshow',
            'bg_media_file'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm|max:102400',
            'bg_gallery_files.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['sort_order']    = Experience::max('sort_order') + 1;
        $validated['is_active']     = $request->boolean('is_active');
        $validated['bg_media_type'] = $validated['bg_media_type'] ?? 'image';

        if (!empty($validated['body_content'])) {
            $validated['body_content'] = json_decode($validated['body_content'], true);
        }

        // Company logo / avatar
        if ($request->filled('image_base64')) {
            $url = $this->uploadMedia($request->input('image_base64'), 'experiences');
            if ($url) $validated['image_path'] = $url;
        } elseif ($request->hasFile('image')) {
            $url = $this->uploadMedia($request->file('image'), 'experiences');
            if ($url) $validated['image_path'] = $url;
        }

        // Background media (single image/video)
        if ($request->filled('bg_media_base64') && $validated['bg_media_type'] === 'image') {
            $url = $this->uploadMedia($request->input('bg_media_base64'), 'experiences/bg');
            if ($url) $validated['bg_media_path'] = $url;
        } elseif ($request->hasFile('bg_media_file')) {
            $url = $this->uploadMedia($request->file('bg_media_file'), 'experiences/bg');
            if ($url) $validated['bg_media_path'] = $url;
        }

        // Background gallery slideshow
        $finalGallery = [];
        if ($request->filled('reordered_bg_gallery')) {
            $slidesData = json_decode($request->input('reordered_bg_gallery'), true);
            if (is_array($slidesData)) {
                foreach ($slidesData as $slide) {
                    if ($slide['type'] === 'new' && !empty($slide['data'])) {
                        $url = $this->uploadMedia($slide['data'], 'experiences/bg_gallery');
                        if ($url) $finalGallery[] = $url;
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
            'body_content'      => 'nullable|string',
            'bg_media_type'     => 'nullable|string|in:image,video,slideshow',
            'bg_media_file'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm|max:102400',
            'bg_gallery_files.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'delete_bg_gallery' => 'nullable|array',
        ]);

        $validated['is_active']     = $request->boolean('is_active');
        $validated['bg_media_type'] = $validated['bg_media_type'] ?? 'image';

        if (!empty($validated['body_content'])) {
            $validated['body_content'] = json_decode($validated['body_content'], true);
        } else {
            $validated['body_content'] = null;
        }

        // Company logo / avatar
        if ($request->filled('image_base64')) {
            if ($experience->image_path) $this->deleteMedia($experience->image_path);
            $url = $this->uploadMedia($request->input('image_base64'), 'experiences');
            if ($url) $validated['image_path'] = $url;
        } elseif ($request->hasFile('image')) {
            if ($experience->image_path) $this->deleteMedia($experience->image_path);
            $url = $this->uploadMedia($request->file('image'), 'experiences');
            if ($url) $validated['image_path'] = $url;
        }

        // Background media
        if ($request->filled('bg_media_base64') && $validated['bg_media_type'] === 'image') {
            if ($experience->bg_media_path) $this->deleteMedia($experience->bg_media_path);
            $url = $this->uploadMedia($request->input('bg_media_base64'), 'experiences/bg');
            if ($url) $validated['bg_media_path'] = $url;
        } elseif ($request->hasFile('bg_media_file')) {
            if ($experience->bg_media_path) $this->deleteMedia($experience->bg_media_path);
            $url = $this->uploadMedia($request->file('bg_media_file'), 'experiences/bg');
            if ($url) $validated['bg_media_path'] = $url;
        }

        // Background gallery slideshow
        $finalGallery = [];
        if ($request->filled('reordered_bg_gallery')) {
            $slidesData = json_decode($request->input('reordered_bg_gallery'), true);
            if (is_array($slidesData)) {
                $existingPaths = $experience->bg_gallery_images ?? [];

                foreach ($slidesData as $slide) {
                    if ($slide['type'] === 'existing' && in_array($slide['path'], $existingPaths)) {
                        $finalGallery[] = $slide['path'];
                    } elseif ($slide['type'] === 'new' && !empty($slide['data'])) {
                        $url = $this->uploadMedia($slide['data'], 'experiences/bg_gallery');
                        if ($url) $finalGallery[] = $url;
                    }
                }

                // Delete removed paths
                foreach ($existingPaths as $oldPath) {
                    if (!in_array($oldPath, $finalGallery)) {
                        $this->deleteMedia($oldPath);
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
        if ($experience->image_path)    $this->deleteMedia($experience->image_path);
        if ($experience->bg_media_path) $this->deleteMedia($experience->bg_media_path);
        if (!empty($experience->bg_gallery_images)) {
            foreach ($experience->bg_gallery_images as $path) $this->deleteMedia($path);
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

        $url = $this->uploadMedia($request->file('file'), 'experiences/body');
        return response()->json(['url' => $url]);
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
            if ($profile->exp_default_bg_media_path) $this->deleteMedia($profile->exp_default_bg_media_path);
            $url = $this->uploadMedia($request->input('single_cropped_base64'), 'profiles/exp_bg');
            if ($url) $profile->exp_default_bg_media_path = $url;
        } elseif ($request->hasFile('bg_media_file')) {
            if ($profile->exp_default_bg_media_path) $this->deleteMedia($profile->exp_default_bg_media_path);
            $url = $this->uploadMedia($request->file('bg_media_file'), 'profiles/exp_bg');
            if ($url) $profile->exp_default_bg_media_path = $url;
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
                        $url = $this->uploadMedia($slide['data'], 'profiles/exp_bg_gallery');
                        if ($url) $finalGallery[] = $url;
                    }
                }

                foreach ($existingPaths as $oldPath) {
                    if (!in_array($oldPath, $finalGallery)) {
                        $this->deleteMedia($oldPath);
                    }
                }
            }
        } else {
            if ($request->has('reordered_bg_gallery')) {
                $existingPaths = $profile->exp_default_bg_gallery_images ?? [];
                foreach ($existingPaths as $oldPath) $this->deleteMedia($oldPath);
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
