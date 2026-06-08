@extends('layouts.app')

@section('title', ($profile->hero_title ?? 'My') . ' | Portfolio')

@section('content')

    <style>
        .grid-bg-section {
            background-image:
                linear-gradient(rgba(104,41,170,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(104,41,170,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            background-attachment: fixed;
        }
    </style>

    <!-- HERO SECTION -->
    <section id="hero" class="relative h-[100vh] flex flex-col justify-between pt-36 text-white overflow-hidden select-none bg-[#111111]">
        
        <!-- Blurred Video Background -->
        @php
            $blurPx = round(($profile->hero_blur_amount ?? 35) * 0.3);
            $opacityVal = max(0.2, 0.7 - (($profile->hero_blur_amount ?? 35) / 100) * 0.35);

            $gradEnabled = $profile->hero_gradient_enabled ?? false;
            $gradType = $profile->hero_gradient_type ?? 'linear';
            $gradAngle = $profile->hero_gradient_angle ?? 180;
            $gradOpacity = $profile->hero_gradient_opacity ?? 100;
            $gradStops = $profile->hero_gradient_stops ?? [
                ['position' => 0, 'color' => '#D9D9D9', 'opacity' => 100],
                ['position' => 100, 'color' => '#737373', 'opacity' => 100],
            ];

            $gradStyle = '';
            if ($gradEnabled && count($gradStops) > 0) {
                // Sort by position
                usort($gradStops, function($a, $b) {
                    return $a['position'] <=> $b['position'];
                });
                
                $gAlpha = $gradOpacity / 100;

                $hexToRgba = function($hex, $alphaPercent) use ($gAlpha) {
                    $alpha = ($alphaPercent / 100) * $gAlpha;
                    $hex = ltrim($hex, '#');
                    if (strlen($hex) == 3) {
                        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                    }
                    if (strlen($hex) == 6) {
                        list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
                        return "rgba($r, $g, $b, $alpha)";
                    }
                    return "rgba(0,0,0,$alpha)";
                };

                $colorStopsStr = implode(', ', array_map(function($stop) use ($hexToRgba) {
                    return $hexToRgba($stop['color'], $stop['opacity']) . " " . $stop['position'] . "%";
                }, $gradStops));

                if ($gradType === 'linear') {
                    $gradStyle = "background: linear-gradient({$gradAngle}deg, $colorStopsStr);";
                } else {
                    $gradStyle = "background: radial-gradient(circle, $colorStopsStr);";
                }
            }
        @endphp
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0"
               style="filter: blur({{ $blurPx }}px); opacity: {{ number_format($opacityVal, 2) }}">
            @if($profile && $profile->hero_video_path)
                <source src="{{ asset('storage/' . $profile->hero_video_path) }}" type="video/mp4">
            @endif
            <source src="{{ asset('videos/bg_showreel_loop.mp4') }}" type="video/mp4">
        </video>

        @if($gradEnabled && $gradStyle)
            <!-- User defined gradient overlay -->
            <div class="absolute inset-0 z-[1] pointer-events-none" style="{{ $gradStyle }}"></div>
        @endif

        <!-- Top Gradient for Navbar legibility -->
        <div class="absolute top-0 left-0 w-full h-48 bg-gradient-to-b from-black/80 to-transparent z-[2] pointer-events-none"></div>
        
        <!-- Center Hero Copy -->
        <div class="max-w-7xl mx-auto px-6 flex-grow flex flex-col justify-center items-center text-center relative z-10 w-full">

            <!-- Hero Typography Container -->
            <div class="inline-flex flex-col items-stretch select-none mx-auto mb-6">

                <!-- Turning Ideas Into (justified) -->
                <div class="flex justify-between w-full font-display uppercase text-white leading-none select-none relative z-10"
                     style="font-size: clamp(12px, 4vw, 45px);">
                    @php $topText = $profile->hero_top_text ?? 'TURNING IDEAS INTO'; @endphp
                    @foreach(explode(' ', $topText) as $word)
                        <span>{{ $word }}</span>
                    @endforeach
                </div>

                <!-- REALITY (thin border, yellow fill, no shadows) -->
                <h1 class="text-yellow-400 font-normal leading-none uppercase font-display tracking-tight select-none text-center"
                    style="font-size: clamp(50px, 18vw, 205.84px); margin-top: -0.12em; -webkit-text-stroke: 1px black;">
                    {{ $profile->hero_title ?? 'REALITY' }}
                </h1>

            </div>

            <!-- One Pixel At A Time -->
            <p class="text-xs sm:text-sm tracking-[0.4em] uppercase text-white/70 mb-10 font-sans">
                {{ $profile->hero_subtitle ?? 'One Pixel At A Time' }}
            </p>

            <!-- Get Started Button -->
            <a href="#projects"
               class="px-8 py-3 bg-transparent border border-white font-sans text-xs font-bold uppercase tracking-wider rounded-none hover:bg-white hover:text-black transition-colors duration-300 relative z-10">
                Get Started
            </a>

        </div>

        <!-- Organic Deep Purple Wave SVG at bottom -->
        <div class="w-full leading-none z-10 -mb-[1px]">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-[40px] md:h-[60px]">
                <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z" fill="#512b81"></path>
            </svg>
        </div>
    </section>

    <!-- SELF INTRO SECTION -->
    <section id="self-intro" class="bg-[#512b81] text-white relative flex flex-col min-h-[90vh]" x-data="{
        slide: 0,
        total: {{ $introSlides->count() }},
        prev() { this.slide = (this.slide - 1 + this.total) % this.total; },
        next() { this.slide = (this.slide + 1) % this.total; }
    }">

        <!-- Section Header -->
        <div class="text-center pt-5 pb-4 px-6">
            <h2 class="text-xl font-display uppercase tracking-[0.3em] text-white">Introduction</h2>
        </div>
        <hr class="border-white/25 mx-0">

        <!-- Slides Wrapper -->
        <div class="flex-1 w-full max-w-5xl mx-auto px-6 md:px-10 py-6 md:py-0 flex flex-col justify-center">

            <!-- Slides Container: mobile = natural flow stack; desktop = fills flex parent -->
            <div class="relative w-full flex-1 intro-slides-container">

                @foreach($introSlides as $index => $slideItem)
                <!-- SLIDE {{ $index + 1 }} -->
                <div x-show="slide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex flex-col md:absolute md:inset-0 md:grid md:gap-12 md:items-center gap-6"
                     style="grid-template-columns: 3fr 2fr;">

                    <!-- Top on mobile: Photo -->
                    <div class="flex items-center justify-center md:order-last md:justify-end md:h-full">
                        <div class="overflow-hidden w-[65vw] max-w-[240px] md:w-full md:max-w-[340px] md:h-auto md:mr-2
                                    {{ $index === 0 ? 'shadow-[5.5px_5.5px_0px_0px_rgba(0,0,0,1)] outline outline-[1.5px] outline-offset-[-1.5px] outline-black' : '' }}"
                             style="aspect-ratio: 3/4; border-radius: {{ $index === 0 ? '24.3% 6.1% 24.3% 6.1% / 18.2% 4.6% 18.2% 4.6%' : '12% / 9%' }};">
                            <img src="{{ $slideItem->image_path ? asset('storage/' . $slideItem->image_path) : ($index === 0 ? asset('images/intro/profile.png') : asset('images/intro/slide'.($index+1).'.jpg')) }}"
                                 alt="{{ $slideItem->title }}"
                                 class="w-full h-full object-cover object-top"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}';">
                        </div>
                    </div>

                    <!-- Bottom on mobile: Text -->
                    <div class="md:order-first">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-sm italic text-white/70 font-sans whitespace-nowrap">{{ $slideItem->chapter_label }}</span>
                            <div class="flex-1 border-t border-dotted border-white/40"></div>
                        </div>
                        <h3 class="font-display whitespace-nowrap text-[clamp(1.25rem,6.5vw,1.875rem)] sm:text-4xl md:text-5xl lg:text-[3.5rem] xl:text-[4rem] text-[#4dd9f0] uppercase leading-none mb-2">
                            {{ $slideItem->title }}
                        </h3>
                        @if($slideItem->subtitle)
                        <p class="text-[10px] sm:text-xs font-sans text-white/50 tracking-wider mb-4 md:mb-8">
                            {{ $slideItem->subtitle }}
                        </p>
                        @endif
                        <div class="space-y-3 md:space-y-5 font-poppins text-sm text-white/80 leading-relaxed md:leading-loose">
                            {!! nl2br(e($slideItem->description)) !!}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        <!-- Navigation: divider + dots + arrows -->
        <hr class="border-white/25 mx-0 mt-2 md:mt-0">
        <div class="py-4 md:py-5 flex items-center justify-center gap-6">

            <!-- Prev Arrow -->
            <button @click="prev()" class="w-8 h-8 flex items-center justify-center border border-white/50 rounded-full hover:bg-white/10 transition-colors duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <!-- Dots -->
            <div class="flex gap-2.5 items-center">
                <template x-for="i in total" :key="i">
                    <button @click="slide = i - 1"
                            :class="slide === i - 1 ? 'bg-white scale-110' : 'bg-white/35 hover:bg-white/60'"
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
    <section id="skills" x-data="{ skillModal: { show: false, name: '', category: '', desc: '', proficiency: 5, image: '' } }" class="w-full bg-[#512b81] text-black pt-16 relative">
        
        <div class="w-full flex flex-col border-t border-black relative z-10">
            
            @php
                $categoryColors = [
                    'Core' => 'bg-[#d0f69a]',
                    'External' => 'bg-[#faf597]'
                ];
            @endphp

            <!-- Header -->
            <div class="w-full bg-[#d0f69a] py-3 text-center border-b border-black">
                <h2 class="font-display text-2xl uppercase tracking-[0.1em] text-[#512b81]">Skills</h2>
            </div>

            <!-- Loop Categories -->
            @foreach($skillsByCategory as $category => $skills)
                <div class="{{ $categoryColors[$category] ?? 'bg-[#d0f69a]' }} w-full flex flex-col border-b border-black">
                    
                    <!-- Mobile Header for Category -->
                    <div class="md:hidden w-full px-6 py-3 border-b border-black/10">
                        <span class="font-mono font-bold tracking-[0.2em] uppercase text-slate-800 text-[10px]">{{ strtoupper($category) }}</span>
                    </div>

                    <!-- Skills Grid -->
                    <div class="md:grid md:grid-cols-[3.5rem_1fr_1fr_1fr_1fr_3.5rem]">
                        
                        <!-- Left Sidebar (Category Name Desktop) -->
                        <div class="hidden md:flex items-center justify-center border-r border-black py-8">
                            <span class="font-mono font-bold tracking-[0.3em] uppercase text-slate-800 -rotate-90 whitespace-nowrap text-xs">
                                {{ strtoupper($category) }}
                            </span>
                        </div>

                        @php
                            $skillCount = $skills->count();
                            $desktopGridClass = 'md:grid-cols-6';
                            if ($skillCount == 1) $desktopGridClass = 'md:grid-cols-1';
                            elseif ($skillCount == 2) $desktopGridClass = 'md:grid-cols-2';
                            elseif ($skillCount == 3) $desktopGridClass = 'md:grid-cols-3';
                            elseif ($skillCount == 4) $desktopGridClass = 'md:grid-cols-4';
                            elseif ($skillCount == 5) $desktopGridClass = 'md:grid-cols-5';
                        @endphp
                        <!-- Skills Cards -->
                        <div class="grid grid-cols-2 {{ $desktopGridClass }} md:col-span-4 w-full">
                            @foreach($skills as $index => $skill)
                                <div @click="skillModal = { show: true, name: '{{ addslashes($skill->name) }}', category: '{{ addslashes($skill->category) }} Skill', desc: '{{ addslashes($skill->tooltip_info ?? '') }}', proficiency: {{ $skill->proficiency ?? 5 }}, image: '{{ !empty($skill->image_path) ? asset('storage/' . $skill->image_path) : '' }}' }" 
                                     class="p-4 md:p-6 border-r border-b md:border-b-0 border-black flex flex-col justify-between min-h-[120px] md:min-h-[260px] transition-all duration-300 hover:bg-black/5 md:hover:bg-gradient-to-br md:hover:from-white/90 md:hover:via-white/40 md:hover:to-transparent cursor-pointer group">
                                    <div class="w-6 h-6 md:w-7 md:h-7 rounded-full border border-black flex items-center justify-center text-[10px] md:text-[11px] font-sans text-black">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="font-poppins font-black text-xs md:text-base uppercase text-black leading-snug mt-4 md:mt-8 group-hover:text-[#FF851B] transition-colors">
                                        {{ $skill->name }}
                                    </h3>
                                </div>
                            @endforeach
                            
                            @if($skillCount % 2 !== 0)
                                <!-- Mobile placeholder for odd counts -->
                                <div class="p-4 border-r border-b border-black flex flex-col justify-center items-center min-h-[120px] bg-black/10 md:hidden cursor-default">
                                    <p class="font-mono text-[9px] uppercase text-black/60 text-center leading-relaxed">
                                        Cooking a skill: actively learning more skills to add here
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Right Sidebar Desktop -->
                        <div class="hidden md:block"></div>
                    </div>
                </div>
            @endforeach
            
            <!-- Tools & Software Used -->
            <div class="w-full bg-[#FF851B] py-3 text-center border-b border-[#783800]/20">
                <h2 class="font-display text-2xl uppercase tracking-[0.1em] text-[#783800]">Tools &amp; Software Used</h2>
            </div>

            <!-- Marquee rows -->
            <div x-data="{ tooltip: { show: false, name: '', desc: '', x: 0, y: 0 }, toolModal: { show: false, name: '', desc: '', image: '', category: '', proficiency: 5 } }" class="relative">
                
                <!-- Custom Cursor Tooltip -->
                <div x-show="tooltip.show" 
                     x-transition.opacity.duration.200ms
                     class="fixed z-50 pointer-events-none bg-[#512b81] text-white p-3 rounded-lg shadow-2xl max-w-xs border border-white/20"
                     :style="`left: ${tooltip.x + 15}px; top: ${tooltip.y + 15}px; transform: translate(0, 0);`"
                     style="display: none;">
                    <div class="font-display font-bold text-sm text-[#d0f69a] mb-1" x-text="tooltip.name"></div>
                    <div class="font-sans text-xs text-white/80 leading-snug" x-show="tooltip.desc" x-text="tooltip.desc"></div>
                </div>

                @foreach($toolsByRow as $rowLabel => $tools)
                    <div class="w-full bg-[#FF851B] text-[#783800] flex border-b border-[#783800]/20 overflow-hidden min-h-[14vh] md:min-h-[11vh]">
                        <div class="w-24 md:w-48 shrink-0 border-r border-[#783800]/30 flex items-center justify-center font-display text-[9px] md:text-sm tracking-widest uppercase text-center leading-tight z-10 px-2">
                            {{ $rowLabel }}
                        </div>
                        <div class="flex-1 flex overflow-hidden relative items-center group/marquee">
                            @php
                                // Duplicate items enough times to guarantee they exceed viewport width
                                $repeatedTools = array_merge($tools->toArray(), $tools->toArray(), $tools->toArray(), $tools->toArray(), $tools->toArray());
                            @endphp
                            <!-- Block 1 -->
                            <div class="animate-marquee flex whitespace-nowrap items-center shrink-0">
                                @foreach($repeatedTools as $item)
                                    <span class="cursor-pointer ml-10 md:ml-16 font-normal font-sans text-xs md:text-xl flex items-center gap-3 group relative transition-opacity duration-300 group-has-[:hover]/marquee:opacity-30 hover:!opacity-100"
                                          @click="toolModal = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', image: '{{ !empty($item['image_path']) ? asset('storage/' . $item['image_path']) : '' }}', category: '{{ addslashes($rowLabel) }}', proficiency: {{ $item['proficiency'] ?? 5 }} }; tooltip.show = false;"
                                          @mouseenter="tooltip = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', x: $event.clientX, y: $event.clientY }"
                                          @mouseleave="tooltip.show = false"
                                          @mousemove="tooltip.x = $event.clientX; tooltip.y = $event.clientY">
                                        @if(!empty($item['image_path']))
                                            <div class="h-10 md:h-14 w-auto flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-110">
                                                <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}" class="h-full w-auto object-contain drop-shadow-md">
                                            </div>
                                        @else
                                            <div class="h-10 w-10 md:h-12 md:w-12 border border-[#783800]/20 rounded-md flex items-center justify-center text-[10px] md:text-xs bg-[#783800]/5 text-[#783800]/80 font-bold uppercase shrink-0">{{ substr($item['name'], 0, 2) }}</div>
                                            <span class="transition-colors font-bold">{{ $item['name'] }}</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                            <!-- Block 2 (Duplicate for seamless loop) -->
                            <div class="animate-marquee flex whitespace-nowrap items-center shrink-0" aria-hidden="true">
                                @foreach($repeatedTools as $item)
                                    <span class="cursor-pointer ml-10 md:ml-16 font-normal font-sans text-xs md:text-xl flex items-center gap-3 group relative transition-opacity duration-300 group-has-[:hover]/marquee:opacity-30 hover:!opacity-100"
                                          @click="toolModal = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', image: '{{ !empty($item['image_path']) ? asset('storage/' . $item['image_path']) : '' }}', category: '{{ addslashes($rowLabel) }}', proficiency: {{ $item['proficiency'] ?? 5 }} }; tooltip.show = false;"
                                          @mouseenter="tooltip = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', x: $event.clientX, y: $event.clientY }"
                                          @mouseleave="tooltip.show = false"
                                          @mousemove="tooltip.x = $event.clientX; tooltip.y = $event.clientY">
                                        @if(!empty($item['image_path']))
                                            <div class="h-10 md:h-14 w-auto flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-110">
                                                <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}" class="h-full w-auto object-contain drop-shadow-md">
                                            </div>
                                        @else
                                            <div class="h-10 w-10 md:h-12 md:w-12 border border-[#783800]/20 rounded-md flex items-center justify-center text-[10px] md:text-xs bg-[#783800]/5 text-[#783800]/80 font-bold uppercase shrink-0">{{ substr($item['name'], 0, 2) }}</div>
                                            <span class="transition-colors font-bold">{{ $item['name'] }}</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Interactive Tool Modal -->
                <template x-teleport="body">
                <div x-show="toolModal.show" style="display: none;" class="relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <!-- Dimmed Backdrop -->
                    <div x-show="toolModal.show"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>

                    <!-- Modal Container -->
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                            <!-- Modal Card -->
                            <div x-show="toolModal.show"
                                 @click.away="toolModal.show = false"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="relative transform overflow-hidden bg-[#111111] border border-[#333] rounded-2xl text-left shadow-[0_0_40px_rgba(255,133,27,0.1)] transition-all sm:my-8 sm:w-full sm:max-w-md flex flex-col">
                                
                                <!-- Close Button -->
                                <button @click="toolModal.show = false" class="absolute top-4 right-4 text-white/50 hover:text-[#FF851B] transition-colors focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="p-8 flex flex-col items-center">
                                    <!-- Image/Icon -->
                                    <div class="w-32 h-32 mb-6 flex items-center justify-center">
                                        <template x-if="toolModal.image">
                                            <img :src="toolModal.image" :alt="toolModal.name" class="max-w-full max-h-full object-contain drop-shadow-lg">
                                        </template>
                                        <template x-if="!toolModal.image">
                                            <div class="text-4xl font-bold uppercase text-[#FF851B]" x-text="toolModal.name.substring(0, 2)"></div>
                                        </template>
                                    </div>

                                    <!-- Title & Category -->
                                    <h3 class="font-display text-3xl text-white mb-1 tracking-wide text-center" x-text="toolModal.name"></h3>
                                    <p class="font-mono text-[10px] uppercase tracking-widest text-[#FF851B] mb-6 text-center border border-[#FF851B]/30 px-3 py-1 rounded-full bg-[#FF851B]/5" x-text="toolModal.category"></p>

                                    <!-- Description -->
                                    <div class="w-full bg-[#1A1A1A] rounded-xl p-5 mb-6 border border-[#333] shadow-inner">
                                        <p class="font-poppins text-sm text-white/70 text-center leading-relaxed" x-text="toolModal.desc || 'A core component of my creative and technical workflow.'"></p>
                                    </div>

                                    <!-- Skill Level Visualization -->
                                    <div class="w-full flex flex-col items-center">
                                        <span class="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-3">Proficiency Rating</span>
                                        <div class="flex gap-2 mb-2">
                                            <!-- 5 Glowing Stars -->
                                            <template x-for="i in 5">
                                                <svg class="w-7 h-7 transition-colors duration-300" 
                                                     :class="i <= toolModal.proficiency ? 'text-[#FF851B] drop-shadow-[0_0_10px_rgba(255,133,27,0.6)]' : 'text-white/10'" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="font-display text-base text-[#FF851B] tracking-widest uppercase" x-text="toolModal.proficiency === 5 ? 'Expert / Master' : (toolModal.proficiency === 4 ? 'Advanced' : (toolModal.proficiency === 3 ? 'Intermediate' : (toolModal.proficiency === 2 ? 'Beginner' : 'Novice')))"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </template>

            </div>
        </div>

        <div class="w-full bg-[#FAF7E6] leading-none">
            <svg viewBox="0 0 1440 75" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-[30px] md:h-[45px] drop-shadow-[0_2px_2px_rgba(0,0,0,0.1)]">
                <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" fill="#FF851B"></path>
            </svg>
        </div>
        </div>

        <!-- Interactive Skill Modal -->
        <template x-teleport="body">
        <div x-show="skillModal.show" style="display: none;" class="relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Dimmed Backdrop -->
            <div x-show="skillModal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <!-- Modal Card -->
                    <div x-show="skillModal.show"
                         @click.away="skillModal.show = false"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative transform overflow-hidden bg-[#111111] border border-[#333] rounded-2xl text-left shadow-[0_0_40px_rgba(255,133,27,0.1)] transition-all sm:my-8 sm:w-full sm:max-w-md flex flex-col">
                        
                        <!-- Close Button -->
                        <button @click="skillModal.show = false" class="absolute top-4 right-4 text-white/50 hover:text-[#FF851B] transition-colors focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="p-8 flex flex-col items-center">
                            <!-- Icon/Image -->
                            <template x-if="skillModal.image">
                                <div class="w-24 h-24 mb-6 flex items-center justify-center">
                                    <img :src="skillModal.image" alt="Skill Logo" class="max-w-full max-h-full object-contain drop-shadow-md">
                                </div>
                            </template>
                            <template x-if="!skillModal.image">
                                <div class="w-24 h-24 mb-6 flex items-center justify-center text-white">
                                    <svg class="w-16 h-16 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </div>
                            </template>

                            <!-- Title & Category -->
                            <h3 class="font-display text-3xl text-white mb-1 tracking-wide text-center" x-text="skillModal.name"></h3>
                            <p class="font-mono text-[10px] uppercase tracking-widest text-[#FF851B] mb-6 text-center border border-[#FF851B]/30 px-3 py-1 rounded-full bg-[#FF851B]/5" x-text="skillModal.category"></p>

                            <!-- Description -->
                            <div class="w-full bg-[#1A1A1A] rounded-xl p-5 mb-6 border border-[#333] shadow-inner">
                                <p class="font-poppins text-sm text-white/70 text-center leading-relaxed" x-text="skillModal.desc || 'A core technical skill utilized to craft highly optimized and premium digital experiences.'"></p>
                            </div>

                            <!-- Skill Level Visualization -->
                            <div class="w-full flex flex-col items-center">
                                <span class="font-mono text-[10px] uppercase tracking-widest text-white/40 mb-3">Proficiency Rating</span>
                                <div class="flex gap-2 mb-2">
                                    <!-- 5 Glowing Stars -->
                                    <template x-for="i in 5">
                                        <svg class="w-7 h-7 transition-colors duration-300" 
                                             :class="i <= skillModal.proficiency ? 'text-[#FF851B] drop-shadow-[0_0_10px_rgba(255,133,27,0.6)]' : 'text-white/10'" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </template>
                                </div>
                                <span class="font-display text-base text-[#FF851B] tracking-widest uppercase" x-text="skillModal.proficiency === 5 ? 'Expert / Master' : (skillModal.proficiency === 4 ? 'Advanced' : (skillModal.proficiency === 3 ? 'Intermediate' : (skillModal.proficiency === 2 ? 'Beginner' : 'Novice')))"></span>
                            </div>
                        </div>
                    </div>
                </div>
        </template>
            </div>
        </div>

    </section>

    <!-- THE BEST WORKS SECTION -->
    @php
        $featuredProjects = $projects->where('is_best_work', true)->values();
    @endphp
    @if($featuredProjects->count() > 0)
    <section id="best-works" class="w-full bg-[#FAF7E6] grid-bg-section text-[#1a1207] pt-16 pb-16 relative overflow-hidden" x-data="{
        slide: 0,
        total: {{ $featuredProjects->count() }},
        prev() { this.slide = (this.slide - 1 + this.total) % this.total; },
        next() { this.slide = (this.slide + 1) % this.total; }
    }">
        <div class="max-w-[1400px] mx-auto px-6 w-full relative z-10">
            <!-- Section Title -->
            <h2 class="text-center font-display text-4xl md:text-5xl uppercase tracking-[0.2em] text-[#1a1207] mb-2" style="text-shadow: 0 4px 20px rgba(0,0,0,0.05);">The Best Works</h2>
            <p class="text-center text-xs font-mono text-[#6829AA] tracking-[0.3em] uppercase mb-12">Handpicked Featured Projects</p>

            <!-- Slider Container -->
            <div class="relative w-full aspect-[4/3] md:aspect-[16/9] max-w-5xl mx-auto rounded-2xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-black/10 group">
                
                @foreach($featuredProjects as $index => $fp)
                <div x-show="slide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full">
                    
                    <!-- Background Image -->
                    <img src="{{ $fp->featured_thumbnail ? asset('storage/' . $fp->featured_thumbnail) : ($fp->thumbnail_path ? asset('storage/' . $fp->thumbnail_path) : asset('images/placeholder.jpg')) }}" 
                         alt="{{ $fp->title }}" class="w-full h-full object-cover">
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 w-full p-5 md:p-12 flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6">
                        <div class="max-w-2xl">
                            <div class="flex items-center gap-3 mb-2 md:mb-3">
                                <span class="px-2 md:px-3 py-1 bg-[#FF851B] text-[#783800] text-[9px] md:text-[10px] font-bold uppercase tracking-widest rounded-sm">{{ $fp->category ?? 'Featured' }}</span>
                                <span class="text-white/60 font-mono text-[10px] md:text-xs uppercase tracking-widest">{{ $fp->year ?? '2025' }}</span>
                            </div>
                            <h3 class="font-display text-3xl md:text-6xl text-white uppercase leading-none mb-2 md:mb-4">{{ $fp->title }}</h3>
                            <p class="font-poppins text-xs md:text-base text-white/80 line-clamp-2 md:line-clamp-3 max-w-xl">
                                {{ $fp->description ?? $fp->subtitle }}
                            </p>
                        </div>
                        
                        <a href="{{ route('portfolio.project.show', $fp->slug) }}" class="shrink-0 group/btn relative inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-white text-black font-sans text-[10px] md:text-xs font-bold uppercase tracking-widest overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2 transition-transform duration-300 group-hover/btn:-translate-y-10">
                                View Case Study
                            </span>
                            <span class="absolute inset-0 z-10 flex items-center justify-center gap-2 translate-y-full transition-transform duration-300 group-hover/btn:translate-y-0 text-white bg-[#6829AA]">
                                Explore Now <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </a>
                    </div>
                </div>
                @endforeach

                <!-- Navigation Controls -->
                <div class="absolute top-1/2 -translate-y-1/2 left-4 right-4 flex justify-between pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button @click="prev()" class="pointer-events-auto w-12 h-12 flex items-center justify-center bg-black/50 hover:bg-[#FF851B] text-white border border-white/20 hover:border-transparent rounded-full backdrop-blur-sm transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="pointer-events-auto w-12 h-12 flex items-center justify-center bg-black/50 hover:bg-[#FF851B] text-white border border-white/20 hover:border-transparent rounded-full backdrop-blur-sm transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                
                <!-- Pagination Indicators -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
                    <template x-for="i in total" :key="i">
                        <button @click="slide = i - 1" 
                                class="h-1.5 transition-all duration-300 rounded-full"
                                :class="slide === i - 1 ? 'w-8 bg-[#FF851B]' : 'w-2 bg-white/30 hover:bg-white/50'">
                        </button>
                    </template>
                </div>

            </div>
        </div>
    </section>
    @endif

    <!-- WORKS AND OUTPUTS SECTION -->
    <section id="works" class="w-full bg-[#FAF7E6] grid-bg-section text-black pt-16 pb-0 relative">
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
                get maxOffset() {
                    const gap = 20;
                    const cardPx = window.innerWidth >= 1024 ? 560 : (window.innerWidth >= 768 ? 560 : window.innerWidth * 0.82);
                    const cw = this.$refs.viewport ? this.$refs.viewport.clientWidth : this.$el.clientWidth;
                    const max = ((this.total + 1) * (cardPx + gap) - gap) - cw;
                    return max > 0 ? max : 0;
                },
                get offset() {
                    const gap = 20;
                    const cardPx = window.innerWidth >= 1024 ? 560 : (window.innerWidth >= 768 ? 560 : window.innerWidth * 0.82);
                    let target = this.current * (cardPx + gap);
                    return target > this.maxOffset ? this.maxOffset : target;
                }
            }" @resize.window="current = current">

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
                <div class="relative" x-ref="viewport">

                    <!-- Left gradient fade -->
                    <div class="absolute left-0 top-0 bottom-0 w-16 md:w-28 pointer-events-none z-10"
                         style="background: linear-gradient(to right, #FAF7E6 15%, transparent 100%);"
                         x-show="offset > 0"
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
                         x-show="offset < maxOffset"
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
                        <div x-ref="track" class="flex gap-5 py-3 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] w-max"
                             :style="'transform: translateX(-' + offset + 'px)'">

                            {{-- Blade renders the real cards — each is a real <a> link --}}
                            @forelse($uiProjects as $index => $proj)
                                <a href="{{ route('portfolio.project.show', $proj->slug) }}"
                                   x-data="{ isDimmed: false }"
                                   class="shrink-0 w-[82vw] md:w-[560px] rounded-2xl relative group bg-white"
                                   :class="{{ $index }} === current ? 'ring-2 ring-black/12' : 'opacity-75'"
                                   style="transition: opacity 0.4s ease, transform 0.28s cubic-bezier(0.34,1.56,0.64,1);"
                                   @mouseenter="if(!isDimmed) { $el.style.transform='translateY(-6px)'; $el.style.opacity='1'; }"
                                   @mouseleave="$el.style.transform=''; $el.style.opacity=''">

                                    <!-- Image / placeholder -->
                                    <div class="relative w-full rounded-2xl overflow-hidden border border-black/10
                                                @if(($proj->use_custom_thumbnail && $proj->thumbnail_path) || $proj->main_video_path) bg-slate-900 @else bg-gradient-to-br from-slate-100 to-slate-200 @endif
                                                flex items-center justify-center">
                                        @if($proj->use_custom_thumbnail && $proj->thumbnail_path)
                                            <img src="{{ Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : asset('storage/' . $proj->thumbnail_path) }}"
                                                 alt="{{ $proj->title }}"
                                                 class="w-full h-auto object-cover">
                                        @elseif($proj->main_media_type === 'video' && $proj->main_video_path)
                                            <video src="{{ asset('storage/' . $proj->main_video_path) }}"
                                                   muted playsinline autoplay loop
                                                   class="w-full h-auto object-cover pointer-events-none"
                                                   x-init="
                                                       let vid = $el;
                                                       let loopStart = {{ $proj->video_loop_start ?? 0 }};
                                                       let loopEnd = {{ $proj->video_loop_end ?? 0 }};
                                                       const initLoop = () => {
                                                           if (loopEnd <= 0) loopEnd = vid.duration || 0;
                                                           if (vid.currentTime < loopStart || (loopEnd > 0 && vid.currentTime > loopEnd)) {
                                                               vid.currentTime = loopStart;
                                                           }
                                                       };
                                                       if (vid.readyState >= 1) {
                                                           initLoop();
                                                       } else {
                                                           vid.addEventListener('loadedmetadata', initLoop);
                                                       }
                                                       vid.addEventListener('timeupdate', () => {
                                                           if (loopEnd > 0 && vid.currentTime >= loopEnd) {
                                                               vid.currentTime = loopStart;
                                                           }
                                                       });
                                                   "></video>
                                        @elseif($proj->main_media_type === 'image' && !empty($proj->main_images))
                                            <div x-data="{ currentSlide: 0, total: {{ count($proj->main_images) }} }"
                                                 x-init="setInterval(() => { currentSlide = (currentSlide + 1) % total }, 3000)"
                                                 class="relative w-full overflow-hidden" style="padding-top: 56.25%;">
                                                @foreach($proj->main_images as $index => $img)
                                                    <img src="{{ asset('storage/' . $img) }}"
                                                         x-show="currentSlide === {{ $index }}"
                                                         x-transition.opacity.duration.700ms
                                                         class="absolute inset-0 w-full h-full object-cover">
                                                @endforeach
                                                <!-- Dots indicator -->
                                                <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-20">
                                                    <template x-for="i in total" :key="i">
                                                        <div class="w-1.5 h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                                             :class="(i - 1) === currentSlide ? 'bg-white scale-125' : 'bg-white/40'"></div>
                                                    </template>
                                                </div>
                                            </div>
                                        @elseif($proj->main_image_path)
                                            <img src="{{ Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : asset('storage/' . $proj->main_image_path) }}"
                                                 alt="{{ $proj->title }}"
                                                 class="w-full h-auto object-cover">
                                        @else
                                            <div class="w-full" style="padding-top: 56.25%;"></div>
                                            <svg class="w-12 h-12 text-black/15 absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                            <!-- Coming Soon Card -->
                            <div class="shrink-0 w-[82vw] md:w-[560px] aspect-video rounded-2xl relative group bg-white/40 border-2 border-dashed border-black/10 flex flex-col items-center justify-center"
                                 :class="{{ $uiProjects->count() }} === current ? 'ring-2 ring-black/12' : 'opacity-75'"
                                 style="transition: opacity 0.4s ease, transform 0.28s cubic-bezier(0.34,1.56,0.64,1);">
                                <div class="text-center opacity-50 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 rounded-full bg-white border-2 border-black/10 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                    <h4 class="font-display text-lg uppercase text-black tracking-wider">More in the works</h4>
                                    <p class="font-mono text-[10px] text-black/60 uppercase tracking-widest mt-1">Coming Soon</p>
                                </div>
                            </div>

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
            $visualProjects = $projects->where('category', 'visual')->shuffle()->values();

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
                               x-data="{ isDimmed: false }"
                               x-show="activeFilter === 'all' || activeFilter === '{{ $proj->medium }}'"
                               x-transition:enter="transition-opacity duration-300"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               x-transition:leave="transition-opacity duration-200"
                               x-transition:leave-start="opacity-100"
                               x-transition:leave-end="opacity-0"
                               class="block w-full break-inside-avoid mb-4 rounded-2xl overflow-hidden relative group bg-white border border-black/8 cursor-pointer"
                               style="transition: transform 0.22s ease;"
                               @mouseenter="if(!isDimmed) $el.style.transform='scale(1.018)'"
                               @mouseleave="$el.style.transform='scale(1)'">

                                <div class="relative w-full overflow-hidden flex items-center justify-center bg-slate-100">
                                    @if(($proj->thumbnail_type === 'video' && $proj->thumbnail_video_path) || ($proj->main_media_type === 'video' && ($proj->main_video_path || $proj->video_url)))
                                        @php
                                            $vidSrc = '';
                                            if ($proj->thumbnail_type === 'video' && $proj->thumbnail_video_path) {
                                                $vidSrc = asset('storage/' . $proj->thumbnail_video_path);
                                            } elseif ($proj->main_media_type === 'video') {
                                                $vidSrc = $proj->main_video_path ? asset('storage/' . $proj->main_video_path) : $proj->video_url;
                                            }
                                        @endphp
                                        <video src="{{ $vidSrc }}"
                                               muted playsinline autoplay loop
                                               class="w-full h-auto object-cover pointer-events-none"
                                               x-init="
                                                   let vid = $el;
                                                   let loopStart = {{ $proj->video_loop_start ?? 0 }};
                                                   let loopEnd = {{ $proj->video_loop_end ?? 0 }};
                                                   const initLoop = () => {
                                                       if (loopEnd <= 0) loopEnd = vid.duration || 0;
                                                       if (vid.currentTime < loopStart || (loopEnd > 0 && vid.currentTime > loopEnd)) {
                                                           vid.currentTime = loopStart;
                                                       }
                                                   };
                                                   const setup = () => {
                                                       if (vid.readyState >= 1) initLoop();
                                                       else vid.addEventListener('loadedmetadata', initLoop);
                                                       vid.addEventListener('timeupdate', () => {
                                                           if (loopEnd > 0 && vid.currentTime >= loopEnd) vid.currentTime = loopStart;
                                                       });
                                                   };
                                                   if (vid.src && vid.src.startsWith('http') && !vid.src.startsWith('blob:')) {
                                                       fetch(vid.src).then(r => r.blob()).then(b => {
                                                           vid.src = URL.createObjectURL(b);
                                                           setup();
                                                           vid.load();
                                                       }).catch(() => setup());
                                                   } else {
                                                       setup();
                                                   }
                                               "></video>
                                    @elseif(!empty($proj->thumbnail_images))
                                        <div x-data="{ currentSlide: 0, total: {{ count($proj->thumbnail_images) }} }"
                                             x-init="setInterval(() => { currentSlide = (currentSlide + 1) % total }, 3500)"
                                             class="relative w-full overflow-hidden">
                                            <!-- To maintain natural aspect ratio for masonry, use the first image for height, rest absolute -->
                                            <img src="{{ asset('storage/' . $proj->thumbnail_images[0]) }}"
                                                 class="w-full h-auto object-cover invisible">
                                            @foreach($proj->thumbnail_images as $index => $img)
                                                <img src="{{ asset('storage/' . $img) }}"
                                                     x-show="currentSlide === {{ $index }}"
                                                     x-transition.opacity.duration.700ms
                                                     class="absolute inset-0 w-full h-full object-cover">
                                            @endforeach
                                            <!-- Dots indicator -->
                                            <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-20">
                                                <template x-for="i in total" :key="i">
                                                    <div class="w-1.5 h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                                         :class="(i - 1) === currentSlide ? 'bg-white scale-125' : 'bg-white/40'"></div>
                                                </template>
                                            </div>
                                        </div>
                                    @elseif($proj->thumbnail_path)
                                        <img src="{{ Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : asset('storage/' . $proj->thumbnail_path) }}"
                                             alt="{{ $proj->title }}"
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
    <section id="achievements" class="pt-14 pb-12 lg:py-24 bg-white text-black font-sans border-b border-gray-100" 
             x-data="{ activeTab: 'all', selectedItem: null }">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-6">
            
            <!-- Top Header & Pills -->
            <div class="text-center mb-8 lg:mb-24">
                <h3 class="text-[2rem] lg:text-[3rem] font-bold tracking-tight text-black mb-5 lg:mb-8 font-display uppercase">Achievements</h3>

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
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8 lg:gap-24">
                
                <!-- Left Content -->
                <div class="w-full lg:w-[35%] text-center lg:text-left pt-2 lg:pt-4 order-2 lg:order-1">
                    <!-- Mobile heading: single line, centered -->
                    <h2 class="text-[1.75rem] lg:text-[3.25rem] font-bold tracking-tight text-black mb-3 lg:mb-6 leading-tight font-poppins">
                        <span class="lg:hidden">We've got <span x-text="activeTab === 'all' ? 'Great Achievements' : (activeTab === 'award' ? 'Amazing Awards' : 'Official Certificates')"></span></span>
                        <span class="hidden lg:block">
                            We've got <br>
                            <span x-text="activeTab === 'all' ? 'Great' : (activeTab === 'award' ? 'Amazing' : 'Official')"></span> <br>
                            <span x-text="activeTab === 'all' ? 'Achievements' : (activeTab === 'award' ? 'Awards' : 'Certificates')"></span>
                        </span>
                    </h2>
                    <p class="text-gray-500 text-sm lg:text-lg leading-relaxed mb-6 lg:mb-12 font-medium font-poppins max-w-xs mx-auto lg:mx-0 lg:max-w-none">
                        Being appreciated for the work we do means the world to us. It translates beautifully into our official ratings, continuous learning, and industry recognition.
                    </p>
                    
                    <!-- Arrow Controls (Left Column, desktop only) -->
                    <div class="hidden lg:flex items-center gap-4"
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
                <div class="lg:w-[65%] w-full relative order-1 lg:order-2"
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
                    <div id="achievement-carousel" class="flex gap-5 lg:gap-8 overflow-x-auto snap-x snap-mandatory pb-10 lg:pb-16 pt-6 lg:pt-8 px-4 -mx-4 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none; scroll-padding-left: calc(50vw - min(39vw, 150px) - 2.25rem);">
                        
                        <!-- Mobile centering spacer (start) -->
                        <div class="flex-none lg:hidden" style="width: calc(50vw - min(39vw, 150px) - 2.25rem);" aria-hidden="true"></div>

                        <!-- Mixed Achievements Loop -->
                        @forelse($achievementsByType->flatten(1)->sortByDesc('year') as $item)
                            @php
                                $isAward = $item->type === 'award';
                                $itemType = $isAward ? 'Award' : 'Certificate';
                            @endphp
                            <!-- Card -->
                            <div x-show="activeTab === 'all' || activeTab === '{{ $item->type }}'"
                                 x-transition.opacity
                                 @click="selectedItem = { title: {{ Js::from($item->title) }}, issuer: {{ Js::from($item->issuer) }}, year: {{ Js::from($item->year) }}, description: {{ Js::from($item->description) }}, type: '{{ $itemType }}', media_path: {{ Js::from($item->media_path ? asset('storage/' . $item->media_path) : null) }} }"
                                 class="flex-none snap-center lg:snap-start group cursor-pointer relative"
                                 style="width: min(78vw, 300px);">
                                 
                                <div class="relative overflow-hidden bg-white rounded-[1.5rem] lg:rounded-[2rem] p-6 lg:p-10 flex flex-col items-center text-center transition-all duration-500 transform shadow-[0_8px_30px_rgb(0,0,0,0.06)] group-hover:-translate-y-2 group-hover:shadow-[0_20px_40px_rgb(94,23,235,0.12)] group-hover:bg-[#E5C14D] group-hover:border-[#C4A030] border-2 border-gray-100 h-auto lg:h-[420px] min-h-[300px]">
                                    
                                    <!-- Shine Effect -->
                                    <div class="absolute top-0 -left-[150%] w-[100%] h-full bg-gradient-to-r from-transparent via-white/60 to-transparent transform -skew-x-12 transition-all duration-700 ease-in-out group-hover:left-[150%] z-20 pointer-events-none"></div>

                                    <div class="w-full flex justify-center mb-6 lg:mb-10 mt-2 lg:mt-4 relative z-10">
                                        @if($isAward)
                                            <!-- Hexagon-ish Shape for Icon -->
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 relative flex items-center justify-center">
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
                                                <img src="{{ asset('images/awards/medal-icon.svg') }}" alt="Award" class="w-12 h-12 lg:w-16 lg:h-16 relative z-10">
                                            </div>
                                        @else
                                            <!-- Hexagon-ish Shape for Icon -->
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 relative flex items-center justify-center">
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
                                                <img src="{{ asset('images/awards/certificate-icon.svg') }}" alt="Certificate" class="w-12 h-12 lg:w-16 lg:h-16 relative z-10">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow flex flex-col justify-start w-full">
                                        <h4 class="font-bold text-[1.1rem] lg:text-[1.4rem] leading-tight text-black mb-2 lg:mb-3 break-words">{{ $item->title }}</h4>
                                        <p class="text-gray-500 font-medium text-xs lg:text-sm break-words">{{ $item->issuer }}</p>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 text-sm py-20 w-full">No achievements found.</p>
                        @endforelse

                        <!-- Mobile centering spacer (end) -->
                        <div class="flex-none lg:hidden" style="width: calc(50vw - min(39vw, 150px) - 2.25rem);" aria-hidden="true"></div>
                    </div>
                    {{-- ^ closes achievement-carousel track --}}

                    <!-- Mobile-only Prev/Next arrows (below carousel) -->
                    <div class="flex lg:hidden items-center justify-center gap-6 mt-2 pb-2"
                         x-data="{ 
                            scrollNext() { document.getElementById('achievement-carousel').scrollBy({ left: 300, behavior: 'smooth' }) },
                            scrollPrev() { document.getElementById('achievement-carousel').scrollBy({ left: -300, behavior: 'smooth' }) }
                         }">
                        <button @click="scrollPrev()" class="w-11 h-11 flex items-center justify-center border border-gray-200 rounded-full text-gray-400 active:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </button>
                        <div class="w-10 h-[2px] bg-gray-100 relative">
                            <div class="absolute left-0 top-0 h-full w-1/2 bg-[#5e17eb] rounded-full"></div>
                        </div>
                        <button @click="scrollNext()" class="w-11 h-11 flex items-center justify-center border border-[#5e17eb]/30 rounded-full text-[#5e17eb] active:bg-[#5e17eb]/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
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
                     class="bg-white max-w-4xl w-full h-[85vh] md:h-auto rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-10 relative shadow-2xl border border-gray-100 flex flex-col md:flex-row gap-6 md:gap-10 max-h-[90vh] overflow-hidden md:overflow-y-auto">
                    
                    <!-- Close Button -->
                    <button @click="selectedItem = null" class="absolute top-4 right-4 md:top-6 md:right-6 text-gray-400 hover:text-black transition-colors bg-gray-50 hover:bg-gray-100 rounded-full p-2 z-20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <!-- Left Content -->
                    <div class="flex-1 flex flex-col justify-center pt-2 md:pt-4 min-h-0 min-w-0">
                        <div class="mb-4 md:mb-6 px-4 md:px-0 shrink-0 text-center md:text-left">
                            <span class="px-3 md:px-4 py-1 md:py-1.5 bg-[#5e17eb] text-white font-semibold text-[10px] md:text-[11px] uppercase tracking-widest rounded-full mb-3 md:mb-6 inline-block" x-text="selectedItem.type"></span>
                            <h4 class="text-2xl md:text-4xl font-bold tracking-tight text-black leading-tight mb-2 md:mb-3 font-sans line-clamp-2 md:line-clamp-none break-words" x-text="selectedItem.title"></h4>
                            <p class="text-xs md:text-sm font-semibold text-gray-500 uppercase tracking-wider font-sans break-words" x-text="selectedItem.issuer"></p>
                        </div>

                        <!-- Mobile Image (Interlaced, Adaptive) -->
                        <div class="flex-1 min-h-[100px] flex items-center justify-center mb-4 md:mb-6 md:hidden">
                            <div class="h-full aspect-[9/16] bg-[#d1d5db] rounded-[1.25rem] overflow-hidden relative shadow-inner max-w-full">
                                <template x-if="selectedItem && selectedItem.media_path">
                                    <img :src="selectedItem.media_path" class="w-full h-full object-cover" alt="Achievement Media">
                                </template>
                            </div>
                        </div>

                        <div class="h-px bg-gray-100 w-full mb-4 md:mb-6 hidden md:block shrink-0"></div>

                        <p class="text-gray-600 font-sans leading-relaxed text-[13px] md:text-[15px] shrink-0 line-clamp-3 md:line-clamp-none text-center md:text-left break-words" x-text="selectedItem.description"></p>
                        
                        <div class="mt-4 md:mt-8 text-center md:text-left shrink-0">
                            <span class="font-semibold text-[11px] md:text-sm bg-gray-100 text-black px-4 md:px-5 py-2 rounded-full font-sans inline-block" x-text="'Awarded: ' + selectedItem.year"></span>
                        </div>
                    </div>

                    <!-- Right Content (Image Slider Placeholder - Desktop only) -->
                    <div class="hidden md:block w-full md:w-[35%] flex-shrink-0">
                        <div class="w-full aspect-[9/16] bg-[#d1d5db] rounded-[1.5rem] overflow-hidden relative shadow-inner">
                            <template x-if="selectedItem && selectedItem.media_path">
                                <img :src="selectedItem.media_path" class="w-full h-full object-cover" alt="Achievement Media">
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- WORK EXPERIENCE SECTION -->
    <section id="experience" class="relative overflow-hidden flex flex-col" style="background:#0d0d0d; min-height: 100vh;" x-data="{ activeIndex: null, bgIndex: 0, select(i) { this.activeIndex = this.activeIndex === i ? null : i; } }">
        <style>
            #experience ::-webkit-scrollbar { width: 4px; }
            #experience ::-webkit-scrollbar-track { background: transparent; }
            #experience ::-webkit-scrollbar-thumb { background: rgba(255,133,27,0.25); border-radius: 2px; }
            #experience ::-webkit-scrollbar-button { display: none; height: 0; }
        </style>

        <!-- ── Background media ── -->
        @if(isset($profile) && $profile->exp_default_bg_mode === 'custom')
            <div class="absolute inset-0 z-0" aria-hidden="true">
                <!-- Custom Global Media for Unselected State -->
                <div x-show="activeIndex === null" x-transition.opacity.duration.1500ms class="absolute inset-0">
                    @if($profile->exp_default_bg_type === 'video' && $profile->exp_default_bg_media_path)
                        <video src="{{ asset('storage/' . $profile->exp_default_bg_media_path) }}" autoplay loop muted playsinline class="w-full h-full object-cover opacity-60"></video>
                    @elseif($profile->exp_default_bg_type === 'slideshow' && !empty($profile->exp_default_bg_gallery_images))
                        <div x-data="{ sIndex: 0, sTotal: {{ count($profile->exp_default_bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === null) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                            @foreach($profile->exp_default_bg_gallery_images as $slideIndex => $sImage)
                                <img src="{{ asset('storage/' . $sImage) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                            @endforeach
                        </div>
                    @elseif($profile->exp_default_bg_media_path)
                        <img src="{{ asset('storage/' . $profile->exp_default_bg_media_path) }}" class="w-full h-full object-cover opacity-60">
                    @endif
                </div>

                <!-- Individual Experience Media when selected -->
                @foreach($experiences as $i => $exp)
                    <div x-show="activeIndex === {{ $i }}"
                         x-transition.opacity.duration.1500ms
                         class="absolute inset-0">
                         @if($exp->bg_media_type === 'video' && $exp->bg_media_path)
                             <video src="{{ asset('storage/' . $exp->bg_media_path) }}" autoplay loop muted playsinline class="w-full h-full object-cover opacity-60"></video>
                         @elseif($exp->bg_media_type === 'slideshow' && !empty($exp->bg_gallery_images))
                             <div x-data="{ sIndex: 0, sTotal: {{ count($exp->bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === {{ $i }}) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                                 @foreach($exp->bg_gallery_images as $slideIndex => $sImage)
                                     <img src="{{ asset('storage/' . $sImage) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                                 @endforeach
                             </div>
                         @elseif($exp->bg_media_path)
                             <img src="{{ asset('storage/' . $exp->bg_media_path) }}" class="w-full h-full object-cover opacity-60">
                         @elseif($exp->image_path)
                             <img src="{{ asset('storage/' . $exp->image_path) }}" class="w-full h-full object-cover opacity-60">
                         @endif
                    </div>
                @endforeach

                <!-- Muted dark overlay so text pops -->
                <div class="absolute inset-0 bg-black/85 transition-opacity duration-500" :class="activeIndex !== null ? 'opacity-90' : 'opacity-70'"></div>
                <!-- Extra vignette gradient top & bottom -->
                <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(13,13,13,0.9) 0%, transparent 25%, transparent 75%, rgba(13,13,13,0.95) 100%); pointer-events: none;"></div>
            </div>
        @else
            <!-- Original Cycling Behavior -->
            <div class="absolute inset-0 z-0" aria-hidden="true" x-init="setInterval(() => { if (activeIndex === null && {{ count($experiences) }} > 0) bgIndex = (bgIndex + 1) % {{ count($experiences) }} }, 5000)">
                @foreach($experiences as $i => $exp)
                    <div x-show="activeIndex === {{ $i }} || (activeIndex === null && bgIndex === {{ $i }})"
                         x-transition.opacity.duration.1500ms
                         class="absolute inset-0">
                         @if($exp->bg_media_type === 'video' && $exp->bg_media_path)
                             <video src="{{ asset('storage/' . $exp->bg_media_path) }}" autoplay loop muted playsinline class="w-full h-full object-cover opacity-60"></video>
                         @elseif($exp->bg_media_type === 'slideshow' && !empty($exp->bg_gallery_images))
                             <div x-data="{ sIndex: 0, sTotal: {{ count($exp->bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === {{ $i }} || (activeIndex === null && bgIndex === {{ $i }})) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                                 @foreach($exp->bg_gallery_images as $slideIndex => $sImage)
                                     <img src="{{ asset('storage/' . $sImage) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                                 @endforeach
                             </div>
                         @elseif($exp->bg_media_path)
                             <img src="{{ asset('storage/' . $exp->bg_media_path) }}" class="w-full h-full object-cover opacity-60">
                         @elseif($exp->image_path)
                             <img src="{{ asset('storage/' . $exp->image_path) }}" class="w-full h-full object-cover opacity-60">
                         @endif
                    </div>
                @endforeach
                <!-- Muted dark overlay so text pops -->
                <div class="absolute inset-0 bg-black/85 transition-opacity duration-500" :class="activeIndex !== null ? 'opacity-90' : 'opacity-70'"></div>
                <!-- Extra vignette gradient top & bottom -->
                <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(13,13,13,0.9) 0%, transparent 25%, transparent 75%, rgba(13,13,13,0.95) 100%); pointer-events: none;"></div>
            </div>
        @endif

        <!-- Wavy top divider (from white achievements) -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-10 scale-x-[1.01] -translate-y-px">
            <svg class="relative block w-[calc(100%+2px)] h-[40px] sm:h-[60px] md:h-[80px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,96L80,112C160,128,320,160,480,165.3C640,171,800,149,960,133.3C1120,117,1280,107,1360,101.3L1440,96L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
            </svg>
        </div>

        <div class="relative z-20 flex flex-col max-w-[1200px] mx-auto w-full px-6 pt-28 pb-24"
             style="flex: 1;">

            <!-- Section header -->
            <div class="text-center mb-16">
                <p class="font-mono text-[11px] uppercase tracking-[0.35em] text-[#FF851B] mb-3">Career Path</p>
                <h2 class="font-display text-[2rem] sm:text-[3rem] uppercase tracking-[0.15em] text-white leading-none" style="text-shadow: 0 0 40px rgba(255,133,27,0.3);">Work Experience</h2>
            </div>

            <!-- Layout wrapper: transitions between centered (default) and split (active) -->
            <div class="relative" style="flex: 1; display: flex; align-items: flex-start;">

                <!-- LEFT: Content panel — only visible when an item is active, slides in from left -->
                <div x-show="activeIndex !== null"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 md:-translate-x-8 translate-y-8 md:translate-y-0"
                     x-transition:enter-end="opacity-100 translate-x-0 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-x-0 translate-y-0"
                     x-transition:leave-end="opacity-0 md:-translate-x-8 translate-y-8 md:translate-y-0"
                     class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex flex-col p-6 pt-20 h-[100dvh] md:relative md:inset-auto md:z-auto md:bg-transparent md:backdrop-blur-none md:p-0 md:flex-1 md:h-[calc(100vh-12rem)] md:pr-8 md:sticky md:top-28 overflow-hidden"
                     style="display: none;">
                     
                    <!-- Back to Timeline Button -->
                    <button @click="activeIndex = null" class="group flex items-center gap-2 text-[#FF851B] hover:text-[#ff9c45] transition-colors mb-4 md:mb-0 md:absolute md:-top-12 md:left-0 z-[110] text-[10px] sm:text-xs font-mono uppercase tracking-[0.2em] shrink-0 w-fit">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Timeline
                    </button>

                    @foreach($experiences as $i => $exp)
                        <div x-show="activeIndex === {{ $i }}"
                             x-transition:enter="transition ease-out duration-400"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="flex flex-col h-full" style="min-height: 0;">

                            <!-- STICKY: Year Banner -->
                            <div class="flex-shrink-0 pt-4 pb-2">
                                <div class="block w-full border border-white/10 rounded-xl px-5 py-4 backdrop-blur-md" style="background: rgba(255,255,255,0.05);">
                                    <div class="font-poppins font-black text-[1.4rem] sm:text-[1.8rem] text-[#FF851B] leading-none tracking-wide">{{ $exp->role }}</div>
                                    <div class="font-mono text-[10px] uppercase tracking-[0.3em] text-white/50 mt-2 flex items-center gap-2 flex-wrap">
                                        <span class="text-white">{{ $exp->company }}</span>
                                        <span class="text-white/30">·</span>
                                        <span>{{ $exp->duration }}</span>
                                        @if($exp->is_active)
                                            <span class="text-white/30">·</span>
                                            <span class="text-[#a3ff6b] border border-[#a3ff6b]/30 px-1.5 py-0.5 rounded text-[8px] bg-[#a3ff6b]/10">Present</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- SCROLLABLE: Description + Image -->
                            <div class="flex-1 overflow-y-auto pr-2 pb-5 space-y-5" style="scrollbar-width: thin; scrollbar-color: rgba(255,133,27,0.15) transparent;">
                                <div class="prose prose-invert max-w-none text-white/80">
                                    @php
                                        $blocks = is_string($exp->body_content) ? json_decode($exp->body_content, true) : $exp->body_content;
                                    @endphp
                                    @if(is_array($blocks) && count($blocks) > 0)
                                        @foreach($blocks as $index => $block)
                                            @php
                                                $type = $block['type'] ?? '';
                                            @endphp
                                            @switch($type)
                                                @case('heading2')
                                                    <h2 class="mt-8 mb-4 font-display text-xl font-bold text-white tracking-wide" style="font-family: 'Bitcount Single', monospace;">{!! $block['content'] ?? '' !!}</h2>
                                                    @break
                                                @case('heading3')
                                                    <h3 class="mt-6 mb-3 font-display text-lg font-bold text-white/90 tracking-wide" style="font-family: 'Bitcount Single', monospace;">{!! $block['content'] ?? '' !!}</h3>
                                                    @break
                                                @case('paragraph')
                                                    <p class="font-poppins text-sm leading-[1.8] mb-5">{!! $block['content'] ?? '' !!}</p>
                                                    @break
                                                @case('bullet')
                                                    <div class="flex gap-2 mb-3 font-poppins text-sm leading-[1.8]">
                                                        <span class="text-[#FF851B] mt-1 shrink-0">•</span>
                                                        <div>{!! $block['content'] ?? '' !!}</div>
                                                    </div>
                                                    @break
                                                @case('numbered')
                                                    <div class="flex gap-2 mb-3 font-poppins text-sm leading-[1.8]">
                                                        <span class="text-[#FF851B] font-mono text-[10px] mt-1.5 shrink-0">{{ $loop->iteration }}.</span>
                                                        <div>{!! $block['content'] ?? '' !!}</div>
                                                    </div>
                                                    @break
                                                @case('quote')
                                                    <blockquote class="font-poppins border-l-[3px] border-[#FF851B] pl-5 my-6 py-1 italic text-white/60 font-medium">
                                                        {!! $block['content'] ?? '' !!}
                                                    </blockquote>
                                                    @break
                                                @case('code')
                                                    <pre class="bg-black/50 border border-white/10 rounded-lg p-4 my-5 overflow-x-auto"><code class="font-mono text-xs text-[#a3ff6b]">{!! $block['content'] ?? '' !!}</code></pre>
                                                    @break
                                                @case('image')
                                                    @if(!empty($block['src']))
                                                        <figure class="my-6">
                                                            <img src="{{ $block['src'] }}" alt="{{ $block['caption'] ?? '' }}" class="w-full rounded-xl shadow-md border border-white/10">
                                                            @if(!empty($block['caption']))
                                                                <figcaption class="text-center mt-3 font-mono text-[9px] uppercase tracking-widest text-white/40">{{ $block['caption'] }}</figcaption>
                                                            @endif
                                                        </figure>
                                                    @endif
                                                    @break
                                                @case('divider')
                                                    <hr class="my-8 border-t border-white/10">
                                                    @break
                                            @endswitch
                                        @endforeach
                                    @else
                                        <p class="text-white/70 font-sans text-[0.85rem] leading-[1.8] whitespace-pre-wrap">{{ $exp->description ?: 'No description added yet.' }}</p>
                                    @endif
                                </div>

                                <div class="h-8"></div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- RIGHT / CENTER: Timeline — centered when nothing active, right-aligned when active -->
                <div class="flex flex-col relative transition-all duration-500 ease-in-out pb-20"
                     :class="activeIndex !== null
                        ? 'w-full md:w-[320px] lg:w-[380px] shrink-0 pl-4 md:pl-8 py-4'
                        : 'w-full max-w-[500px] mx-auto px-6 py-4'"
                     style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.05) transparent;">

                    <!-- Timeline entries -->
                    <div class="flex flex-col flex-1">
                        @forelse($experiences as $i => $exp)
                        <div class="flex">
                            <!-- Connector column -->
                            <div class="flex flex-col items-center mr-6 md:mr-8 w-6 shrink-0">
                                <!-- Dot -->
                                <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full transition-all duration-300 shrink-0 flex items-center justify-center relative border border-white/10"
                                     :class="activeIndex === {{ $i }} ? 'bg-[#a3ff6b] shadow-[0_0_12px_rgba(163,255,107,0.5)] scale-110' : 'bg-black/80'">
                                    @if($exp->is_active)
                                    <!-- Active indicator -->
                                    <div class="absolute w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full transition-all duration-300 border-[1.5px] border-dotted border-white"
                                         :class="activeIndex === {{ $i }} ? 'bg-[#0A8C5E]' : 'bg-[#FF851B]'"></div>
                                    @endif
                                </div>
                                <!-- Line -->
                                <div class="w-[2px] sm:w-[3px] flex-1 my-2 transition-all duration-300"
                                     :class="activeIndex === {{ $i }} ? 'bg-[#a3ff6b]/50' : 'bg-white/10'"></div>
                            </div>

                            <!-- Content column -->
                            <button type="button" @click="select({{ $i }})"
                                    class="pb-12 pt-0 md:pt-1 text-left transition-all duration-300 focus:outline-none flex-1 group"
                                    :class="activeIndex === {{ $i }} ? 'opacity-100' : 'opacity-50 hover:opacity-80'">
                                <!-- Job Title & Duration (Modified per request) -->
                                <div class="font-poppins font-bold text-[1.1rem] sm:text-[1.3rem] tracking-wide leading-none mb-3 transition-colors"
                                     :class="activeIndex === {{ $i }} ? 'text-[#a3ff6b]' : 'text-white'">
                                    {{ $exp->role }}
                                </div>
                                <!-- Bullets -->
                                <ul class="space-y-2.5 font-sans text-[0.85rem] sm:text-[0.9rem] text-white/70 list-none ml-4">
                                    <li class="relative before:content-[''] before:absolute before:w-1.5 before:h-1.5 before:bg-white/40 group-hover:before:bg-white/70 transition-all duration-300 before:rounded-full before:left-[-16px] before:top-[8px] uppercase tracking-wider font-bold">
                                        {{ $exp->company }}
                                    </li>
                                    <li class="relative before:content-[''] before:absolute before:w-1.5 before:h-1.5 before:bg-white/40 group-hover:before:bg-white/70 transition-all duration-300 before:rounded-full before:left-[-16px] before:top-[8px]">
                                        {{ $exp->duration }}
                                    </li>
                                </ul>
                            </button>
                        </div>
                        @empty
                        <p class="text-white/30 font-mono text-xs uppercase tracking-widest pl-12 pb-10">No experiences yet.</p>
                        @endforelse

                        <!-- Bottom Indicator -->
                        <div class="flex">
                            <!-- Connector column -->
                            <div class="flex flex-col items-center mr-6 md:mr-8 w-6 shrink-0">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full border-[2.5px] border-dashed border-white/40 shrink-0"></div>
                            </div>
                            <!-- Content column -->
                            <div class="pb-4 pt-[-2px] sm:pt-0 flex-1">
                                <p class="font-mono text-white/40 text-[10px] sm:text-[11px] uppercase tracking-[0.2em] mb-4">The journey is still on it's way</p>
                                <a href="#contact" class="font-display text-[1.2rem] sm:text-[1.5rem] text-white hover:text-[#FF851B] transition-colors duration-300 uppercase tracking-wider leading-tight block">
                                    Be part of my<br>experience →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </section>



    <!-- COLLABORATE (CONTACT) SECTION -->
    <section id="contact" class="bg-[#161616] text-white relative pt-0 pb-10">


        <div class="max-w-7xl mx-auto px-6 pt-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <!-- Contact info cards -->
                <div class="lg:col-span-5">
                    <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Collaborate</h2>
                    <h3 class="text-3xl sm:text-4xl font-black tracking-tight leading-none mb-6 text-white">Let's craft something premium together</h3>
                    <p class="text-slate-400 text-sm sm:text-base leading-relaxed mb-8">
                        Whether you want to discuss a new full-time role, a freelance project, or just want to connect over software craftsmanship—my inbox is always open.
                    </p>

                    <div class="space-y-4">
                        <!-- Email Card -->
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-[#ff6b00]/10 border border-[#ff6b00]/30 text-[#ff6b00] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-mono">Direct Email</p>
                                <a href="mailto:{{ $profile->email }}" class="text-sm font-bold text-white hover:text-[#ff6b00] transition-colors">{{ $profile->email }}</a>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-[#ff6b00]/10 border border-[#ff6b00]/30 text-[#ff6b00] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-mono">Location</p>
                                <p class="text-sm font-bold text-white">{{ $profile->location ?? 'Silicon Valley, CA, USA' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div class="lg:col-span-7">
                    <form action="{{ route('portfolio.contact') }}" method="POST" class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-sm space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 font-mono">Your Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required
                                       value="{{ old('name') }}"
                                       class="w-full bg-white/5 border border-white/15 focus:border-[#ff6b00] rounded-xl px-4 py-3 text-white text-sm outline-none transition-all duration-200 placeholder-slate-600">
                                @error('name')
                                    <p class="text-xs text-rose-400 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 font-mono">Your Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required
                                       value="{{ old('email') }}"
                                       class="w-full bg-white/5 border border-white/15 focus:border-[#ff6b00] rounded-xl px-4 py-3 text-white text-sm outline-none transition-all duration-200 placeholder-slate-600">
                                @error('email')
                                    <p class="text-xs text-rose-400 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 font-mono">Subject (Optional)</label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject"
                                   value="{{ old('subject') }}"
                                   class="w-full bg-white/5 border border-white/15 focus:border-[#ff6b00] rounded-xl px-4 py-3 text-white text-sm outline-none transition-all duration-200 placeholder-slate-600">
                            @error('subject')
                                <p class="text-xs text-rose-400 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 font-mono">Message</label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="5"
                                      required
                                      class="w-full bg-white/5 border border-white/15 focus:border-[#ff6b00] rounded-xl px-4 py-3 text-white text-sm outline-none transition-all duration-200 resize-none placeholder-slate-600">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-400 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full py-4 bg-[#ff6b00] hover:bg-white hover:text-black text-white font-bold text-xs uppercase tracking-widest rounded-xl transition-all duration-300">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
