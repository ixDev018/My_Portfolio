<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

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

        $achievement->update($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement updated!');
    }

    public function destroy($id)
    {
        Achievement::findOrFail($id)->delete();
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement deleted.');
    }
}
