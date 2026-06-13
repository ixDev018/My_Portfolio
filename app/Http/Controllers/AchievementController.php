<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
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
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'year'        => 'required|string|max:20',
            'type'        => 'required|in:award,certificate',
            'description' => 'nullable|string|max:2000',
            'disable_modal' => 'nullable|boolean',
        ]);

        if ($request->filled('image_data')) {
            $imageData = $request->input('image_data');
            // Remove the data URL prefix
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'png'])) {
                    $imageData = base64_decode($imageData);
                    if ($imageData !== false) {
                        $filename = 'achievements/' . uniqid() . '.png';
                        Storage::disk('public')->put($filename, $imageData);
                        $validated['media_path'] = $filename;
                    }
                }
            }
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
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'year'        => 'required|string|max:20',
            'type'        => 'required|in:award,certificate',
            'description' => 'nullable|string|max:2000',
            'disable_modal' => 'nullable|boolean',
        ]);

        if ($request->filled('image_data')) {
            $imageData = $request->input('image_data');
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'png'])) {
                    $imageData = base64_decode($imageData);
                    if ($imageData !== false) {
                        $filename = 'achievements/' . uniqid() . '.png';
                        Storage::disk('public')->put($filename, $imageData);
                        $validated['media_path'] = $filename;

                        // optionally delete old image
                        if ($achievement->media_path && Storage::disk('public')->exists($achievement->media_path)) {
                            Storage::disk('public')->delete($achievement->media_path);
                        }
                    }
                }
            }
        }
        $validated['disable_modal'] = $request->has('disable_modal');

        $achievement->update($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement updated!');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        if ($achievement->media_path && Storage::disk('public')->exists($achievement->media_path)) {
            Storage::disk('public')->delete($achievement->media_path);
        }
        $achievement->delete();
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement deleted.');
    }
}
