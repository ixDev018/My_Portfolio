<?php

namespace App\Http\Controllers;

use App\Models\IntroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntroSlideController extends Controller
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
            'image_data'    => 'nullable|string',
        ]);

        $validated['sort_order'] = IntroSlide::max('sort_order') + 1;
        $validated['is_locked']  = false;

        if (!empty($validated['image_data'])) {
            $url = $this->uploadMedia($validated['image_data'], 'intro_slides');
            if ($url) $validated['image_path'] = $url;
        } elseif ($request->hasFile('image')) {
            $url = $this->uploadMedia($request->file('image'), 'intro_slides');
            if ($url) $validated['image_path'] = $url;
        }

        unset($validated['image'], $validated['image_data']);
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
            'image_data'    => 'nullable|string',
        ]);

        if (!empty($validated['image_data'])) {
            if ($slide->image_path) $this->deleteMedia($slide->image_path);
            $url = $this->uploadMedia($validated['image_data'], 'intro_slides');
            if ($url) $validated['image_path'] = $url;
        } elseif ($request->hasFile('image')) {
            if ($slide->image_path) $this->deleteMedia($slide->image_path);
            $url = $this->uploadMedia($request->file('image'), 'intro_slides');
            if ($url) $validated['image_path'] = $url;
        }

        unset($validated['image'], $validated['image_data']);
        $slide->update($validated);
        return redirect()->route('admin.intro_slides.index')->with('success', 'Slide updated!');
    }

    public function destroy($id)
    {
        $slide = IntroSlide::findOrFail($id);
        if ($slide->is_locked) {
            return redirect()->route('admin.intro_slides.index')->with('error', 'Slide 1 is locked and cannot be deleted.');
        }
        if ($slide->image_path) $this->deleteMedia($slide->image_path);
        $slide->delete();
        return redirect()->route('admin.intro_slides.index')->with('success', 'Slide deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:intro_slides,id']);
        foreach ($request->order as $position => $id) {
            $slide = IntroSlide::find($id);
            if ($slide && !$slide->is_locked) {
                $slide->update(['sort_order' => $position]);
            }
        }
        return response()->json(['ok' => true]);
    }
}
