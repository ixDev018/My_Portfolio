<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Achievement;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@portfolio.com',
        ]);

        // Seed Profile details matching Figma/Screenshot branding
        Profile::create([
            'name' => 'Alex Morgan',
            'title' => 'Full-Stack Developer & UI/UX Specialist',
            'bio_short' => 'Turning ideas into reality, one pixel at a time.',
            'bio_long' => "I am a dedicated software craftsman with over 5 years of professional experience developing premium web applications. Having started my journey as a graphic designer, I merge strict structural clean-coding habits with an artistic eye for high-contrast colors, elegant typography, and smooth transitions. I believe that web applications should not only function flawlessly but should also wow users with their responsive aesthetics.",
            'avatar_path' => null,
            'cv_path' => null,
            'github_url' => 'https://github.com',
            'linkedin_url' => 'https://linkedin.com',
            'twitter_url' => 'https://twitter.com',
            'email' => 'alex.morgan@portfolio.com',
        ]);

        // Seed Projects
        Project::create([
            'title' => 'Nebula eCommerce Engine',
            'slug' => 'nebula-ecommerce-engine',
            'description' => 'A next-generation digital store built with Laravel and custom Tailwind CSS components. Features real-time state management, secure stripe payment intents, elegant product dashboards, and dynamic search integrations.',
            'thumbnail_path' => null,
            'tags' => 'Laravel,Tailwind CSS,AlpineJS,Stripe,SQLite',
            'demo_url' => 'https://example.com',
            'github_url' => 'https://github.com',
            'featured' => true,
        ]);

        Project::create([
            'title' => 'Aether Analytics Suite',
            'slug' => 'aether-analytics-suite',
            'description' => 'A sleek business intelligence platform showcasing complex interactive charting, detailed report generators, and elegant real-time server monitoring tools.',
            'thumbnail_path' => null,
            'tags' => 'VueJS,Tailwind CSS,ChartJS,Laravel API',
            'demo_url' => 'https://example.com',
            'github_url' => 'https://github.com',
            'featured' => true,
        ]);

        Project::create([
            'title' => 'Zenith Project Manager',
            'slug' => 'zenith-project-manager',
            'description' => 'A robust collaboration space for software teams. Implements drag-and-drop kanban boards, live chat communication web sockets, and milestone charting overlays.',
            'thumbnail_path' => null,
            'tags' => 'Laravel,Vite,Tailwind,WebSockets',
            'demo_url' => 'https://example.com',
            'github_url' => 'https://github.com',
            'featured' => true,
        ]);

        Project::create([
            'title' => 'Nova Mobile Wallet App',
            'slug' => 'nova-mobile-wallet-app',
            'description' => 'Modern financial application tracking personal assets, executing virtual peer-to-peer transfers, and rendering customized budget graphs.',
            'thumbnail_path' => null,
            'tags' => 'Flutter,Firebase,UI/UX Design',
            'demo_url' => 'https://example.com',
            'github_url' => 'https://github.com',
            'featured' => false,
        ]);

        // Seed Technical Skills matching the Figma layout
        $skills = [
            // Core
            ['name' => 'Communication Skills', 'category' => 'CORE', 'proficiency' => 100],
            ['name' => 'Leadership Skills', 'category' => 'CORE', 'proficiency' => 100],
            ['name' => 'Dedicated Learning', 'category' => 'CORE', 'proficiency' => 100],
            ['name' => 'Strategic Thinking', 'category' => 'CORE', 'proficiency' => 100],

            // External
            ['name' => 'Product Design', 'category' => 'EXTERNAL', 'proficiency' => 100],
            ['name' => 'Product Design', 'category' => 'EXTERNAL', 'proficiency' => 100],
            ['name' => 'Product Design', 'category' => 'EXTERNAL', 'proficiency' => 100],
            ['name' => 'Product Design', 'category' => 'EXTERNAL', 'proficiency' => 100],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Seed Work Experiences
        Experience::create([
            'company' => 'IX Media',
            'role' => 'Lead Visual Engineer',
            'duration' => '2024 - Present',
            'description' => 'Architecting premium web interfaces, neo-brutalist corporate branding portals, and high-performance Tailwind systems.',
        ]);

        Experience::create([
            'company' => 'Aether Agency',
            'role' => 'Full-Stack Developer',
            'duration' => '2022 - 2024',
            'description' => 'Integrated secure API endpoints, restructured Laravel database frameworks, and collaborated on sleek client product suites.',
        ]);

        Experience::create([
            'company' => 'Creative Bloom',
            'role' => 'Web Developer Intern',
            'duration' => '2021 - 2022',
            'description' => 'Maintained local PHP installations, drafted HTML/CSS prototypes, and conducted visual design QA tests.',
        ]);

        // Seed Achievements
        Achievement::create([
            'type' => 'award',
            'title' => 'Innovative Web Design Gold Award',
            'issuer' => 'Creative Web Alliance',
            'year' => '2025',
            'description' => 'Awarded for excellence in neo-brutalist interactive typography and responsive design architectures.',
        ]);

        Achievement::create([
            'type' => 'award',
            'title' => 'Outstanding Software Craftsman',
            'issuer' => 'DevCon Summit',
            'year' => '2024',
            'description' => 'Honored for outstanding contributions to open-source database optimizations in SQLite & Laravel frameworks.',
        ]);

        Achievement::create([
            'type' => 'certificate',
            'title' => 'Advanced Laravel Framework Certification',
            'issuer' => 'Laracasts Academy',
            'year' => '2023',
            'description' => 'Verified expert certification in route structures, middleware bindings, and queue processors.',
        ]);

        Achievement::create([
            'type' => 'certificate',
            'title' => 'Responsive Web Architecture & Accessibility',
            'issuer' => 'W3 Consortium',
            'year' => '2022',
            'description' => 'Certified in designing highly keyboard-navigable and screen-reader accessible modern layouts.',
        ]);
    }
}
