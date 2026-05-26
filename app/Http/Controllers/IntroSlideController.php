<?php

namespace App\Http\Controllers;

use App\Models\IntroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntroSlideController extends Controller
{
    public function index()
    {
        // Seed slide 1 if table is empty
        if (IntroSlide::count() === 0) {
            IntroSlide::create([
                'chapter_label' => 'I am',
                'title'         => 'Brix Jorie F. Cura',
                'subtitle'      => 'Product Designer • Full-Stack Creative • System Developer',
                'description'   => "A multidisciplinary creative blending design, storytelling, and code to deliver intentional, high-impact digital solutions. As a solution-based problem solver with skills spanning visual arts and front-end development.\n\nI don't just build interfaces—I design with strict purpose and execution, turning complex challenges into meaningful, user-centric experiences.",
                'image_path'    => null,
                'sort_order'    => 0,
                'is_locked'     => true,
            ]);
        }

        $slides = IntroSlide::orderBy('sort_order')->get();
        return view('admin.intro_slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_label' => 'required|string|max:100',
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:3000',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $validated['sort_order'] = IntroSlide::max('sort_order') + 1;
        $validated['is_locked'] = false;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('intro_slides', 'public');
        }

        unset($validated['image']);
        IntroSlide::create($validated);
        return redirect()->route('admin.intro_slides.index')->with('success', 'Slide added!');
    }

    public function edit($id)
    {
        $editSlide = IntroSlide::findOrFail($id);
        $slides = IntroSlide::orderBy('sort_order')->get();
        return view('admin.intro_slides.index', compact('slides', 'editSlide'));
    }

    public function update(Request $request, $id)
    {
        $slide = IntroSlide::findOrFail($id);
        $validated = $request->validate([
            'chapter_label' => 'required|string|max:100',
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:3000',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($slide->image_path) {
                Storage::disk('public')->delete($slide->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('intro_slides', 'public');
        }

        unset($validated['image']);
        $slide->update($validated);
        return redirect()->route('admin.intro_slides.index')->with('success', 'Slide updated!');
    }

    public function destroy($id)
    {
        $slide = IntroSlide::findOrFail($id);
        if ($slide->is_locked) {
            return redirect()->route('admin.intro_slides.index')->with('error', 'Slide 1 is locked and cannot be deleted.');
        }
        if ($slide->image_path) {
            Storage::disk('public')->delete($slide->image_path);
        }
        $slide->delete();
        return redirect()->route('admin.intro_slides.index')->with('success', 'Slide deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:intro_slides,id']);
        foreach ($request->order as $position => $id) {
            // Never move locked slide from position 0
            $slide = IntroSlide::find($id);
            if ($slide && !$slide->is_locked) {
                $slide->update(['sort_order' => $position]);
            }
        }
        return response()->json(['ok' => true]);
    }
}
