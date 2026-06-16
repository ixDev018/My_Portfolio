<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
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
            if (is_string($data) && preg_match('/^data:image\/\w+;base64,/', $data)) {
                $uploaded = cloudinary()->uploadApi()->upload(
                    $data,
                    ['folder' => "portfolio/{$folder}", 'resource_type' => 'auto']
                );
                return $uploaded['secure_url'];
            }
        } catch (\Exception $e) {
            \Log::error("Cloudinary upload failed [{$folder}]: " . $e->getMessage());
        }
        return '';
    }

    protected function deleteMedia(string $path): void
    {
        if (empty($path)) return;
        if (Str::startsWith($path, 'http')) {
            try {
                if (preg_match('/\/upload\/(?:v\d+\/)?(.+?)(?:\.\w+)?$/', $path, $matches)) {
                    cloudinary()->uploadApi()->destroy($matches[1], ['resource_type' => 'image']);
                }
            } catch (\Exception $e) {}
            return;
        }
        \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($path);
    }

    public function index()
    {
        $achievements = Achievement::orderByDesc('year')->orderByDesc('created_at')->get();
        $profile = Profile::firstOrCreate([]);
        return view('admin.achievements.index', compact('achievements', 'profile'));
    }

    public function toggleModals(Request $request)
    {
        $profile = Profile::firstOrCreate([]);
        $profile->disable_achievements_modal = !$profile->disable_achievements_modal;
        $profile->save();
        return response()->json([
            'success' => true,
            'disable_achievements_modal' => $profile->disable_achievements_modal
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'issuer'        => 'required|string|max:255',
            'year'          => 'required|string|max:20',
            'type'          => 'required|in:award,certificate',
            'description'   => 'nullable|string|max:2000',
            'disable_modal' => 'nullable|boolean',
        ]);

        if ($request->filled('image_data')) {
            $url = $this->uploadMedia($request->input('image_data'), 'achievements');
            if ($url) $validated['media_path'] = $url;
        }

        $validated['disable_modal'] = $request->has('disable_modal');

        Achievement::create($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement added successfully!');
    }

    public function edit($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievements = Achievement::orderByDesc('year')->get();
        $profile = Profile::firstOrCreate([]);
        return view('admin.achievements.index', compact('achievements', 'achievement', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'issuer'        => 'required|string|max:255',
            'year'          => 'required|string|max:20',
            'type'          => 'required|in:award,certificate',
            'description'   => 'nullable|string|max:2000',
            'disable_modal' => 'nullable|boolean',
        ]);

        if ($request->filled('image_data')) {
            if ($achievement->media_path) $this->deleteMedia($achievement->media_path);
            $url = $this->uploadMedia($request->input('image_data'), 'achievements');
            if ($url) $validated['media_path'] = $url;
        }

        $validated['disable_modal'] = $request->has('disable_modal');
        $achievement->update($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement updated!');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        if ($achievement->media_path) $this->deleteMedia($achievement->media_path);
        $achievement->delete();
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement deleted.');
    }
}
