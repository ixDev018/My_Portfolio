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
                    <!-- Programming Languages Marquee before External -->
                    <div class="w-full bg-[#4f4f4f] text-white flex border-b border-black overflow-hidden h-[72px]">
                        <div class="w-32 md:w-48 shrink-0 border-r border-white/50 flex items-center justify-center font-display text-[10px] md:text-sm tracking-widest uppercase text-center leading-tight bg-[#4f4f4f] z-10 px-2 relative after:content-[''] after:absolute after:right-1 after:top-2 after:bottom-2 after:w-[1px] after:bg-white/30">
                            Programming<br>Languages
                        </div>
                        <div class="flex-1 flex overflow-hidden relative items-center">
                            <!-- Set 1 -->
                            <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12">
                                <span class="font-normal font-sans text-sm md:text-xl text-white">PHP</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Laravel</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">JavaScript</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Vue.js</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Tailwind CSS</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Alpine.js</span>
                                
                                <span class="font-normal font-sans text-sm md:text-xl text-white">PHP</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Laravel</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">JavaScript</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Vue.js</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Tailwind CSS</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Alpine.js</span>
                            </div>
                            <!-- Set 2 -->
                            <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12" aria-hidden="true">
                                <span class="font-normal font-sans text-sm md:text-xl text-white">PHP</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Laravel</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">JavaScript</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Vue.js</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Tailwind CSS</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Alpine.js</span>
                                
                                <span class="font-normal font-sans text-sm md:text-xl text-white">PHP</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Laravel</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">JavaScript</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Vue.js</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Tailwind CSS</span>
                                <span class="font-normal font-sans text-sm md:text-xl text-white">Alpine.js</span>
                            </div>
                        </div>
                    </div>
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
                        <div class="p-6 border-b md:border-b-0 md:border-r border-black flex flex-col justify-between min-h-[180px] lg:min-h-[260px] transition-all duration-300 hover:bg-gradient-to-br hover:from-white/90 hover:via-white/40 hover:to-transparent cursor-default group">
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
            
            <!-- Editing Tools Marquee after External -->
            <div class="w-full bg-[#4f4f4f] text-white flex border-b border-black overflow-hidden h-[72px]">
                <div class="w-32 md:w-48 shrink-0 border-r border-white/50 flex items-center justify-center font-display text-[10px] md:text-sm tracking-widest uppercase text-center leading-tight bg-[#4f4f4f] z-10 px-2 relative after:content-[''] after:absolute after:right-1 after:top-2 after:bottom-2 after:w-[1px] after:bg-white/30">
                    Editing Tools
                </div>
                <div class="flex-1 flex overflow-hidden relative items-center">
                    <!-- Set 1 -->
                    <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12">
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Bl</div> Blender</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ai</div> Illustrator</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ae</div> After Effects</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Pr</div> Premiere</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ps</div> Photoshop</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Cc</div> CapCut</span>
                        
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Bl</div> Blender</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ai</div> Illustrator</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ae</div> After Effects</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Pr</div> Premiere</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ps</div> Photoshop</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Cc</div> CapCut</span>
                    </div>
                    <!-- Set 2 -->
                    <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12" aria-hidden="true">
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Bl</div> Blender</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ai</div> Illustrator</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ae</div> After Effects</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Pr</div> Premiere</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ps</div> Photoshop</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Cc</div> CapCut</span>
                        
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Bl</div> Blender</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ai</div> Illustrator</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ae</div> After Effects</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Pr</div> Premiere</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Ps</div> Photoshop</span>
                        <span class="font-normal font-sans text-sm md:text-lg flex items-center gap-2"><div class="w-6 h-6 border border-white rounded flex items-center justify-center text-[10px] bg-black/20">Cc</div> CapCut</span>
                    </div>
                </div>
            </div>

            <!-- General Tools Marquee -->
            <div class="w-full bg-[#4f4f4f] text-white flex overflow-hidden h-[50px]">
                <div class="w-32 md:w-48 shrink-0 border-r border-white/50 flex items-center justify-center font-display text-[10px] md:text-xs tracking-widest text-center leading-tight bg-[#4f4f4f] z-10 px-2 relative after:content-[''] after:absolute after:right-1 after:top-2 after:bottom-2 after:w-[1px] after:bg-white/30">
                    General Tools
                </div>
                <div class="flex-1 flex overflow-hidden relative items-center">
                    <!-- Set 1 -->
                    <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12">
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Gi</div> Git</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Fi</div> Figma</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Do</div> Docker</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Vs</div> VScode</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ji</div> Jira</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">No</div> Notion</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Fj</div> FigJam</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Wd</div> MS Word</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ex</div> MS Excel</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Pp</div> MS PowerPoint</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ca</div> Canva</span>
                    </div>
                    <!-- Set 2 -->
                    <div class="animate-marquee flex whitespace-nowrap items-center gap-12 pl-12" aria-hidden="true">
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Gi</div> Git</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Fi</div> Figma</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Do</div> Docker</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Vs</div> VScode</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ji</div> Jira</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">No</div> Notion</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Fj</div> FigJam</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Wd</div> MS Word</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ex</div> MS Excel</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Pp</div> MS PowerPoint</span>
                        <span class="font-normal font-sans text-sm md:text-base flex items-center gap-2"><div class="w-5 h-5 border border-white rounded flex items-center justify-center text-[9px] bg-black/20">Ca</div> Canva</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Spacer connecting to the white section below -->
        <div class="w-full bg-[#FAF7E6] leading-none">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-[0_2px_2px_rgba(0,0,0,0.1)]">
                <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" fill="#4f4f4f"></path>
            </svg>
        </div>

    </section>

    <!-- WORKS AND OUTPUTS SECTION -->
    <section id="works" class="w-full bg-[#FAF7E6] text-black pt-16 pb-0 relative">
        <div class="max-w-[1400px] mx-auto px-6 w-full">

            <!-- Section Title -->
            <h2 class="text-center font-display text-2xl uppercase tracking-widest text-black mb-2">Works & Outputs</h2>
            <p class="text-center text-xs font-mono text-black/40 tracking-widest uppercase mb-14">A curated showcase of craft & execution</p>

            {{-- ══════════════════════════════════════════════════
                 PART 1 — PRODUCT & UI DESIGN
                 Functional carousel wired to real $projects
            ══════════════════════════════════════════════════ --}}
            @php
                // Filter specifically for UI/UX product projects
                $uiProjects = $projects->where('category', 'ui')->values();

                // Build a JS-safe JSON array from real DB projects
                $carouselItems = $uiProjects->map(fn($p) => [
                    'title'  => $p->title,
                    'sub'    => $p->subtitle ?? $p->description,
                    'label'  => $p->medium ?? 'Project',
                    'url'    => route('portfolio.project.show', $p->slug),
                ])->values()->toJson();
            @endphp

            <div class="mb-20" x-data="{
                current: 0,
                allItems: {{ $carouselItems }},
                get total() { return this.allItems.length; },
                prev() { this.current = (this.current - 1 + this.total) % this.total; },
                next() { this.current = (this.current + 1) % this.total; },
                get offset() {
                    const cardPx = window.innerWidth >= 1024 ? 560 : (window.innerWidth >= 768 ? 560 : window.innerWidth * 0.82);
                    return this.current * (cardPx + 20);
                }
            }">

                <!-- Sub-header row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-display text-base tracking-widest uppercase text-black">Product &amp; UI Design</h3>
                        <p class="text-xs font-mono text-black/40 mt-0.5">Interfaces built with purpose and precision</p>
                    </div>
                    <!-- Project count pill -->
                    <span class="font-mono text-[10px] text-black/40 uppercase tracking-widest">
                        {{ $uiProjects->count() }} {{ Str::plural('project', $uiProjects->count()) }}
                    </span>
                </div>

                <!-- Carousel Viewport -->
                <div class="relative">

                    <!-- Left gradient fade -->
                    <div class="absolute left-0 top-0 bottom-0 w-16 md:w-28 pointer-events-none z-10"
                         style="background: linear-gradient(to right, #FAF7E6 15%, transparent 100%);"
                         x-show="current > 0"
                         x-transition:enter="transition-opacity duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                    </div>

                    <!-- Right gradient fade -->
                    <div class="absolute right-0 top-0 bottom-0 w-24 md:w-40 pointer-events-none z-10"
                         style="background: linear-gradient(to left, #FAF7E6 15%, transparent 100%);"
                         x-show="current < total - 1"
                         x-transition:enter="transition-opacity duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                    </div>

                    <!-- Left Arrow -->
                    <button @click="prev()"
                            x-show="current > 0"
                            x-transition:enter="transition-opacity duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-black/15 flex items-center justify-center text-black hover:bg-black hover:text-white transition-all duration-200 focus:outline-none"
                            aria-label="Previous slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Right Arrow -->
                    <button @click="next()"
                            x-show="current < total - 1"
                            x-transition:enter="transition-opacity duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-black/15 flex items-center justify-center text-black hover:bg-black hover:text-white transition-all duration-200 focus:outline-none"
                            aria-label="Next slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Track wrapper -->
                    <div style="overflow-x: clip; overflow-y: visible;">
                        <div class="flex gap-5 py-3 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
                             :style="'transform: translateX(-' + offset + 'px)'">

                            {{-- Blade renders the real cards — each is a real <a> link --}}
                            @forelse($uiProjects as $index => $proj)
                                <a href="{{ route('portfolio.project.show', $proj->slug) }}"
                                   class="shrink-0 w-[82vw] md:w-[560px] aspect-video rounded-2xl relative group bg-white"
                                   :class="{{ $index }} === current ? 'ring-2 ring-black/12' : 'opacity-75'"
                                   style="transition: opacity 0.4s ease, transform 0.28s cubic-bezier(0.34,1.56,0.64,1);"
                                   @mouseenter="$el.style.transform='translateY(-6px)'; $el.style.opacity='1';"
                                   @mouseleave="$el.style.transform=''; $el.style.opacity=''">

                                    <!-- Image / placeholder -->
                                    <div class="absolute inset-0 rounded-2xl overflow-hidden border border-black/10
                                                @if($proj->thumbnail_path) bg-slate-900 @else bg-gradient-to-br from-slate-100 to-slate-200 @endif
                                                flex items-center justify-center">
                                        @if($proj->thumbnail_path)
                                            <img src="{{ asset('storage/' . $proj->thumbnail_path) }}"
                                                 alt="{{ $proj->title }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-12 h-12 text-black/15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Hover overlay -->
                                    <div class="absolute inset-0 rounded-2xl bg-black/0 group-hover:bg-black/60 transition-all duration-300 flex flex-col justify-end p-5 pointer-events-none z-10">
                                        <div class="translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-white font-mono text-[9px] uppercase tracking-widest mb-2">
                                                {{ $proj->medium ?? 'Project' }}
                                            </span>
                                            <p class="text-white font-display text-lg uppercase leading-tight">{{ $proj->title }}</p>
                                            <span class="mt-3 inline-flex items-center gap-1.5 text-white font-mono text-[10px] uppercase tracking-widest">
                                                View Case Study
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Medium badge -->
                                    <div class="absolute top-3.5 left-3.5 z-20 px-2.5 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[9px] uppercase tracking-widest text-black/60">
                                        {{ $proj->medium ?? 'Project' }}
                                    </div>

                                    @if($proj->year)
                                        <div class="absolute top-3.5 right-3.5 z-20 px-2.5 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[9px] text-black/50">
                                            {{ $proj->year }}
                                        </div>
                                    @endif
                                </a>
                            @empty
                                <div class="flex-1 flex items-center justify-center min-h-[220px] text-black/30 font-mono text-xs uppercase tracking-widest">
                                    No projects yet.
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>

                <!-- Dot indicators + progress bar -->
                <div class="mt-5 flex items-center gap-5">
                    <div class="flex items-center gap-2">
                        @foreach($uiProjects as $index => $proj)
                            <button @click="current = {{ $index }}"
                                    :class="{{ $index }} === current ? 'w-5 bg-black' : 'w-2 bg-black/25 hover:bg-black/50'"
                                    class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                                    aria-label="Go to slide {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>

                    <!-- Progress bar -->
                    <div class="flex-1 max-w-[120px] h-[2px] bg-black/10 rounded-full overflow-hidden">
                        <div class="h-full bg-black rounded-full transition-all duration-500 ease-out"
                             :style="'width: ' + ((current + 1) / Math.max(total, 1) * 100) + '%'">
                        </div>
                    </div>

                    <!-- Counter -->
                    <span class="font-mono text-[10px] text-black/35 tabular-nums">
                        <span x-text="String(current + 1).padStart(2,'0')"></span>
                        <span class="mx-1 opacity-50">/</span>
                        <span>{{ str_pad($uiProjects->count(), 2, '0', STR_PAD_LEFT) }}</span>
                    </span>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════
             PART 2 — VISUAL & MOTION DESIGN
             Blade masonry wired to real $visualProjects
             Native image height for true Pinterest aspect ratios
        ══════════════════════════════════════════════════ --}}
        @php
            $visualProjects = $projects->where('category', 'visual')->values();

            // Collect distinct mediums for filter pills
            $mediums = $visualProjects->pluck('medium')->filter()->unique()->values();

            // Map medium → play icon visibility (anything motion/video shows play)
            $playTypes = ['Motion', 'Video', 'Video Edit', 'Animation', 'Motion Design'];
        @endphp

        <div class="w-full pt-6 pb-20"
             x-data="{ activeFilter: 'all' }">

            <div class="max-w-[1400px] mx-auto px-6 w-full">

                <!-- Sub-header + filter pills -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div>
                        <h3 class="font-display text-base tracking-widest uppercase text-black">Visual &amp; Motion Design</h3>
                        <p class="text-xs font-mono text-black/40 mt-0.5">Graphic arts, motion animations &amp; video edits</p>
                    </div>

                    <!-- Filter pills — driven by real mediums in DB -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <button @click="activeFilter = 'all'"
                                :class="activeFilter === 'all' ? 'bg-black text-[#FAF7E6]' : 'bg-white text-black/60 hover:text-black hover:border-black/60'"
                                class="px-4 py-1.5 rounded-full border border-black/20 font-mono text-[10px] uppercase tracking-widest transition-all duration-200">
                            All
                        </button>
                        @foreach($mediums as $med)
                            <button @click="activeFilter = '{{ $med }}'"
                                    :class="activeFilter === '{{ $med }}' ? 'bg-black text-[#FAF7E6]' : 'bg-white text-black/60 hover:text-black hover:border-black/60'"
                                    class="px-4 py-1.5 rounded-full border border-black/20 font-mono text-[10px] uppercase tracking-widest transition-all duration-200">
                                {{ $med }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Pinterest masonry — columns layout, natively sized by images --}}
            <div class="max-w-[1400px] mx-auto px-6 relative">
                
                {{-- Cropped Height Wrapper (150vh allowance) --}}
                <div class="relative overflow-hidden" style="max-height: 150vh;">
                    
                    <div class="columns-2 md:columns-3 lg:columns-4 gap-4">

                        @forelse($visualProjects as $proj)
                            {{-- By removing padding-top and absolute positioning, the img tag naturally defines the box height, perfect for masonry! --}}
                            <a href="{{ route('portfolio.project.show', $proj->slug) }}"
                               x-show="activeFilter === 'all' || activeFilter === '{{ $proj->medium }}'"
                               x-transition:enter="transition-opacity duration-300"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               x-transition:leave="transition-opacity duration-200"
                               x-transition:leave-start="opacity-100"
                               x-transition:leave-end="opacity-0"
                               class="block w-full break-inside-avoid mb-4 rounded-2xl overflow-hidden relative group bg-white border border-black/8 cursor-pointer"
                               style="transition: transform 0.22s ease;"
                               @mouseenter="$el.style.transform='scale(1.018)'"
                               @mouseleave="$el.style.transform='scale(1)'">

                                <div class="relative w-full overflow-hidden flex items-center justify-center bg-slate-100">
                                    @if($proj->thumbnail_path)
                                        <img src="{{ Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : asset('storage/' . $proj->thumbnail_path) }}"
                                             alt="{{ $project->title ?? $proj->title }}"
                                             class="w-full h-auto object-cover" loading="lazy">
                                    @else
                                        {{-- Fallback placeholder if missing --}}
                                        <div class="w-full" style="padding-top: 100%;">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                                                <svg class="w-8 h-8 text-black/12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Play button for motion/video types --}}
                                    @if(in_array($proj->medium, $playTypes) || $proj->media_type === 'video')
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                                            <div class="w-11 h-11 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Hover overlay: gradient + title reveal --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 pointer-events-none z-10">
                                        <div class="translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-250">
                                            @if($proj->medium)
                                                <span class="font-mono text-[9px] text-white/70 uppercase tracking-widest">{{ $proj->medium }}</span>
                                            @endif
                                            <p class="text-white font-sans text-sm font-semibold mt-1 leading-snug">{{ $proj->title }}</p>
                                        </div>
                                    </div>

                                    {{-- Type pill —always visible top-right --}}
                                    @if($proj->medium)
                                        <div class="absolute top-3 right-3 z-20">
                                            <span class="px-2 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[8px] uppercase tracking-wider text-black/55 shadow-sm">
                                                {{ $proj->medium }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Year badge top-left --}}
                                    @if($proj->year)
                                        <div class="absolute top-3 left-3 z-20">
                                            <span class="px-2 py-0.5 rounded-full bg-black/40 backdrop-blur-sm font-mono text-[8px] text-white/80">
                                                {{ $proj->year }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="col-span-4 py-20 flex items-center justify-center text-black/30 font-mono text-xs uppercase tracking-widest">
                                No creative outputs yet.
                            </div>
                        @endforelse

                    </div>

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-[#FAF7E6] via-[#FAF7E6]/90 to-transparent pointer-events-none"></div>
                </div>

                {{-- See More Button --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20">
                    <a href="{{ route('portfolio.outputs') }}" 
                       class="inline-block px-10 py-3 border-[1.5px] border-[#ff6b00] text-[#ff6b00] font-sans font-bold text-sm tracking-wider uppercase hover:bg-[#ff6b00] hover:text-white transition-colors duration-300 bg-[#FAF7E6] shadow-sm"
                       style="font-family: 'Poppins', sans-serif;">
                        See More
                    </a>
                </div>

            </div>

        </div>

    </section>

    <!-- Wave Spacer connecting to the white section below -->
    <div class="w-full bg-white leading-none">
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-[0_2px_2px_rgba(0,0,0,0.1)]">
            <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" fill="#FAF7E6"></path>
        </svg>
    </div>

    <!-- ACHIEVEMENTS SECTION (Modern Two-Column Layout) -->
    <section id="achievements" class="py-24 bg-white text-black font-sans border-b border-gray-100" 
             x-data="{ activeTab: 'all', selectedItem: null }">
        <div class="max-w-[1400px] mx-auto px-6">
            
            <!-- Top Header & Pills -->
            <div class="text-center mb-24">
                <h3 class="text-[3rem] font-bold tracking-tight text-black mb-8 font-display uppercase">Achievements</h3>

                <!-- Switchable tabs (Purple Accent) -->
                <div class="inline-flex items-center gap-3">
                    <button @click="activeTab = 'all'"
                            :class="{ 'bg-[#5e17eb] text-white shadow-md border-[#5e17eb]': activeTab === 'all', 'bg-[#f4f5f7] text-gray-500 hover:bg-gray-200 border-gray-200': activeTab !== 'all' }"
                            class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border font-poppins">
                        All
                    </button>
                    <button @click="activeTab = 'award'"
                            :class="{ 'bg-[#5e17eb] text-white shadow-md border-[#5e17eb]': activeTab === 'award', 'bg-[#f4f5f7] text-gray-500 hover:bg-gray-200 border-gray-200': activeTab !== 'award' }"
                            class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border font-poppins">
                        Awards
                    </button>
                    <button @click="activeTab = 'certificate'"
                            :class="{ 'bg-[#5e17eb] text-white shadow-md border-[#5e17eb]': activeTab === 'certificate', 'bg-[#f4f5f7] text-gray-500 hover:bg-gray-200 border-gray-200': activeTab !== 'certificate' }"
                            class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border font-poppins">
                        Certificates
                    </button>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-16 lg:gap-24">
                
                <!-- Left Content -->
                <div class="lg:w-[35%] text-left pt-4">
                    <h2 class="text-[3.25rem] font-bold tracking-tight text-black mb-6 leading-[1.1] font-poppins">
                        We've got <br> 
                        <span class="font-poppins" x-text="activeTab === 'all' ? 'Great' : (activeTab === 'award' ? 'Amazing' : 'Official')"></span> <br>
                        <span class="font-poppins" x-text="activeTab === 'all' ? 'Achievements' : (activeTab === 'award' ? 'Awards' : 'Certificates')"></span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-12 font-medium font-poppins">
                        Being appreciated for the work we do means the world to us. It translates beautifully into our official ratings, continuous learning, and industry recognition.
                    </p>
                    
                    <!-- Arrow Controls (Left Column) -->
                    <div class="flex items-center gap-4"
                         x-data="{ 
                            scrollNext() { document.getElementById('achievement-carousel').scrollBy({ left: 340, behavior: 'smooth' }) },
                            scrollPrev() { document.getElementById('achievement-carousel').scrollBy({ left: -340, behavior: 'smooth' }) }
                         }">
                        <button @click="scrollPrev()" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-black transition-colors group">
                            <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </button>
                        <div class="w-12 h-[2px] bg-gray-200 relative">
                            <div class="absolute left-0 top-0 h-full w-1/2 bg-[#5e17eb]"></div>
                        </div>
                        <button @click="scrollNext()" class="w-12 h-12 flex items-center justify-center text-[#5e17eb] hover:text-[#4a12ba] transition-colors group">
                            <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Right Content (Carousel) -->
                <div class="lg:w-[65%] w-full relative"
                     x-data="{ 
                        canScrollLeft: false, 
                        canScrollRight: true,
                        checkScroll() {
                            const el = document.getElementById('achievement-carousel');
                            if (!el) return;
                            this.canScrollLeft = el.scrollLeft > 32;
                            this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 32);
                        }
                     }"
                     x-init="
                        $nextTick(() => { checkScroll(); });
                        document.getElementById('achievement-carousel').addEventListener('scroll', () => checkScroll());
                        $watch('activeTab', () => { setTimeout(() => checkScroll(), 400); });
                     "
                     @resize.window="checkScroll()">
                     
                    <!-- Left Gradient Fade -->
                    <div x-show="canScrollLeft" 
                         x-transition.opacity.duration.300ms
                         class="absolute -left-4 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-30 pointer-events-none" style="display: none;"></div>

                    <!-- Right Gradient Fade -->
                    <div x-show="canScrollRight"
                         x-transition.opacity.duration.300ms
                         class="absolute -right-4 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-30 pointer-events-none"></div>

                    <!-- Carousel Track -->
                    <!-- We use negative margins and padding to allow shadows to clip nicely without cutting off -->
                    <div id="achievement-carousel" class="flex gap-8 overflow-x-auto snap-x snap-mandatory pb-16 pt-8 px-4 -mx-4 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                        
                        <!-- Mixed Achievements Loop -->
                        @forelse($achievementsByType->flatten(1)->sortByDesc('year') as $item)
                            @php
                                $isAward = $item->type === 'award';
                                $itemType = $isAward ? 'Award' : 'Certificate';
                            @endphp
                            <!-- Card -->
                            <div x-show="activeTab === 'all' || activeTab === '{{ $item->type }}'"
                                 x-transition.opacity
                                 @click="selectedItem = { title: {{ Js::from($item->title) }}, issuer: {{ Js::from($item->issuer) }}, year: {{ Js::from($item->year) }}, description: {{ Js::from($item->description) }}, type: '{{ $itemType }}' }"
                                 class="flex-none snap-start group cursor-pointer relative"
                                 style="width: 320px;">
                                 
                                <div class="relative overflow-hidden bg-white rounded-[2rem] p-10 flex flex-col items-center text-center transition-all duration-500 transform shadow-[0_8px_30px_rgb(0,0,0,0.04)] group-hover:-translate-y-2 group-hover:shadow-[0_20px_40px_rgb(94,23,235,0.12)] group-hover:bg-[#E5C14D] group-hover:border-[#C4A030] border-2 border-gray-100 h-[420px]">
                                    
                                    <!-- Shine Effect -->
                                    <div class="absolute top-0 -left-[150%] w-[100%] h-full bg-gradient-to-r from-transparent via-white/60 to-transparent transform -skew-x-12 transition-all duration-700 ease-in-out group-hover:left-[150%] z-20 pointer-events-none"></div>

                                    <div class="w-full flex justify-center mb-10 mt-4 relative z-10">
                                        @if($isAward)
                                            <!-- Hexagon-ish Shape for Icon -->
                                            <div class="w-32 h-32 relative flex items-center justify-center">
                                                <svg class="absolute inset-0 w-full h-full text-amber-50 drop-shadow-sm transition-colors duration-500 group-hover:text-amber-100" viewBox="0 0 100 100" fill="currentColor">
                                                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" />
                                                </svg>
                                                <svg class="absolute inset-0 w-full h-full transform scale-90" viewBox="0 0 100 100" fill="url(#goldPulse-{{ $loop->index }}-award)">
                                                    <defs>
                                                        <radialGradient id="goldPulse-{{ $loop->index }}-award" cx="50%" cy="50%" r="65%">
                                                            <stop offset="0%" stop-color="#fff1a8">
                                                                <animate attributeName="stop-color" values="#fff1a8; #ffe066; #fff1a8" dur="4s" repeatCount="indefinite" />
                                                            </stop>
                                                            <stop offset="100%" stop-color="#d4af37">
                                                                <animate attributeName="stop-color" values="#d4af37; #b8860b; #d4af37" dur="4s" repeatCount="indefinite" />
                                                            </stop>
                                                        </radialGradient>
                                                    </defs>
                                                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" />
                                                </svg>
                                                <img src="{{ asset('images/awards/medal-icon.svg') }}" alt="Award" class="w-16 h-16 relative z-10">
                                            </div>
                                        @else
                                            <!-- Hexagon-ish Shape for Icon -->
                                            <div class="w-32 h-32 relative flex items-center justify-center">
                                                <svg class="absolute inset-0 w-full h-full text-amber-50 drop-shadow-sm transition-colors duration-500 group-hover:text-amber-100" viewBox="0 0 100 100" fill="currentColor">
                                                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" />
                                                </svg>
                                                <svg class="absolute inset-0 w-full h-full transform scale-90" viewBox="0 0 100 100" fill="url(#goldPulse-{{ $loop->index }}-cert)">
                                                    <defs>
                                                        <radialGradient id="goldPulse-{{ $loop->index }}-cert" cx="50%" cy="50%" r="65%">
                                                            <stop offset="0%" stop-color="#fff1a8">
                                                                <animate attributeName="stop-color" values="#fff1a8; #ffe066; #fff1a8" dur="4s" repeatCount="indefinite" />
                                                            </stop>
                                                            <stop offset="100%" stop-color="#d4af37">
                                                                <animate attributeName="stop-color" values="#d4af37; #b8860b; #d4af37" dur="4s" repeatCount="indefinite" />
                                                            </stop>
                                                        </radialGradient>
                                                    </defs>
                                                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" />
                                                </svg>
                                                <img src="{{ asset('images/awards/certificate-icon.svg') }}" alt="Certificate" class="w-16 h-16 relative z-10">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow flex flex-col justify-start w-full">
                                        <h4 class="font-bold text-[1.4rem] leading-tight text-black mb-3 break-words">{{ $item->title }}</h4>
                                        <p class="text-gray-500 font-medium text-sm break-words">{{ $item->issuer }}</p>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 text-sm py-20 w-full">No achievements found.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- MODAL OVERLAY (Kept existing styling but adapted slightly for white theme) -->
            <div x-show="selectedItem !== null" 
                 x-transition.opacity
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                 style="display: none;">
                
                <div @click.away="selectedItem = null"
                     x-show="selectedItem !== null"
                     x-transition.scale.95
                     class="bg-white max-w-4xl w-full rounded-[2rem] p-10 relative shadow-2xl border border-gray-100 flex flex-col md:flex-row gap-10">
                    
                    <!-- Close Button -->
                    <button @click="selectedItem = null" class="absolute top-6 right-6 text-gray-400 hover:text-black transition-colors bg-gray-50 hover:bg-gray-100 rounded-full p-2 z-20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <!-- Left Content -->
                    <div class="flex-1 flex flex-col justify-center pt-2">
                        <div class="mb-6">
                            <span class="px-4 py-1.5 bg-[#5e17eb] text-white font-semibold text-[11px] uppercase tracking-widest rounded-full mb-6 inline-block" x-text="selectedItem.type"></span>
                            <h4 class="text-4xl font-bold tracking-tight text-black leading-tight mb-3 font-sans" x-text="selectedItem.title"></h4>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider font-sans" x-text="selectedItem.issuer"></p>
                        </div>

                        <div class="h-px bg-gray-100 w-full my-6"></div>

                        <p class="text-gray-600 font-sans leading-relaxed text-[15px]" x-text="selectedItem.description"></p>
                        
                        <div class="mt-8 text-left">
                            <span class="font-semibold text-sm bg-gray-100 text-black px-5 py-2 rounded-full font-sans" x-text="'Awarded: ' + selectedItem.year"></span>
                        </div>
                    </div>

                    <!-- Right Content (Image Slider Placeholder) -->
                    <div class="w-full md:w-[35%] flex-shrink-0">
                        <div class="w-full aspect-[9/16] bg-[#d1d5db] rounded-[1.5rem] overflow-hidden relative shadow-inner">
                            <!-- Placeholder for slider -->
                        </div>
                    </div>
                </div>
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
