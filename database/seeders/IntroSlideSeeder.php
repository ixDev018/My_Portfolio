<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntroSlide;

class IntroSlideSeeder extends Seeder
{
    public function run(): void
    {
        // Slide 2: The Approach
        IntroSlide::create([
            'chapter_label' => 'Chapter 2',
            'title'         => 'DESIGN MEETS LOGIC',
            'subtitle'      => 'The Approach',
            'description'   => "I believe the best products aren't just beautiful—they make sense. Coming from a multimedia background, I treat front-end development as an extension of storytelling. Whether I'm editing a video, designing a UI in Figma, or writing code, my goal is always to create a frictionless journey for the end user.\n\nI bridge the gap between aesthetics and functionality—so nothing is ever just functional, and nothing is ever just pretty. Both have to coexist.",
            'sort_order'    => 2,
            'is_locked'     => false,
        ]);

        // Slide 3: The Journey
        IntroSlide::create([
            'chapter_label' => 'Chapter 3',
            'title'         => 'THE JOURNEY',
            'subtitle'      => 'Experience & Growth',
            'description'   => "Over 3 years at the SPCC CIT Council, I evolved from a creative member to Head Editor—leading multimedia operations under tight deadlines and building real leadership experience along the way.\n\nNow, as an independent freelancer, I bring that same drive directly to my clients—managing projects from the first wireframe to the final deployment, independently and with purpose.",
            'sort_order'    => 3,
            'is_locked'     => false,
        ]);
    }
}
