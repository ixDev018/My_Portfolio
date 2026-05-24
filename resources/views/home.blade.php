@extends('layouts.app')

@section('title', $profile->name . ' | Portfolio')

@section('content')

    <!-- HERO SECTION -->
    <section id="hero" class="relative min-h-[95vh] flex flex-col justify-between pt-36 bg-[#FAF7E6] text-black overflow-hidden select-none">
        
        <!-- Center Hero Copy -->
        <div class="max-w-7xl mx-auto px-6 flex-grow flex flex-col justify-center items-center text-center relative z-10 w-full">
            
            <!-- Hero Typography Container -->
            <div class="inline-flex flex-col items-stretch select-none mx-auto mb-6">
                <!-- Turning Ideas Into (justified) -->
                <div class="flex justify-between w-full font-display uppercase text-black leading-none select-none relative z-10" style="font-size: clamp(12px, 4vw, 45px);">
                    <span>TURNING</span>
                    <span>IDEAS</span>
                    <span>INTO</span>
                </div>

                <!-- REALITY (thin border, yellow fill, no shadows) -->
                <h1 class="text-yellow-400 font-normal leading-none uppercase font-display tracking-tight select-none text-center" style="font-size: clamp(50px, 18vw, 205.84px); margin-top: -0.12em; -webkit-text-stroke: 1px black;">
                    REALITY
                </h1>
            </div>

            <!-- One Pixel At A Time -->
            <p class="text-xs sm:text-sm tracking-[0.4em] uppercase text-black/70 mb-10 font-sans">
                One Pixel At A Time
            </p>

            <!-- Get Started Button -->
            <a href="#projects" 
               class="px-8 py-3 bg-transparent border border-black font-sans text-xs font-bold uppercase tracking-wider rounded-none hover:bg-black hover:text-[#FAF7E6] transition-colors duration-300">
                Get Started
            </a>
            
        </div>

        <!-- Organic Deep Purple Wave SVG at bottom -->
        <div class="w-full leading-none z-10 -mb-[1px]">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z" fill="#512b81"></path>
            </svg>
        </div>
    </section>

    <!-- SELF INTRO SECTION -->
    <section id="self-intro" class="bg-[#512b81] text-white relative flex flex-col" style="min-height: clamp(500px, 90vh, 960px);" x-data="{
        slide: 0,
        total: 3,
        prev() { this.slide = (this.slide - 1 + this.total) % this.total; },
        next() { this.slide = (this.slide + 1) % this.total; }
    }">

        <!-- Section Header -->
        <div class="text-center pt-5 pb-4 px-6">
            <h2 class="text-xl font-display uppercase tracking-[0.3em] text-white">Introduction</h2>
        </div>
        <hr class="border-white/25 mx-0">

        <!-- Slides Wrapper -->
        <div class="flex-1 max-w-5xl w-full mx-auto px-10 flex flex-col justify-center">

            <!-- Slides Container: fixed height, slides layered absolutely for seamless crossfade -->
            <div class="relative w-full" style="height: clamp(320px, 60vh, 560px);">

                <!-- SLIDE 1: Who I am -->
                <div x-show="slide === 0"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 grid gap-12 items-center"
                     style="grid-template-columns: 3fr 2fr;">

                    <!-- Left: Text -->
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-sm italic text-white/70 font-sans whitespace-nowrap">I am</span>
                            <div class="flex-1 border-t border-dotted border-white/40"></div>
                        </div>
                        <h3 class="font-display text-5xl lg:text-[3.5rem] xl:text-[4rem] text-[#4dd9f0] uppercase leading-none mb-2">
                            Brix Jorie F. Cura
                        </h3>
                        <p class="text-xs font-sans text-white/50 tracking-wider mb-8">
                            Product Designer &nbsp;•&nbsp; Full-Stack Creative &nbsp;•&nbsp; System Developer
                        </p>
                        <div class="space-y-5 font-poppins text-sm text-white/80 leading-loose">
                            <p>A multidisciplinary creative blending design, storytelling, and code to deliver intentional, high-impact digital solutions. As a solution-based problem solver with skills spanning visual arts and front-end development.</p>
                            <p>I don't just build interfaces—I design with strict purpose and execution, turning complex challenges into meaningful, user-centric experiences.</p>
                        </div>
                    </div>

                    <!-- Right: Photo -->
                    <div class="flex items-center justify-end h-full">
                        <div class="rounded-[2rem] overflow-hidden h-full" style="aspect-ratio: 3/4;">
                            <img src="{{ asset('images/intro/profile.png') }}"
                                 alt="Brix Jorie Cura"
                                 class="w-full h-full object-cover object-top"
                                 onerror="this.parentElement.innerHTML='<div style=\'height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;text-align:center;font-size:11px;color:rgba(0,0,0,0.4);\'>Place photo at<br><strong>public/images/intro/profile.png</strong></div>'">
                        </div>
                    </div>
                </div>

                <!-- SLIDE 2: Placeholder -->
                <div x-show="slide === 1"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 grid gap-12 items-center"
                     style="grid-template-columns: 3fr 2fr;">

                    <!-- Left: Text -->
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-sm italic text-white/70 font-sans whitespace-nowrap">Chapter 2</span>
                            <div class="flex-1 border-t border-dotted border-white/40"></div>
                        </div>
                        <h3 class="font-display text-5xl lg:text-[3.5rem] xl:text-[4rem] text-[#4dd9f0] uppercase leading-none mb-2">
                            Your Story Here
                        </h3>
                        <p class="text-xs font-sans text-white/50 tracking-wider mb-8">
                            Subtitle &nbsp;•&nbsp; Role &nbsp;•&nbsp; Context
                        </p>
                        <div class="space-y-5 font-poppins text-sm text-white/80 leading-loose">
                            <p>This is the second slide of your story. Replace this placeholder with a chapter about your journey, your skills, or a key milestone that shaped who you are today.</p>
                        </div>
                    </div>

                    <!-- Right: Photo -->
                    <div class="flex items-center justify-end h-full">
                        <div class="rounded-[2rem] overflow-hidden h-full" style="aspect-ratio: 3/4;">
                            <img src="{{ asset('images/intro/slide2.jpg') }}" alt="Slide 2"
                                 class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div style=\'height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;text-align:center;font-size:11px;color:rgba(0,0,0,0.4);\'>Place image at<br><strong>public/images/intro/slide2.jpg</strong></div>'">
                        </div>
                    </div>
                </div>

                <!-- SLIDE 3: Placeholder -->
                <div x-show="slide === 2"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 grid gap-12 items-center"
                     style="grid-template-columns: 3fr 2fr;">

                    <!-- Left: Text -->
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-sm italic text-white/70 font-sans whitespace-nowrap">Chapter 3</span>
                            <div class="flex-1 border-t border-dotted border-white/40"></div>
                        </div>
                        <h3 class="font-display text-5xl lg:text-[3.5rem] xl:text-[4rem] text-[#4dd9f0] uppercase leading-none mb-2">
                            Your Story Here
                        </h3>
                        <p class="text-xs font-sans text-white/50 tracking-wider mb-8">
                            Subtitle &nbsp;•&nbsp; Role &nbsp;•&nbsp; Context
                        </p>
                        <div class="space-y-5 font-poppins text-sm text-white/80 leading-loose">
                            <p>This is the third slide of your story. Fill it with what makes you unique—your values, your vision, or a powerful moment that defines your craft and drives your work.</p>
                        </div>
                    </div>

                    <!-- Right: Photo -->
                    <div class="flex items-center justify-end h-full">
                        <div class="rounded-[2rem] overflow-hidden h-full" style="aspect-ratio: 3/4;">
                            <img src="{{ asset('images/intro/slide3.jpg') }}" alt="Slide 3"
                                 class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div style=\'height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;text-align:center;font-size:11px;color:rgba(0,0,0,0.4);\'>Place image at<br><strong>public/images/intro/slide3.jpg</strong></div>'">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Navigation: divider + dots + arrows -->
        <hr class="border-white/25 mx-0">
        <div class="py-5 flex items-center justify-center gap-6">

            <!-- Prev Arrow -->
            <button @click="prev()" class="w-8 h-8 flex items-center justify-center border border-white/50 rounded-full hover:bg-white/10 transition-colors duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <!-- Dots -->
            <div class="flex gap-2.5 items-center">
                <template x-for="i in total" :key="i">
                    <button @click="slide = i - 1"
                            :class="slide === i - 1 ? 'bg-white' : 'bg-white/35 hover:bg-white/60'"
                            class="w-2.5 h-2.5 rounded-full transition-all duration-300">
                    </button>
                </template>
            </div>

            <!-- Next Arrow -->
            <button @click="next()" class="w-8 h-8 flex items-center justify-center border border-white/50 rounded-full hover:bg-white/10 transition-colors duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

        </div>

    </section>

    <!-- SKILLS SECTION -->
    <section id="skills" class="w-full bg-[#512b81] text-black pt-16">
        
        <div class="w-full flex flex-col border-y border-black relative z-10">
            
            @php
                $categoryColors = [
                    'CORE' => 'bg-[#d0f69a]',
                    'EXTERNAL' => 'bg-[#faf597]'
                ];
            @endphp

            <!-- Header -->
            <div class="w-full bg-[#d0f69a] py-3 text-center border-b border-black">
                <h2 class="font-display text-2xl uppercase tracking-[0.1em] text-[#512b81]">Skills</h2>
            </div>

            <!-- Loop Categories -->
            @foreach($skillsByCategory as $category => $skills)
                
                @if($category === 'EXTERNAL')
                    <!-- Yellow Separator Row before External -->
                    <div class="w-full h-8 md:h-12 bg-[#faf597] border-b border-black"></div>
                @endif

                <div class="{{ $categoryColors[$category] ?? 'bg-[#d0f69a]' }} w-full flex flex-col md:grid md:grid-cols-[3.5rem_1fr_1fr_1fr_1fr_3.5rem] border-b border-black">
                    
                    <!-- Left Sidebar (Category Name) -->
                    <div class="flex items-center justify-center border-b md:border-b-0 md:border-r border-black py-4 md:py-8">
                        <span class="font-mono font-bold tracking-[0.3em] uppercase text-slate-800 md:-rotate-90 whitespace-nowrap text-xs">
                            {{ $category }}
                        </span>
                    </div>

                    <!-- Skills Blocks (up to 4) -->
                    @foreach($skills->take(4) as $index => $skill)
                        <div class="p-6 border-b md:border-b-0 md:border-r border-black flex flex-col justify-between min-h-[160px] lg:min-h-[220px] transition-all duration-300 hover:bg-gradient-to-br hover:from-white/90 hover:via-white/40 hover:to-transparent cursor-default group">
                            <!-- Number Circle -->
                            <div class="w-7 h-7 rounded-full border border-black flex items-center justify-center text-[11px] font-sans text-black">
                                {{ $index + 1 }}
                            </div>

                            <!-- Skill Name -->
                            <h3 class="font-poppins font-black text-sm lg:text-base uppercase text-black leading-snug mt-8 group-hover:scale-[1.02] transition-transform origin-left">
                                {{ $skill->name }}
                            </h3>
                        </div>
                    @endforeach

                    <!-- Fill remaining columns if less than 4 skills -->
                    @for($i = $skills->count(); $i < 4; $i++)
                        <div class="p-6 border-b md:border-b-0 md:border-r border-black hidden md:block"></div>
                    @endfor

                    <!-- Right Empty Sidebar -->
                    <div class="hidden md:block"></div>
                    
                </div>
            @endforeach
        </div>

        <!-- Yellow Wave Spacer connecting to the white section below -->
        <div class="w-full bg-[#FAF7E6] leading-none -mt-[1px]">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-[0_2px_2px_rgba(0,0,0,0.1)]">
                <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" fill="#faf597"></path>
            </svg>
        </div>

    </section>

    <!-- WORKS AND OUTPUTS SECTION -->
    <section id="projects" class="py-24 bg-[#FAF7E6] text-black border-b-4 border-black" x-data="{ activeFilter: 'All' }">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Header & Filter Grid -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16">
                <div>
                    <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Outputs</h2>
                    <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-none">Works & Projects</h3>
                </div>

                <!-- Client Side Filter tabs using AlpineJS -->
                <div class="flex flex-wrap gap-2 p-1.5 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    <button @click="activeFilter = 'All'" 
                            :class="{ 'bg-yellow-300 font-extrabold': activeFilter === 'All', 'hover:bg-slate-50 text-slate-600': activeFilter !== 'All' }"
                            class="px-4 py-1.5 border border-transparent rounded-lg text-xs font-mono uppercase tracking-wider transition-all duration-200">
                        All
                    </button>
                    @foreach($skillsByCategory->keys() as $category)
                        <button @click="activeFilter = '{{ $category }}'" 
                                :class="{ 'bg-yellow-300 font-extrabold': activeFilter === '{{ $category }}', 'hover:bg-slate-50 text-slate-600': activeFilter !== '{{ $category }}' }"
                                class="px-4 py-1.5 border border-transparent rounded-lg text-xs font-mono uppercase tracking-wider transition-all duration-200">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects as $project)
                    @php
                        $projectCategories = ['All'];
                        $tagsLower = strtolower($project->tags);
                        if (str_contains($tagsLower, 'laravel') || str_contains($tagsLower, 'php') || str_contains($tagsLower, 'api') || str_contains($tagsLower, 'sql')) {
                            $projectCategories[] = 'Backend';
                        }
                        if (str_contains($tagsLower, 'tailwind') || str_contains($tagsLower, 'vue') || str_contains($tagsLower, 'javascript') || str_contains($tagsLower, 'html') || str_contains($tagsLower, 'alpine')) {
                            $projectCategories[] = 'Frontend';
                        }
                        if (str_contains($tagsLower, 'git') || str_contains($tagsLower, 'figma') || str_contains($tagsLower, 'docker') || str_contains($tagsLower, 'ui/ux')) {
                            $projectCategories[] = 'Tools';
                        }
                        if (count($projectCategories) === 1) {
                            $projectCategories[] = 'Frontend';
                        }
                        
                        $categoriesJson = json_encode($projectCategories);
                    @endphp
                    
                    <div x-show="{{ $categoriesJson }}.includes(activeFilter)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="h-full">
                        <x-project-card :project="$project" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ACHIEVEMENTS SECTION (Switchable Tabs for Awards & Certificates) -->
    <section id="achievements" class="py-24 bg-[#FAF7E6] text-black border-b-4 border-black" x-data="{ activeTab: 'award' }">
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="text-center mb-12">
                <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Honors</h2>
                <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-none mb-6">Achievements</h3>

                <!-- Switchable tabs with brutalist outlines -->
                <div class="inline-flex p-1.5 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    <button @click="activeTab = 'award'"
                            :class="{ 'bg-yellow-300 font-extrabold': activeTab === 'award', 'text-slate-500 hover:text-black': activeTab !== 'award' }"
                            class="px-6 py-2 rounded-lg text-xs font-mono uppercase tracking-wider transition-all duration-200">
                        Awards
                    </button>
                    <button @click="activeTab = 'certificate'"
                            :class="{ 'bg-yellow-300 font-extrabold': activeTab === 'certificate', 'text-slate-500 hover:text-black': activeTab !== 'certificate' }"
                            class="px-6 py-2 rounded-lg text-xs font-mono uppercase tracking-wider transition-all duration-200">
                        Certificates
                    </button>
                </div>
            </div>

            <!-- Awards Listings -->
            <div x-show="activeTab === 'award'" x-transition class="space-y-6">
                @forelse($achievementsByType->get('award', []) as $award)
                    <div class="bg-white border-4 border-black p-6 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
                            <div>
                                <h4 class="text-lg font-extrabold text-black leading-tight">{{ $award->title }}</h4>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $award->issuer }}</p>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 border border-black font-mono text-[10px] font-bold rounded-lg">{{ $award->year }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed font-sans">{{ $award->description }}</p>
                    </div>
                @empty
                    <p class="text-center text-slate-500 text-sm font-mono">No awards registered yet.</p>
                @endforelse
            </div>

            <!-- Certificates Listings -->
            <div x-show="activeTab === 'certificate'" x-transition class="space-y-6" style="display: none;">
                @forelse($achievementsByType->get('certificate', []) as $cert)
                    <div class="bg-white border-4 border-black p-6 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
                            <div>
                                <h4 class="text-lg font-extrabold text-black leading-tight">{{ $cert->title }}</h4>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $cert->issuer }}</p>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 border border-black font-mono text-[10px] font-bold rounded-lg">{{ $cert->year }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed font-sans">{{ $cert->description }}</p>
                    </div>
                @empty
                    <p class="text-center text-slate-500 text-sm font-mono">No certificates registered yet.</p>
                @endforelse
            </div>

        </div>
    </section>

    <!-- WORK EXPERIENCE SECTION -->
    <section id="experience" class="py-24 bg-[#FAF7E6] text-black border-b-4 border-black">
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="text-center mb-16">
                <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Timeline</h2>
                <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-none">Work Experience</h3>
            </div>

            <!-- Vertical Timeline Cards -->
            <div class="relative border-l-4 border-black pl-8 ml-4 space-y-12">
                @forelse($experiences as $exp)
                    <div class="relative">
                        <!-- Connecting bubble node -->
                        <div class="absolute -left-[42px] top-1.5 w-6 h-6 rounded-full bg-yellow-300 border-4 border-black shadow-[1.5px_1.5px_0px_0px_rgba(0,0,0,1)]"></div>
                        
                        <div class="bg-white border-4 border-black p-6 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                <div>
                                    <h4 class="text-lg font-extrabold text-black leading-tight">{{ $exp->role }}</h4>
                                    <p class="text-xs font-bold text-slate-500 font-mono mt-0.5">{{ $exp->company }}</p>
                                </div>
                                <span class="px-3 py-1 bg-yellow-100 border border-black font-mono text-[10px] font-bold rounded-lg">{{ $exp->duration }}</span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed font-sans">{!! nl2br(e($exp->description)) !!}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 text-sm font-mono">No work experience loaded.</p>
                @endforelse
            </div>

        </div>
    </section>

    <!-- COLLABORATE (CONTACT) SECTION -->
    <section id="contact" class="py-24 bg-[#FAF7E6] text-black">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <!-- Contact info cards -->
                <div class="lg:col-span-5">
                    <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Collaborate</h2>
                    <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-none mb-6">Let's craft something premium together</h3>
                    <p class="text-slate-650 text-sm sm:text-base leading-relaxed mb-8">
                        Whether you want to discuss a new full-time role, a freelance project, or just want to connect over software craftsmanship—my inbox is always open.
                    </p>

                    <div class="space-y-4">
                        <!-- Email Card -->
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 border-2 border-black text-black flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-mono">Direct Email</p>
                                <a href="mailto:{{ $profile->email }}" class="text-sm font-bold text-black hover:underline">{{ $profile->email }}</a>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 border-2 border-black text-black flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-mono">Location</p>
                                <p class="text-sm font-bold text-black">Silicon Valley, CA, USA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div class="lg:col-span-7">
                    <form action="{{ route('portfolio.contact') }}" method="POST" class="bg-white border-4 border-black p-8 rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required
                                       value="{{ old('name') }}"
                                       class="w-full bg-[#FAF7E6] border-2 border-black focus:border-[#ff6b00] rounded-xl px-4 py-3 text-black text-sm outline-none transition-all duration-200">
                                @error('name')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required
                                       value="{{ old('email') }}"
                                       class="w-full bg-[#FAF7E6] border-2 border-black focus:border-[#ff6b00] rounded-xl px-4 py-3 text-black text-sm outline-none transition-all duration-200">
                                @error('email')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Subject (Optional)</label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject"
                                   value="{{ old('subject') }}"
                                   class="w-full bg-[#FAF7E6] border-2 border-black focus:border-[#ff6b00] rounded-xl px-4 py-3 text-black text-sm outline-none transition-all duration-200">
                            @error('subject')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Message</label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="5"
                                      required
                                      class="w-full bg-[#FAF7E6] border-2 border-black focus:border-[#ff6b00] rounded-xl px-4 py-3 text-black text-sm outline-none transition-all duration-200 resize-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full py-4 bg-[#ff6b00] hover:bg-black hover:text-yellow-300 text-white font-bold text-xs uppercase tracking-widest border-2 border-black rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2.5px] hover:translate-y-[2.5px] transition-all">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
