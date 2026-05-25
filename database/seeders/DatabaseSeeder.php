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
            'name' => 'IX MEDIA',
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

        // Seed Projects with full CMS data
        Project::create([
            'category'     => 'ui',
            'title'        => 'Nebula eCommerce Engine',
            'subtitle'     => 'A next-gen storefront built for speed and experience',
            'slug'         => 'nebula-ecommerce-engine',
            'description'  => 'A next-generation digital store built with Laravel and custom Tailwind CSS components. Features real-time state management, secure Stripe payment intents, elegant product dashboards, and dynamic search integrations.',
            'body_content' => "The brief was simple: replace a clunky WooCommerce setup that was hemorrhaging conversion rates. What we delivered was anything but simple.\n\nWe rebuilt the storefront from the ground up on a Laravel + Vite stack, stripping every unnecessary DOM node and replacing bloated plugin chains with purpose-built components. The result: a 94-point Lighthouse performance score and a 38% lift in checkout completion.\n\nThe design system centers on high-contrast product cards with micro-interaction hover states. Each card reveals a quick-add panel on hover, reducing average clicks-to-cart from 4 to 1. We paired this with an Algolia-powered search that delivers sub-200ms results with typo tolerance and category faceting.\n\nOn the backend, Stripe Payment Intents handle PCI-compliant flows while webhook listeners keep order state in real-time sync. The admin panel gives the client full CMS control over banners, featured collections, and promotional countdown timers.",
            'thumbnail_path' => null,
            'media_type'   => 'image',
            'tags'         => 'Laravel,Tailwind CSS,AlpineJS,Stripe,SQLite,Algolia',
            'client'       => 'IX Media — Internal Product',
            'role'         => 'Lead Product Designer & Full-Stack Engineer',
            'year'         => '2025',
            'medium'       => 'Product Design',
            'collaborators'=> 'Jerie Santos (Backend), Marco Reyes (QA)',
            'demo_url'     => 'https://example.com',
            'github_url'   => 'https://github.com',
            'featured'     => true,
        ]);

        Project::create([
            'category'     => 'ui',
            'title'        => 'Aether Analytics Suite',
            'subtitle'     => 'Business intelligence with a human face',
            'slug'         => 'aether-analytics-suite',
            'description'  => 'A sleek business intelligence platform showcasing complex interactive charting, detailed report generators, and elegant real-time server monitoring tools.',
            'body_content' => "Most analytics dashboards look like they were designed by engineers, for engineers. Aether was our answer to that problem — a full BI suite that non-technical stakeholders could actually navigate without a training session.\n\nWe mapped 12 user personas across three business roles (executive, analyst, operations) and distilled the interface into three distinct dashboard modes. Executives see high-level KPI tiles with sparklines. Analysts get deep drill-down tables with custom filter builders. Ops teams see live server health indicators with configurable alert thresholds.\n\nChartJS was extended with custom plugin hooks to support our branded gradient fills and animated entry sequences. Every chart is exportable to PDF and PNG directly from the UI.\n\nThe real engineering challenge was the real-time layer. We used Laravel Echo + Pusher to broadcast server metrics every 5 seconds without full page re-renders, keeping CPU overhead on the client well below 4%.",
            'thumbnail_path' => null,
            'media_type'   => 'image',
            'tags'         => 'VueJS,Tailwind CSS,ChartJS,Laravel API,Pusher',
            'client'       => 'Aether Data Co.',
            'role'         => 'UI/UX Designer & Frontend Architect',
            'year'         => '2024',
            'medium'       => 'Dashboard Design',
            'collaborators'=> 'Dana Lim (Data Engineering), Chris Po (Vue Lead)',
            'demo_url'     => 'https://example.com',
            'github_url'   => 'https://github.com',
            'featured'     => true,
        ]);

        Project::create([
            'category'     => 'ui',
            'title'        => 'Zenith Project Manager',
            'subtitle'     => 'Where teams align and work flows',
            'slug'         => 'zenith-project-manager',
            'description'  => 'A robust collaboration space for software teams with drag-and-drop kanban boards, live chat via WebSockets, and milestone charting overlays.',
            'body_content' => "Zenith started as an internal tool for a 12-person agency and grew into a standalone SaaS product after becoming indispensable to three client teams during its beta.\n\nThe Kanban engine was the core challenge. We needed drag-and-drop that felt native — instant, no flicker, optimistic UI updates that gracefully handle server conflicts. We built a custom Vue directive wrapping native drag events, syncing card positions to the server via debounced PATCH requests. Conflicts are resolved with a last-write-wins + visual merge indicator.\n\nThe live chat layer uses Laravel WebSockets (self-hosted) to keep infrastructure costs flat. Message threads are scoped to cards, keeping context tight without polluting a general feed.\n\nFor the milestone overlay, we built a compact Gantt renderer that overlays a timeline directly on the Kanban view, toggled with a keyboard shortcut. No context-switch, no separate page.",
            'thumbnail_path' => null,
            'media_type'   => 'image',
            'tags'         => 'Laravel,Vite,Vue,WebSockets,Tailwind',
            'client'       => 'Creative Bloom — Internal SaaS',
            'role'         => 'Product Designer & Full-Stack Developer',
            'year'         => '2024',
            'medium'       => 'SaaS Product',
            'collaborators'=> 'Raia Cruz (PM), Ben Ocampo (Backend)',
            'demo_url'     => 'https://example.com',
            'github_url'   => 'https://github.com',
            'featured'     => true,
        ]);

        Project::create([
            'category'     => 'ui',
            'title'        => 'Nova Mobile Wallet',
            'subtitle'     => 'Personal finance that feels like a lifestyle brand',
            'slug'         => 'nova-mobile-wallet-app',
            'description'  => 'Modern financial application tracking personal assets, executing virtual peer-to-peer transfers, and rendering customized budget graphs.',
            'body_content' => "Nova was a pure design exercise — no legacy codebase, no technical constraints. Just the question: what does a mobile wallet look like when it's designed the way a luxury product is?\n\nWe explored 40+ visual directions before landing on the final system: a dark-first palette with gold-tinted data visualisations, haptic-paired micro-interactions, and a card metaphor that makes digital money feel tangible.\n\nThe onboarding flow was iterated 8 times based on usability testing with 30 participants. The final 4-step KYC flow reduced drop-off from 61% to 14% by separating identity verification from account setup and introducing an animated progress map.\n\nBudget graphs use custom Rive animations to celebrate spending milestones — a small detail that generated an outsized amount of positive App Store reviews.",
            'thumbnail_path' => null,
            'media_type'   => 'image',
            'tags'         => 'Figma,Prototyping,Rive,UI/UX Design,Mobile',
            'client'       => 'Nova Fintech — Concept',
            'role'         => 'Lead Product & Motion Designer',
            'year'         => '2023',
            'medium'       => 'Mobile UI',
            'collaborators'=> 'Kris Dela Cruz (Research), Paolo Sy (Prototype Dev)',
            'demo_url'     => 'https://example.com',
            'github_url'   => null,
            'featured'     => false,
        ]);

        // ──────────────────────────────────────────────────────────
        // Seed Visual & Motion Design projects (Pinterest Bento Box)
        // ──────────────────────────────────────────────────────────
        $visuals = [
            ['title' => 'Neon Nights', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/101/600/800'],
            ['title' => 'Abstract Flow', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/102/800/800'],
            ['title' => 'Brand Identity X', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/103/600/900'],
            ['title' => 'Cinematic Reel', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/104/1280/720'],
            ['title' => 'Poster Series 1', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/105/800/800'],
            ['title' => 'Logo Animation', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/106/600/800'],
            ['title' => 'Event Promo', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/107/1920/1080'],
            ['title' => 'Typography Study', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/108/800/800'],
            ['title' => '3D Render', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/109/600/1000'],
            ['title' => 'Music Video Cut', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/110/1024/768'],
            
            // Batch 2
            ['title' => 'Cyberpunk Aesthetics', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/111/700/900'],
            ['title' => 'Kinetic Typography', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/112/800/600'],
            ['title' => 'Minimalist Cover', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/113/800/1200'],
            ['title' => 'Vlog Sequence', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/114/1920/1080'],
            ['title' => 'Grid System', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/115/1000/1000'],
            ['title' => 'Fluid Simulation', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/116/1080/1350'],
            ['title' => 'Festival Aftermovie', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/117/1280/720'],
            ['title' => 'Editorial Spread', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/118/900/1200'],
            ['title' => 'Character Rigging', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/119/800/800'],
            ['title' => 'Documentary Intro', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/120/1920/1080'],
            
            // Batch 3
            ['title' => 'Vintage Retouch', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/121/800/1000'],
            ['title' => 'Loading Spinner', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/122/600/600'],
            ['title' => 'Packaging Mockup', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/123/1200/900'],
            ['title' => 'Social Media Ad', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/124/1080/1920'],
            ['title' => 'Iconography', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/125/800/800'],
            ['title' => 'UI Interaction', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/126/1000/800'],
            ['title' => 'Interview Cut', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/127/1920/1080'],
            ['title' => 'Magazine Layout', 'med' => 'Graphic Art', 'type' => 'image', 'url' => null, 'thumb' => 'https://picsum.photos/seed/128/900/1200'],
            ['title' => 'Particle Effects', 'med' => 'Motion', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/129/1080/1080'],
            ['title' => 'Highlight Reel', 'med' => 'Video Edit', 'type' => 'video', 'url' => 'https://www.w3schools.com/html/mov_bbb.mp4', 'thumb' => 'https://picsum.photos/seed/130/1920/1080'],
        ];

        foreach ($visuals as $idx => $v) {
            Project::create([
                'category'       => 'visual',
                'title'          => $v['title'],
                'slug'           => \Illuminate\Support\Str::slug($v['title'] . '-' . $idx),
                'medium'         => $v['med'],
                'media_type'     => $v['type'],
                'video_url'      => $v['url'],
                'thumbnail_path' => $v['thumb'], // Using Picsum for real aspect ratios
                'description'    => 'A creative ' . strtolower($v['med']) . ' piece showcasing experimental techniques and visual storytelling.',
                'body_content'   => "This project was a deep dive into experimental visual techniques. The goal was to break away from traditional structural constraints and explore pure aesthetic expression.\n\nBy manipulating raw assets and applying dynamic grading, the final output presents a striking, high-contrast visual experience that commands attention.",
                'year'           => '2025',
                'client'         => 'Personal / Studio',
                'role'           => 'Creative Director',
            ]);
        }

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
