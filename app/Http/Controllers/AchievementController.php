<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::orderByDesc('year')->orderByDesc('created_at')->get();
        return view('admin.achievements.index', compact('achievements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'year'        => 'required|string|max:20',
            'type'        => 'required|in:award,certificate',
            'description' => 'nullable|string|max:2000',
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

        Achievement::create($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement added successfully!');
    }

    public function edit($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievements = Achievement::orderByDesc('year')->get();
        return view('admin.achievements.index', compact('achievements', 'achievement'));
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
