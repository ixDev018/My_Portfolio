<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.experiences.index', compact('experiences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company'     => 'required|string|max:255',
            'role'        => 'required|string|max:255',
            'duration'    => 'required|string|max:100',
            'description' => 'nullable|string|max:3000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $validated['sort_order'] = Experience::max('sort_order') + 1;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('experiences', 'public');
        }

        unset($validated['image']);
        Experience::create($validated);
        return redirect()->route('admin.experiences.index')->with('success', 'Work experience added!');
    }

    public function edit($id)
    {
        $experience = Experience::findOrFail($id);
        $experiences = Experience::orderBy('sort_order')->get();
        return view('admin.experiences.index', compact('experiences', 'experience'));
    }

    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);
        $validated = $request->validate([
            'company'     => 'required|string|max:255',
            'role'        => 'required|string|max:255',
            'duration'    => 'required|string|max:100',
            'description' => 'nullable|string|max:3000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($experience->image_path) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('experiences', 'public');
        }

        unset($validated['image']);
        $experience->update($validated);
        return redirect()->route('admin.experiences.index')->with('success', 'Experience updated!');
    }

    public function destroy($id)
    {
        $experience = Experience::findOrFail($id);
        if ($experience->image_path) {
            Storage::disk('public')->delete($experience->image_path);
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
}
