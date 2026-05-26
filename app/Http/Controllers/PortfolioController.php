<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\ToolItem;
use App\Models\Experience;
use App\Models\Achievement;
use App\Models\ContactMessage;
use App\Models\IntroSlide;

class PortfolioController extends Controller
{
    /**
     * Display the dynamic landing page.
     */
    public function index()
    {
        // Fetch the first profile (fallback to mock static if none exists)
        $profile = Profile::first();

        if (!$profile) {
            $profile = new Profile([
                'name' => 'Alex Morgan',
                'title' => 'Full-Stack Developer',
                'bio_short' => 'Turning ideas into reality, one pixel at a time.',
                'bio_long' => 'Creative developer merging strict backend structure with sleek visual design.',
                'email' => 'alex.morgan@portfolio.com',
            ]);
        }

        // Fetch projects (featured first, then newest)
        $projects = Project::orderBy('featured', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Fetch all skills and group them by category
        $skillsByCategory = Skill::all()->groupBy('category');

        // Fetch all work experiences ordered by sort_order
        $experiences = Experience::orderBy('sort_order')->get();

        // Fetch all achievements and group them by type (award, certificate)
        $achievementsByType = Achievement::all()->groupBy('type');

        // Fetch all intro slides ordered by sort_order
        $introSlides = IntroSlide::orderBy('sort_order')->get();

        // Fetch tools for marquee
        $toolsByRow = ToolItem::orderBy('sort_order')->get()->groupBy('row_label');

        return view('home', compact('profile', 'projects', 'skillsByCategory', 'experiences', 'achievementsByType', 'introSlides', 'toolsByRow'));
    }

    /**
     * Display a single project isolated page.
     */
    public function showProject($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $profile = Profile::first();
        return view('project-show', compact('project', 'profile'));
    }

    /**
     * Display the isolated outputs gallery page.
     */
    public function outputs()
    {
        $visualProjects = Project::where('category', 'visual')
                                 ->inRandomOrder()
                                 ->get();

        return view('outputs', compact('visualProjects'));
    }

    /**
     * Process visitor contact form submissions.
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:2000',
        ]);

        ContactMessage::create($validated);

        return redirect()->route('portfolio.index')
                         ->with('success', 'Thank you! Your message has been received successfully.');
    }
}
