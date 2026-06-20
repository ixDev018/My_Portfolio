@extends('layouts.app')

@section('title', 'IX-Media | Portfolio')

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
    <section id="hero" x-data="{ 
        sectionVisible: false, 
        passedYellow: false, 
        initAnim() { 
            if(this.sectionVisible) return; 
            this.sectionVisible = true; 
            setTimeout(() => this.passedYellow = true, 1985); 
        } 
    }" x-init="
        if ($refs.bgVideo && $refs.bgVideo.readyState >= 3) { initAnim(); }
        setTimeout(() => initAnim(), 2500);
    " class="relative min-h-[calc(100vh+40px)] md:min-h-[calc(100vh+60px)] pb-[40px] md:pb-[60px] flex flex-col justify-between pt-36 text-white overflow-hidden select-none bg-[#111111]">
        
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
        <video x-ref="bgVideo" @playing="initAnim()" preload="auto" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0"
               style="filter: blur({{ $blurPx }}px); opacity: {{ number_format($opacityVal, 2) }}">
            @if($profile && $profile->hero_video_path)
                <source src="{{ (Str::startsWith($profile->hero_video_path, 'http') ? $profile->hero_video_path : ((Str::startsWith($profile->hero_video_path, 'images/') || Str::startsWith($profile->hero_video_path, 'videos/')) ? asset($profile->hero_video_path) : Storage::url($profile->hero_video_path))) }}" type="video/mp4">
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
        <div class="max-w-7xl mx-auto px-6 flex-grow flex flex-col justify-center items-center text-center relative z-10 w-full -mt-[20vh] sm:-mt-[15vh] md:-mt-12">

            <!-- Hero Typography Container -->
            <div class="flex flex-col items-center select-none mx-auto mb-6 w-full">

                <!-- Turning Ideas Into (centered) -->
                <div class="flex justify-center gap-x-3 sm:gap-x-4 md:gap-x-10 w-full font-display uppercase leading-none select-none relative z-10"
                     style="font-size: clamp(18px, 5vw, 45px);">
                    @php $topText = $profile->hero_top_text ?? 'TURNING IDEAS INTO'; @endphp
                    @foreach(explode(' ', $topText) as $index => $word)
                        @if(strtoupper($word) === 'IDEAS')
                            <span class="inline-block opacity-0 translate-y-8"
                                  :class="[sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8', passedYellow ? 'text-white' : 'text-yellow-400']"
                                  style="transition: transform 1323ms ease-out {{ 551 + ($index * 331) }}ms, opacity 1323ms ease-out {{ 551 + ($index * 331) }}ms, color 1323ms ease-in-out 0ms;">
                                {{ $word }}
                            </span>
                        @else
                            <span class="inline-block opacity-0 translate-y-8 text-white"
                                  :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                                  style="transition: transform 1323ms ease-out {{ 551 + ($index * 331) }}ms, opacity 1323ms ease-out {{ 551 + ($index * 331) }}ms;">
                                {{ $word }}
                            </span>
                        @endif
                    @endforeach
                </div>

                <!-- REALITY Custom Mask Animation Styles -->
                <style>
                    .hero-reality-effect {
                        background-image: linear-gradient(to right, #facc15 0%, #facc15 33.33%, white 66.66%, white 100%);
                        background-size: 300% auto;
                        background-position: 100% center;
                        color: transparent;
                        -webkit-background-clip: text;
                        background-clip: text;
                        
                        -webkit-mask-image: linear-gradient(to right, black 0%, black 33.33%, transparent 66.66%, transparent 100%);
                        mask-image: linear-gradient(to right, black 0%, black 33.33%, transparent 66.66%, transparent 100%);
                        -webkit-mask-size: 300% auto;
                        mask-size: 300% auto;
                        -webkit-mask-position: 100% center;
                        mask-position: 100% center;
                        
                        transition: transform 1.98s cubic-bezier(0.34,1.56,0.64,1) 1654ms;
                    }
                    .hero-reality-effect.active {
                        background-position: 0% center;
                        -webkit-mask-position: 0% center;
                        mask-position: 0% center;
                        transition: 
                            -webkit-mask-position 2.75s ease-in-out 1654ms,
                            mask-position 2.75s ease-in-out 1654ms,
                            background-position 2.75s ease-in-out 1764ms,
                            transform 1.98s cubic-bezier(0.34,1.56,0.64,1) 1654ms;
                    }
                </style>

                <!-- REALITY (thin border, yellow fill, no shadows) -->
                <h1 class="font-normal leading-none uppercase font-logo tracking-tight select-none text-center origin-center transform scale-90 translate-y-12 hero-reality-effect"
                    :class="sectionVisible ? 'active scale-100 translate-y-0' : ''"
                    style="font-size: clamp(75px, 22vw, 205.84px); margin-top: -0.24em; -webkit-text-stroke: 1px black;">
                    {{ $profile->hero_title ?? 'REALITY' }}
                </h1>

            </div>

            <!-- One Pixel At A Time -->
            <p class="text-sm sm:text-base tracking-[0.3em] sm:tracking-[0.4em] uppercase text-white/70 mb-10 font-sans text-center">
                @php $heroSubtitle = $profile->hero_subtitle ?? 'One Pixel At A Time'; @endphp
                @foreach(explode(' ', $heroSubtitle) as $index => $word)<span class="inline-block opacity-0 translate-y-6" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" style="transition: transform 1103ms ease-out {{ 2867 + ($index * 221) }}ms, opacity 1103ms ease-out {{ 2867 + ($index * 221) }}ms;">{{ $word }}</span> @endforeach
            </p>

            <!-- Get Started Button -->
            <a href="#projects"
               class="px-10 py-4 sm:px-8 sm:py-3 bg-transparent border border-white font-sans text-sm sm:text-xs font-bold uppercase tracking-wider rounded-none hover:bg-white hover:text-black relative z-10 opacity-0 translate-y-8"
               :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
               style="transition: transform 1654ms ease-out 4190ms, opacity 1654ms ease-out 4190ms, background-color 300ms, color 300ms;">
                Get Started
            </a>

        </div>

        </div>
    </section>

    <!-- SELF INTRO SECTION -->
    <section id="self-intro" class="text-white relative flex flex-col min-h-[90vh] -mt-[40px] md:-mt-[60px] pt-[40px] md:pt-[60px] z-20">

        <!-- Floating 2D Visualizers -->
        <style>
            /* Wave Mask for clipping shapes to the top wave */
            .wave-mask {
                -webkit-mask-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z' fill='black'/%3E%3C/svg%3E"), linear-gradient(black, black);
                -webkit-mask-size: 100% 40px, 100% calc(100% - 32px);
                -webkit-mask-position: top left, bottom left;
                -webkit-mask-repeat: no-repeat;
                mask-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z' fill='black'/%3E%3C/svg%3E"), linear-gradient(black, black);
                mask-size: 100% 40px, 100% calc(100% - 32px);
                mask-position: top left, bottom left;
                mask-repeat: no-repeat;
            }
            @media (min-width: 768px) {
                .wave-mask {
                    -webkit-mask-size: 100% 60px, 100% calc(100% - 40px);
                    mask-size: 100% 60px, 100% calc(100% - 40px);
                }
            }
            
            /* Wave Mask for clipping shapes to BOTH top and bottom waves */
            .wave-mask-both {
                -webkit-mask-image: 
                    url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z' fill='black'/%3E%3C/svg%3E"), 
                    url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L0,0Z' fill='black'/%3E%3C/svg%3E"), 
                    linear-gradient(black, black);
                -webkit-mask-size: 100% 40px, 100% 40px, 100% calc(100% - 64px);
                -webkit-mask-position: top left, bottom left, center left;
                -webkit-mask-repeat: no-repeat;
                mask-image: 
                    url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z' fill='black'/%3E%3C/svg%3E"), 
                    url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 120' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,0L0,0Z' fill='black'/%3E%3C/svg%3E"), 
                    linear-gradient(black, black);
                mask-size: 100% 40px, 100% 40px, 100% calc(100% - 64px);
                mask-position: top left, bottom left, center left;
                mask-repeat: no-repeat;
            }
            @media (min-width: 768px) {
                .wave-mask-both {
                    -webkit-mask-size: 100% 60px, 100% 60px, 100% calc(100% - 80px);
                    mask-size: 100% 60px, 100% 60px, 100% calc(100% - 80px);
                }
            }

            @keyframes travelLeft {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                50% { transform: translate(-25px, 0px) rotate(-15deg) scale(0.95); }
            }
            @keyframes travelRight {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                50% { transform: translate(25px, 0px) rotate(15deg) scale(0.95); }
            }
            @keyframes travelUp {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                50% { transform: translate(0px, -25px) rotate(-10deg) scale(0.95); }
            }
            @keyframes travelDown {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                50% { transform: translate(0px, 25px) rotate(10deg) scale(0.95); }
            }
            @keyframes morphShape {
                0%, 100% { border-radius: 0%; transform: scale(1) rotate(0deg); }
                50% { border-radius: 50%; transform: scale(0.85) rotate(45deg); }
            }
            @keyframes pulseDotOpacity {
                0% { opacity: 0.1; }
                100% { opacity: 1; }
            }
            /* Smooth sine easing for buttery subtle animations */
            .anim-travel-left { animation: travelLeft 16s cubic-bezier(0.45, 0, 0.55, 1) infinite; }
            .anim-travel-right { animation: travelRight 17s cubic-bezier(0.45, 0, 0.55, 1) infinite; }
            .anim-travel-up { animation: travelUp 18s cubic-bezier(0.45, 0, 0.55, 1) infinite; }
            .anim-travel-down { animation: travelDown 19s cubic-bezier(0.45, 0, 0.55, 1) infinite; }
            .anim-morph { animation: morphShape 15s cubic-bezier(0.45, 0, 0.55, 1) infinite; }
            .anim-dot-1 { animation: pulseDotOpacity 1.4s ease-in-out infinite alternate; }
            .anim-dot-2 { animation: pulseDotOpacity 2.1s ease-in-out infinite alternate 0.5s; }
            .anim-dot-3 { animation: pulseDotOpacity 1.8s ease-in-out infinite alternate 0.2s; }
            .anim-dot-4 { animation: pulseDotOpacity 2.5s ease-in-out infinite alternate 0.7s; }
            @keyframes drawLine {
                0% { stroke-dashoffset: 100; }
                50% { stroke-dashoffset: 0; }
                100% { stroke-dashoffset: -100; }
            }
            .anim-draw-line { stroke-dasharray: 100; animation: drawLine 6s ease-in-out infinite; }
        </style>

        <div class="absolute inset-0 pointer-events-none z-0 bg-[#512b81] overflow-hidden wave-mask" x-data="{
            shapes: [],
            shown: false,
            init() {
                // Pre-defined perfectly balanced aesthetic composition (no randomness to prevent clashing)
                // Creates a perfect 360-degree frame strictly along the outer edges (Top, Bottom, Left, Right)
                // Creates a perfect 360-degree frame strictly along the outer edges (Top, Bottom, Left, Right)
                this.shapes = [
                    // --- DOTTED GRIDS (Anchored to the absolute hard corners) ---
                    { type: 'dots', color: 'text-white/25', top: '-2%', left: '-2%', size: '180px', anim: 'anim-travel-right', delay: '-1s', rotation: '0deg' },
                    { type: 'dots', color: 'text-white/25', top: '-2%', left: '93%', size: '180px', anim: 'anim-travel-left', delay: '-5s', rotation: '0deg' },
                    { type: 'dots', color: 'text-white/25', top: '88%', left: '-2%', size: '180px', anim: 'anim-travel-up', delay: '-3s', rotation: '0deg' },
                    { type: 'dots', color: 'text-white/25', top: '88%', left: '93%', size: '180px', anim: 'anim-travel-down', delay: '-8s', rotation: '0deg' },

                    // --- LINE ANIMATIONS (Dynamic 'drawing' paths to add energetic motion) ---
                    { type: 'zigzag', color: 'text-[#4dd9f0]', top: '15%', left: '-2%', size: '100px', anim: 'anim-travel-up', delay: '-2s', rotation: '20deg' },
                    { type: 'wave', color: 'text-[#FF851B]', top: '12%', left: '95%', size: '120px', anim: 'anim-travel-down', delay: '-6s', rotation: '-15deg' },
                    { type: 'straight-line', color: 'text-[#d0f69a]', top: '65%', left: '-2%', size: '150px', anim: 'anim-travel-right', delay: '-4s', rotation: '45deg', lowOpMobile: true },
                    { type: 'cross', color: 'text-[#4dd9f0]', top: '60%', left: '95%', size: '80px', anim: 'anim-travel-left', delay: '-7s', rotation: '10deg', lowOpMobile: true },

                    // --- CORNERS (Always visible, responsive size) ---
                    { type: 'morph', color: 'text-[#4dd9f0]', top: '4%', left: '-2%', size: '170px', anim: 'anim-travel-up', delay: '0s', rotation: '15deg' }, // Top-Left
                    { type: 'half-circle', color: 'text-[#d0f69a]', top: '4%', left: '95%', size: '160px', anim: 'anim-travel-down', delay: '-4s', rotation: '80deg' }, // Top-Right
                    { type: 'right-triangle', color: 'text-[#d0f69a]', top: '73%', left: '-2%', size: '150px', anim: 'anim-travel-down', delay: '-8s', rotation: '-15deg', lowOpMobile: true }, // Bottom-Left
                    { type: 'quarter-circle', color: 'text-[#FF851B]', top: '73%', left: '95%', size: '150px', anim: 'anim-travel-left', delay: '-2s', rotation: '25deg', lowOpMobile: true }, // Bottom-Right

                    // --- TOP EDGE (Hidden on mobile to prevent title clash) ---
                    { type: 'stairs', desktopOnly: true, color: 'text-[#FF851B]', top: '-5%', left: '30%', size: '130px', anim: 'anim-travel-right', delay: '-3s', rotation: '45deg' },
                    { type: 'pill', desktopOnly: true, color: 'text-[#4dd9f0]', top: '-5%', left: '65%', size: '140px', anim: 'anim-travel-left', delay: '-5s', rotation: '-10deg' },

                    // --- BOTTOM EDGE (Fully visible pop shapes, positioned safely on the sides) ---
                    { type: 'circle', color: 'text-[#d0f69a]', top: '75%', left: '12%', size: '140px', anim: 'anim-travel-right', delay: '-7s', rotation: '30deg', lowOpMobile: true },
                    { type: 'diamond', color: 'text-[#4dd9f0]', top: '75%', left: '82%', size: '120px', anim: 'anim-travel-up', delay: '-1s', rotation: '60deg', lowOpMobile: true },

                    // --- LEFT EDGE (Always visible, responsive size) ---
                    { type: 'circle', color: 'text-[#4dd9f0]', top: '27%', left: '-2%', size: '140px', anim: 'anim-travel-left', delay: '-3s', rotation: '-20deg' },
                    { type: 'pill', color: 'text-[#FF851B]', top: '50%', left: '-5%', size: '160px', anim: 'anim-travel-right', delay: '-6s', rotation: '45deg', lowOpMobile: true },

                    // --- RIGHT EDGE (Always visible, responsive size) ---
                    { type: 'diamond', color: 'text-[#FF851B]', top: '27%', left: '95%', size: '140px', anim: 'anim-travel-right', delay: '-7s', rotation: '-30deg' },
                    { type: 'morph', color: 'text-[#4dd9f0]', top: '50%', left: '95%', size: '170px', anim: 'anim-travel-up', delay: '-1s', rotation: '60deg', lowOpMobile: true }
                ];

                // Scroll observer to trigger pop-in animation
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        this.shown = true;
                        observer.disconnect(); // Only animate once
                    }
                }, { threshold: 0.1 }); // Trigger when 10% of section is visible
                observer.observe(this.$el);
            }
        }">
            <template x-for="(shape, index) in shapes" :key="index">
                <!-- Outer wrapper: Handles absolute positioning and the pop-in scroll transition -->
                <div class="absolute items-center justify-center transition-all duration-1000 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-center"
                     :class="[
                        shown ? 'scale-100 opacity-100' : 'scale-0 opacity-0',
                        shape.desktopOnly ? 'hidden md:flex' : 'flex'
                     ]"
                     :style="`top: ${shape.top}; left: ${shape.left}; width: clamp(50px, 12vw, ${shape.size}); height: clamp(50px, 12vw, ${shape.size}); transition-delay: ${index * 80}ms;`">
                    
                    <!-- Inner wrapper: Handles the continuous travel animation -->
                    <div class="w-full h-full flex items-center justify-center transition-opacity duration-500"
                         :class="[shape.anim, shape.color, shape.lowOpMobile ? 'opacity-20 md:opacity-100' : '']"
                         :style="`animation-delay: ${shape.delay};`">
                         
                        <!-- Shape rotation container -->
                        <div class="w-full h-full flex items-center justify-center" :style="`transform: rotate(${shape.rotation});`">
                            
                            <!-- Bauhaus Half Circle -->
                            <template x-if="shape.type === 'half-circle'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M 0 50 A 50 50 0 0 1 100 50 Z" />
                            </svg>
                        </template>
                        
                        <!-- Bauhaus Right Triangle -->
                        <template x-if="shape.type === 'right-triangle'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <polygon points="0,0 100,100 0,100" />
                            </svg>
                        </template>

                        <!-- Morphing Square to Circle -->
                        <template x-if="shape.type === 'morph'">
                            <div class="w-full h-full bg-current anim-morph"></div>
                        </template>

                        <!-- Solid Circle -->
                        <template x-if="shape.type === 'circle'">
                            <div class="w-full h-full rounded-full bg-current"></div>
                        </template>

                        <!-- Quarter Circle -->
                        <template x-if="shape.type === 'quarter-circle'">
                            <div class="w-full h-full bg-current rounded-tl-full"></div>
                        </template>

                        <!-- Solid Pill -->
                        <template x-if="shape.type === 'pill'">
                            <div class="w-full h-1/2 rounded-full bg-current"></div>
                        </template>

                        <!-- Solid Diamond -->
                        <template x-if="shape.type === 'diamond'">
                            <div class="w-full h-full bg-current" style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"></div>
                        </template>

                        <!-- Stepped Stairs -->
                        <template x-if="shape.type === 'stairs'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <polygon points="0,100 0,66 33,66 33,33 66,33 66,0 100,0 100,100" />
                            </svg>
                        </template>

                        <!-- Dotted Grid Pattern -->
                        <template x-if="shape.type === 'dots'">
                            <svg class="w-full h-full" width="100%" height="100%">
                                <defs>
                                    <pattern :id="'dotPattern' + index" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                                        <circle cx="9" cy="9" r="1.5" fill="currentColor" class="anim-dot-1" />
                                        <circle cx="27" cy="9" r="1.5" fill="currentColor" class="anim-dot-2" />
                                        <circle cx="9" cy="27" r="1.5" fill="currentColor" class="anim-dot-3" />
                                        <circle cx="27" cy="27" r="1.5" fill="currentColor" class="anim-dot-4" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" :fill="'url(#' + 'dotPattern' + index + ')'" />
                            </svg>
                        </template>

                        <!-- Line Animations -->
                        <template x-if="shape.type === 'zigzag'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M 10,50 L 30,20 L 50,80 L 70,20 L 90,50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'wave'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M 10,50 Q 30,20 50,50 T 90,50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'straight-line'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <line x1="10" y1="50" x2="90" y2="50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'cross'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <line x1="20" y1="20" x2="80" y2="80" pathLength="100" class="anim-draw-line" />
                                <line x1="80" y1="20" x2="20" y2="80" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>

                    </div>
                </div>
            </template>
        </div>

        <!-- Slides Component -->
        <div class="relative z-10 flex flex-col flex-1 w-full transition-all duration-1000 ease-out opacity-0 translate-y-12" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" x-data="{
            sectionVisible: false,
            slide: 0,
            total: {{ $introSlides->count() }},
            showSwipeHint: true,
            touchStartX: 0,
            prev() { this.slide = (this.slide - 1 + this.total) % this.total; },
            next() { this.slide = (this.slide + 1) % this.total; },
            onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
            onTouchEnd(e) {
                this.showSwipeHint = false;
                const diff = this.touchStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? this.next() : this.prev();
                }
            }
        }">

            <!-- Section Header -->
            <div class="text-center pt-5 pb-4 px-6 relative z-10">
                <h2 class="text-3xl md:text-5xl font-display uppercase tracking-tighter leading-none text-white">Introduction</h2>
            </div>
        <hr class="border-white/25 mx-0 relative z-10">

        <!-- Slides Wrapper (Desktop only - fade transition) -->
        <div class="hidden md:flex flex-1 w-full max-w-5xl mx-auto px-6 md:px-10 py-6 md:py-0 flex-col justify-center relative z-10">

            <!-- Slides Container: Uses CSS Grid area trick to stack slides perfectly on top of each other on mobile. 
                 This prevents the container from doubling in height and stretching the layout when both slides are fading in/out simultaneously. -->
            <div class="relative w-full flex-1 intro-slides-container grid" style="grid-template-areas: 'slide';">

                @foreach($introSlides as $index => $slideItem)
                <!-- SLIDE {{ $index + 1 }} -->
                <div x-show="slide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-50"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-50"
                     class="flex flex-col md:grid md:gap-12 md:items-center gap-6 w-full"
                     style="grid-area: slide; grid-template-columns: 3fr 2fr; transform-origin: center center;">

                    <!-- Top on mobile: Photo -->
                    <div class="flex items-center justify-center md:order-last md:justify-end md:h-full">
                        <div class="overflow-hidden w-[65vw] max-w-[240px] md:w-full md:max-w-[340px] md:h-auto md:mr-2
                                    {{ $index === 0 ? 'shadow-[5.5px_5.5px_0px_0px_rgba(0,0,0,1)] outline outline-[1.5px] outline-offset-[-1.5px] outline-black' : 'rounded-2xl' }}"
                             style="{{ $index === 0 ? 'aspect-ratio: 3/4; border-radius: 24.3% 6.1% 24.3% 6.1% / 18.2% 4.6% 18.2% 4.6%;' : '' }}">
                            <img src="{{ $slideItem->image_path ? (Str::startsWith($slideItem->image_path, 'http') ? $slideItem->image_path : ((Str::startsWith($slideItem->image_path, 'images/') || Str::startsWith($slideItem->image_path, 'videos/')) ? asset($slideItem->image_path) : Storage::url($slideItem->image_path))) : ($index === 0 ? asset('images/intro/profile.png') : asset('images/intro/slide'.($index+1).'.jpg')) }}"
                                 alt="{{ $slideItem->title }}"
                                 class="w-full {{ $index === 0 ? 'h-full object-cover object-top' : 'h-auto object-contain' }}"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23222%22/%3E%3C/svg%3E';">
                        </div>
                    </div>

                    <!-- Bottom on mobile: Text -->
                    <div class="md:order-first">
                        <div class="flex items-center gap-3 mb-2 w-full overflow-hidden">
                            <span class="text-xs italic text-white/60 font-sans whitespace-nowrap shrink-0">{{ $slideItem->chapter_label }}</span>
                            <div class="flex-1 min-w-0 border-t border-dotted border-white/30 overflow-hidden"></div>
                        </div>
                        <h3 class="font-logo text-[clamp(2rem,10vw,2.5rem)] sm:text-5xl md:text-5xl lg:text-[3.5rem] xl:text-[4rem] text-[#4dd9f0] uppercase leading-none mb-2"
                            style="-webkit-text-stroke: 1px black;">
                            {{ $slideItem->title }}
                        </h3>
                        @if($slideItem->subtitle)
                        <p class="text-[11px] sm:text-xs font-sans text-white/50 tracking-[0.12em] uppercase mb-3 md:mb-8 leading-relaxed">
                            {{ $slideItem->subtitle }}
                        </p>
                        @endif
                        <div class="space-y-3 md:space-y-5 font-poppins text-[13px] sm:text-sm text-white/80 leading-[1.8] md:leading-loose">
                            {!! nl2br(e($slideItem->description)) !!}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        <!-- MOBILE: Touch-swipe controlled slides (same x-show transitions as desktop) -->
        <div class="md:hidden relative w-full px-6 py-6"
             @touchstart="onTouchStart($event)"
             @touchend="onTouchEnd($event)"
             style="touch-action: pan-y;">

            <!-- Stacked slides with scale+fade transitions -->
            <div class="relative w-full grid" style="grid-template-areas: 'slide';">
                @foreach($introSlides as $index => $slideItem)
                <div x-show="slide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-50"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-50"
                     class="flex flex-col gap-6 w-full"
                     style="grid-area: slide; transform-origin: center center;">

                    <!-- Photo -->
                    <div class="flex items-center justify-center">
                        <div class="overflow-hidden w-[65vw] max-w-[240px]
                                    {{ $index === 0 ? 'shadow-[5.5px_5.5px_0px_0px_rgba(0,0,0,1)] outline outline-[1.5px] outline-offset-[-1.5px] outline-black' : 'rounded-2xl' }}"
                             style="{{ $index === 0 ? 'aspect-ratio: 3/4; border-radius: 24.3% 6.1% 24.3% 6.1% / 18.2% 4.6% 18.2% 4.6%;' : '' }}">
                            <img src="{{ $slideItem->image_path ? (Str::startsWith($slideItem->image_path, 'http') ? $slideItem->image_path : ((Str::startsWith($slideItem->image_path, 'images/') || Str::startsWith($slideItem->image_path, 'videos/')) ? asset($slideItem->image_path) : Storage::url($slideItem->image_path))) : ($index === 0 ? asset('images/intro/profile.png') : asset('images/intro/slide'.($index+1).'.jpg')) }}"
                                 alt="{{ $slideItem->title }}"
                                 class="w-full {{ $index === 0 ? 'h-full object-cover object-top' : 'h-auto object-contain' }}"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23222%22/%3E%3C/svg%3E';">
                        </div>
                    </div>

                    <!-- Text -->
                    <div>
                        <div class="flex items-center gap-3 mb-2 w-full overflow-hidden">
                            <span class="text-xs italic text-white/60 font-sans whitespace-nowrap shrink-0">{{ $slideItem->chapter_label }}</span>
                            <div class="flex-1 min-w-0 border-t border-dotted border-white/30 overflow-hidden"></div>
                        </div>
                        <h3 class="font-logo text-[clamp(2rem,10vw,2.5rem)] text-[#4dd9f0] uppercase leading-none mb-2"
                            style="-webkit-text-stroke: 1px black;">
                            {{ $slideItem->title }}
                        </h3>
                        @if($slideItem->subtitle)
                        <p class="text-[11px] font-sans text-white/50 tracking-[0.12em] uppercase mb-3 leading-relaxed">
                            {{ $slideItem->subtitle }}
                        </p>
                        @endif
                        <div class="space-y-3 font-poppins text-[13px] text-white/80 leading-[1.8]">
                            {!! nl2br(e($slideItem->description)) !!}
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Swipe Hint with Horizontal Lines -->
            <div x-show="showSwipeHint"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="flex items-center gap-4 mt-8 pointer-events-none relative z-30">
                <div class="flex-1 h-px bg-white/20"></div>
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="font-mono text-[9px] uppercase tracking-widest text-white/50 whitespace-nowrap">Swipe to browse</span>
                    <svg class="w-3.5 h-3.5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <div class="flex-1 h-px bg-white/20"></div>
            </div>
        </div>

        <!-- Navigation: divider + dots + arrows (Desktop only) -->
        <hr class="hidden md:block border-white/25 mx-0 mt-2 md:mt-0 relative z-10">
        <div class="hidden md:flex py-4 md:py-5 items-center justify-center gap-6 relative z-10">

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
        <!-- Mobile-only: dots -->
        <div class="md:hidden py-4 flex items-center justify-center gap-2.5 relative z-10">
            <template x-for="i in total" :key="i">
                <button @click="slide = i - 1"
                        :class="slide === i - 1 ? 'bg-white scale-110' : 'bg-white/35 hover:bg-white/60'"
                        class="w-2.5 h-2.5 rounded-full transition-all duration-300">
                </button>
            </template>
        </div>
        </div>

    </section>

    <!-- SKILLS SECTION -->
    <section id="skills" x-data="{ skillModal: { show: false, name: '', category: '', desc: '', proficiency: 5, image: '' } }" class="w-full bg-[#512b81] text-black pt-4 md:pt-8 relative">
        
        <div x-data="{ sectionVisible: false }" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" class="transition-all duration-1000 ease-out w-full flex flex-col border-t border-black relative z-10 opacity-0 translate-y-12">
            
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
                                <div @click="skillModal = { show: true, name: '{{ addslashes($skill->name) }}', category: '{{ addslashes($skill->category) }} Skill', desc: '{{ addslashes($skill->tooltip_info ?? '') }}', proficiency: {{ $skill->proficiency ?? 5 }}, image: '{{ !empty($skill->image_path) ? (Str::startsWith($skill->image_path, 'http') ? $skill->image_path : ((Str::startsWith($skill->image_path, 'images/') || Str::startsWith($skill->image_path, 'videos/')) ? asset($skill->image_path) : Storage::url($skill->image_path))) : '' }}' }" 
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
                <template x-teleport="body">
                    <div x-show="tooltip.show" 
                         x-transition.opacity.duration.200ms
                         class="fixed z-[9999] pointer-events-none bg-[#512b81] text-white p-3 rounded-lg shadow-2xl max-w-xs border border-white/20"
                         :style="`left: ${tooltip.x + 15}px; top: ${tooltip.y + 15}px; transform: translate(0, 0);`"
                         style="display: none;">
                        <div class="font-display font-bold text-sm text-[#d0f69a] mb-1" x-text="tooltip.name"></div>
                        <div class="font-sans text-xs text-white/80 leading-snug" x-show="tooltip.desc" x-text="tooltip.desc"></div>
                    </div>
                </template>

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
                                          @click="toolModal = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', image: '{{ !empty($item['image_path']) ? (Str::startsWith($item['image_path'], 'http') ? $item['image_path'] : ((Str::startsWith($item['image_path'], 'images/') || Str::startsWith($item['image_path'], 'videos/')) ? asset($item['image_path']) : Storage::url($item['image_path']))) : '' }}', category: '{{ addslashes($rowLabel) }}', proficiency: {{ $item['proficiency'] ?? 5 }} }; tooltip.show = false;"
                                          @mouseenter="tooltip = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', x: $event.clientX, y: $event.clientY }"
                                          @mouseleave="tooltip.show = false"
                                          @mousemove="tooltip.x = $event.clientX; tooltip.y = $event.clientY">
                                        @if(!empty($item['image_path']))
                                            <div class="h-10 md:h-14 w-auto flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-110">
                                                <img src="{{ Str::startsWith($item['image_path'], 'http') ? $item['image_path'] : ((Str::startsWith($item['image_path'], 'images/') || Str::startsWith($item['image_path'], 'videos/')) ? asset($item['image_path']) : Storage::url($item['image_path'])) }}" alt="{{ $item['name'] }}" class="h-full w-auto object-contain drop-shadow-md">
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
                                          @click="toolModal = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', image: '{{ !empty($item['image_path']) ? (Str::startsWith($item['image_path'], 'http') ? $item['image_path'] : ((Str::startsWith($item['image_path'], 'images/') || Str::startsWith($item['image_path'], 'videos/')) ? asset($item['image_path']) : Storage::url($item['image_path']))) : '' }}', category: '{{ addslashes($rowLabel) }}', proficiency: {{ $item['proficiency'] ?? 5 }} }; tooltip.show = false;"
                                          @mouseenter="tooltip = { show: true, name: '{{ addslashes($item['name']) }}', desc: '{{ addslashes($item['tooltip_info'] ?? '') }}', x: $event.clientX, y: $event.clientY }"
                                          @mouseleave="tooltip.show = false"
                                          @mousemove="tooltip.x = $event.clientX; tooltip.y = $event.clientY">
                                        @if(!empty($item['image_path']))
                                            <div class="h-10 md:h-14 w-auto flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-110">
                                                <img src="{{ Str::startsWith($item['image_path'], 'http') ? $item['image_path'] : ((Str::startsWith($item['image_path'], 'images/') || Str::startsWith($item['image_path'], 'videos/')) ? asset($item['image_path']) : Storage::url($item['image_path'])) }}" alt="{{ $item['name'] }}" class="h-full w-auto object-contain drop-shadow-md">
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

                <!-- Extra padding for the next section's negative margin overlap -->
                <div class="w-full bg-[#FF851B] h-[100px] md:h-[120px]"></div>

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
    <!-- COMBINED WORKS AREA WRAPPER -->
    <div class="relative w-full bg-[#FAF7E6] grid-bg-section wave-mask -mt-[40px] md:-mt-[60px] pt-[40px] md:pt-[60px] z-20">

        <!-- Floating 2D Visualizers (Spanning both Best Works and Works) -->
        <div class="absolute inset-0 pointer-events-none z-0" x-data="{
            shapes: [],
            shown: false,
            init() {
                this.shapes = [
                    // --- MORPHING SOLID SHAPES ---
                    { type: 'morph', color: 'text-[#4dd9f0]/30', top: '-2%', left: '-2%', size: '170px', anim: 'anim-travel-up', delay: '0s', rotation: '15deg' },
                    { type: 'circle', color: 'text-[#FF851B]/20', top: '85%', left: '96%', size: '150px', anim: 'anim-travel-left', delay: '-2s', rotation: '25deg', lowOpMobile: true },
                    { type: 'pill', color: 'text-[#4dd9f0]/30', top: '50%', left: '95%', size: '140px', anim: 'anim-travel-left', delay: '-5s', rotation: '-10deg' },
                    
                    // --- SQUIGGLY PATH ANIMATIONS ---
                    { type: 'scribble-loop', color: 'text-[#FF851B]/40', top: '10%', left: '96%', size: '150px', anim: 'anim-travel-up', delay: '0s', rotation: '15deg' },
                    { type: 'scribble-bounce', color: 'text-[#4dd9f0]/40', top: '75%', left: '-5%', size: '130px', anim: 'anim-travel-down', delay: '-3s', rotation: '-10deg' },
                    { type: 'scribble-wave', color: 'text-[#512b81]/20', top: '40%', left: '-5%', size: '160px', anim: 'anim-travel-right', delay: '-5s', rotation: '45deg', lowOpMobile: true },
                    
                    // --- DOTS / GRIDS ---
                    { type: 'dots', color: 'text-[#512b81]/10', top: '-2%', left: '-5%', size: '180px', anim: 'anim-travel-right', delay: '-2s', rotation: '0deg' },
                    { type: 'dots', color: 'text-[#512b81]/10', top: '88%', left: '95%', size: '180px', anim: 'anim-travel-up', delay: '-6s', rotation: '0deg' },
                ];

                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        this.shown = true;
                        observer.disconnect();
                    }
                }, { threshold: 0.1 });
                observer.observe(this.$el);
            }
        }">
            <template x-for="(shape, index) in shapes" :key="index">
                <div class="absolute items-center justify-center transition-all duration-1000 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-center"
                     :class="[
                        shown ? 'scale-100 opacity-100' : 'scale-0 opacity-0',
                        shape.desktopOnly ? 'hidden md:flex' : 'flex'
                     ]"
                     :style="`top: ${shape.top}; left: ${shape.left}; width: clamp(50px, 12vw, ${shape.size}); height: clamp(50px, 12vw, ${shape.size}); transition-delay: ${index * 80}ms;`">
                    
                    <div class="w-full h-full flex items-center justify-center transition-opacity duration-500"
                         :class="[shape.anim, shape.color, shape.lowOpMobile ? 'opacity-20 md:opacity-100' : '']"
                         :style="`animation-delay: ${shape.delay};`">
                         
                        <div class="w-full h-full flex items-center justify-center" :style="`transform: rotate(${shape.rotation});`">
                            
                            <!-- Morphing Square to Circle -->
                            <template x-if="shape.type === 'morph'">
                                <div class="w-full h-full bg-current anim-morph"></div>
                            </template>

                            <!-- Solid Circle -->
                            <template x-if="shape.type === 'circle'">
                                <div class="w-full h-full rounded-full bg-current"></div>
                            </template>

                            <!-- Solid Pill -->
                            <template x-if="shape.type === 'pill'">
                                <div class="w-full h-1/2 rounded-full bg-current"></div>
                            </template>

                            <!-- Squiggles -->
                            <template x-if="shape.type === 'scribble-loop'">
                                <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M 20,80 C -10,30 60,-10 50,50 C 40,110 110,70 80,20" pathLength="100" class="anim-draw-line" />
                                </svg>
                            </template>

                            <template x-if="shape.type === 'scribble-bounce'">
                                <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M 10,60 Q 25,10 40,60 T 70,60 T 100,60" pathLength="100" class="anim-draw-line" />
                                </svg>
                            </template>

                            <template x-if="shape.type === 'scribble-wave'">
                                <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M 10,50 Q 25,20 40,50 T 70,50 T 100,50" pathLength="100" class="anim-draw-line" />
                                </svg>
                            </template>

                            <template x-if="shape.type === 'zigzag'">
                                <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M 10,50 L 30,20 L 50,80 L 70,20 L 90,50" pathLength="100" class="anim-draw-line" />
                                </svg>
                            </template>

                            <template x-if="shape.type === 'dots'">
                                <svg class="w-full h-full" width="100%" height="100%">
                                    <defs>
                                        <pattern :id="'dotPatternCombined' + index" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                                            <circle cx="9" cy="9" r="2" fill="currentColor" class="anim-dot-1" />
                                            <circle cx="27" cy="9" r="2" fill="currentColor" class="anim-dot-2" />
                                            <circle cx="9" cy="27" r="2" fill="currentColor" class="anim-dot-3" />
                                            <circle cx="27" cy="27" r="2" fill="currentColor" class="anim-dot-4" />
                                        </pattern>
                                    </defs>
                                    <rect width="100%" height="100%" :fill="'url(#' + 'dotPatternCombined' + index + ')'" />
                                </svg>
                            </template>

                        </div>
                    </div>
                </div>
            </template>
        </div>



    <!-- WORKS AND OUTPUTS SECTION -->
    <section id="works" class="w-full text-black pt-0 pb-0 relative">
        <div x-data="{ sectionVisible: false }" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" class="transition-all duration-1000 ease-out w-full relative z-10 pb-0 opacity-0 translate-y-12">

            {{-- ══════════════════════════════════════════════════
                 WORKS & OUTPUTS GENERAL INDICATOR
            ══════════════════════════════════════════════════ --}}
            <div class="text-center pt-0 md:pt-2 pb-12 md:pb-16 px-6 relative z-10">
                <h2 class="text-5xl md:text-7xl lg:text-[6rem] font-display uppercase tracking-tighter leading-none text-black">Works and outputs</h2>
            </div>

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

            <div class="w-full relative" x-data="{
                current: 0,
                allItems: {{ $carouselItems }},
                dimming: false,
                hoverTimer: null,
                mobileFocusId: null,
                showSwipeHint: true,
                isMobile: ('ontouchstart' in window || navigator.maxTouchPoints > 0),
                get total() { return this.allItems.length; },
                prev() {
                    const track = this.$refs.track;
                    const card = track.querySelector('a');
                    if (!card) return;
                    track.scrollBy({ left: -(card.offsetWidth), behavior: 'smooth' });
                },
                next() {
                    const track = this.$refs.track;
                    const card = track.querySelector('a');
                    if (!card) return;
                    track.scrollBy({ left: card.offsetWidth, behavior: 'smooth' });
                },
                onScroll() {
                    this.showSwipeHint = false;
                    const track = this.$refs.track;
                    const card = track.querySelector('a');
                    if (card) this.current = Math.round(track.scrollLeft / card.offsetWidth);
                }
            }"
               @click.outside="if(isMobile) { mobileFocusId = null; dimming = false; }"
               @mouseenter="if(!isMobile) { hoverTimer = setTimeout(() => { dimming = true; }, 5000); }"
               @mouseleave="if(!isMobile) { clearTimeout(hoverTimer); dimming = false; }"
               @mobile-focus-reset.window="if ($event.detail.id !== mobileFocusId) { mobileFocusId = null; dimming = false; }">

                <!-- Brutalist Edge-to-Edge Header & Filters for UI/UX -->
                @php
                    $uiMediums = $uiProjects->pluck('medium')->filter()->unique()->values();
                @endphp
                <div class="w-full bg-[#D4D4D4] border-t border-black flex flex-col border-b sticky top-[72px] z-40">
                    <!-- Large Header Row -->
                    <div class="relative w-full py-8 md:py-12 text-center flex items-center justify-center overflow-hidden">
                        <h3 class="font-display text-2xl md:text-4xl lg:text-5xl uppercase text-black tracking-tighter leading-none relative z-10 mt-2">UI/UX PRODUCTS</h3>
                    </div>

                    <!-- Tabs Row -->
                    <div class="flex w-full border-t border-black overflow-x-auto hide-scrollbar">
                        <button class="bg-white text-black min-w-[120px] flex-1 py-3 px-4 text-[10px] md:text-sm font-display font-bold uppercase tracking-wider border-r border-black shrink-0">
                            ALL
                        </button>
                        @foreach($uiMediums as $med)
                            <button class="text-black hover:bg-white/50 min-w-[120px] flex-1 py-3 px-4 text-[10px] md:text-sm font-display font-bold uppercase tracking-wider border-r border-black last:border-r-0 shrink-0 truncate transition-colors duration-200">
                                {{ $med }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Carousel Viewport -->
                <div class="relative w-full bg-black">

                    <!-- Desktop-only Left Arrow -->
                    <button @click="prev()"
                            class="hidden md:flex absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-black/15 items-center justify-center text-black hover:bg-black hover:text-white transition-all duration-200 focus:outline-none"
                            aria-label="Previous slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Desktop-only Right Arrow -->
                    <button @click="next()"
                            class="hidden md:flex absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-black/15 items-center justify-center text-black hover:bg-black hover:text-white transition-all duration-200 focus:outline-none"
                            aria-label="Next slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Mobile Swipe Hint -->
                    <div x-show="isMobile && showSwipeHint"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="md:hidden absolute inset-x-0 bottom-4 flex items-end justify-center z-30 pointer-events-none">
                        <div class="flex items-center gap-2 bg-black/40 backdrop-blur-sm rounded-full px-4 py-2 shadow-lg">
                            <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span class="font-mono text-[9px] uppercase tracking-widest text-white/80 whitespace-nowrap">Swipe to browse</span>
                            <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>

                    <!-- Native Scroll Track -->
                    <div x-ref="track"
                         @scroll="onScroll()"
                         class="flex gap-0 py-0 w-full"
                         style="overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none;"
                         @scroll.passive="true">
                        <style>.ui-track::-webkit-scrollbar { display: none; }</style>

                            {{-- Blade renders the real cards — each is a real <a> link --}}
                            @forelse($uiProjects as $index => $proj)
                                @php
                                    $hasBodyContentUi = $proj->hasBodyContent();
                                    $hasAdminLinkUi = !empty($proj->full_video_url) || !empty($proj->embed_url) || !empty($proj->video_url);
                                    $adminLinkUrlUi = $proj->full_video_url ?: $proj->embed_url ?: $proj->video_url;
                                    $isVideoProjectUi = $proj->main_media_type === 'video' || !empty($proj->main_video_path) || $hasAdminLinkUi;
                                    
                                    $localVideoUi = $proj->main_video_path ? (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path))) : ($proj->thumbnail_video_path ? (Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : ((Str::startsWith($proj->thumbnail_video_path, 'images/') || Str::startsWith($proj->thumbnail_video_path, 'videos/')) ? asset($proj->thumbnail_video_path) : Storage::url($proj->thumbnail_video_path))) : '');
                                    
                                    $localImageUi = '';
                                    if ($proj->main_image_path) {
                                        $localImageUi = (Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path)));
                                    } elseif ($proj->thumbnail_path) {
                                        $localImageUi = (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path)));
                                    } elseif (!empty($proj->thumbnail_images)) {
                                        $localImageUi = Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0]));
                                    } elseif (!empty($proj->main_images)) {
                                        $localImageUi = Str::startsWith($proj->main_images[0], 'http') ? $proj->main_images[0] : ((Str::startsWith($proj->main_images[0], 'images/') || Str::startsWith($proj->main_images[0], 'videos/')) ? asset($proj->main_images[0]) : Storage::url($proj->main_images[0]));
                                    }
                                    
                                    $isFallbackUi = !$hasBodyContentUi;
                                    
                                    if ($isFallbackUi) {
                                        if ($hasAdminLinkUi) {
                                            $cardHrefUi = $adminLinkUrlUi;
                                            $cardTargetUi = '_blank';
                                            $onClickUi = '';
                                        } else {
                                            $cardHrefUi = '#';
                                            $cardTargetUi = '_self';
                                            $onClickUi = "\$event.preventDefault(); window.dispatchEvent(new CustomEvent('open-global-preview', { detail: { v: '".addslashes($localVideoUi)."', i: '".addslashes($localImageUi)."', t: '".addslashes($proj->title)."', m: '".addslashes($proj->medium)."', y: '".addslashes($proj->year)."' } }));";
                                        }
                                    } else {
                                        $cardHrefUi = route('portfolio.project.show', $proj->slug);
                                        $cardTargetUi = '_self';
                                        $onClickUi = '';
                                    }
                                @endphp
                                <a href="{{ $cardHrefUi }}"
                                   target="{{ $cardTargetUi }}"
                                   @if($isFallbackUi && $hasAdminLinkUi) rel="noopener noreferrer" @endif
                                   x-data="{ isDimmed: false, vidLoaded: false, isHovered: false, itemTimer: null, itemId: 'best-{{$index}}' }"
                                   @mouseenter="if(!isMobile) { itemTimer = setTimeout(() => { isHovered = true; dimming = true; }, 1500); }"
                                   @mouseleave="if(!isMobile) { clearTimeout(itemTimer); isHovered = false; dimming = false; }"
                                   @click="if(isMobile && mobileFocusId !== itemId) { $event.preventDefault(); mobileFocusId = itemId; dimming = true; window.dispatchEvent(new CustomEvent('mobile-focus-reset', { detail: { id: itemId } })); } @if($onClickUi) else { {!! $onClickUi !!} } @endif"
                                   class="shrink-0 w-[82vw] md:w-[560px] rounded-none relative group bg-black transition-opacity duration-500 hover:!opacity-100 z-10"
                                   style="scroll-snap-align: start;"
                                   :class="(dimming && (!isMobile ? !isHovered : mobileFocusId !== itemId) ? 'opacity-25 ' : 'opacity-100 ') + ({{ $index }} === current ? '' : '')"
                                   :style="`scroll-snap-align: start; ${((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) ? 'z-index: 20;' : ''}`">

                                    <!-- Image / placeholder -->
                                    <div class="relative w-full rounded-none overflow-hidden
                                                @if(($proj->use_custom_thumbnail && $proj->thumbnail_path) || $proj->main_video_path) bg-black @else bg-black @endif
                                                flex items-center justify-center">
                                        @if($proj->use_custom_thumbnail && $proj->thumbnail_path)
                                            <img src="{{ Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path))) }}"
                                                 alt="{{ $proj->title }}"
                                                 class="w-full h-auto object-cover">
                                        @elseif($proj->main_media_type === 'video' && $proj->main_video_path)
                                            @php
                                                $localImage = '';
                                                if ($proj->thumbnail_path) {
                                                    $localImage = Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path)));
                                                } elseif ($proj->main_image_path) {
                                                    $localImage = (Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path)));
                                                } elseif (!empty($proj->thumbnail_images)) {
                                                    $localImage = (Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0])));
                                                }
                                                
                                                // Auto-generate Cloudinary poster from video URL
                                                $vidUrl = $proj->main_video_path;
                                                if (empty($localImage) && $vidUrl && Str::contains($vidUrl, 'res.cloudinary.com')) {
                                                    $autoJpg = preg_replace('/\.[a-zA-Z0-9]+$/i', '.jpg', $vidUrl);
                                                    $localImage = str_replace('/upload/', '/upload/so_2/', $autoJpg);
                                                }
                                            @endphp
                                            
                                            {{-- Video: preload=none prevents fetching until play() is called --}}
                                            <video src="{{ Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path))) }}"
                                                   @if($localImage) poster="{{ $localImage }}" @endif
                                                   @loadeddata="vidLoaded = true"
                                                   @canplay="vidLoaded = true"
                                                   muted playsinline loop preload="none"
                                                   x-effect="if ((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) { $el.play().catch(()=>{}) } else { $el.pause(); }"
                                                   class="w-full h-auto block pointer-events-none transition-all duration-700"
                                                   :class="!vidLoaded ? 'animate-pulse grayscale opacity-60' : 'opacity-100'"
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
                                                       vid.addEventListener('loadedmetadata', initLoop);
                                                       vid.addEventListener('timeupdate', () => {
                                                           if (loopEnd > 0 && vid.currentTime >= loopEnd) {
                                                               vid.currentTime = loopStart;
                                                           }
                                                       });
                                                   "></video>

                                            @if($localImage)
                                            <img src="{{ $localImage }}"
                                                 class="absolute inset-0 w-full h-full object-cover pointer-events-none transition-opacity duration-700 z-10"
                                                 :class="((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) ? 'opacity-0' : 'opacity-100'">
                                            @endif
                                        @elseif($proj->main_media_type === 'image' && !empty($proj->main_images))
                                            <div x-data="{ currentSlide: 0, total: {{ count($proj->main_images) }} }"
                                                 class="relative w-full overflow-hidden" style="padding-top: 56.25%;">
                                                @foreach($proj->main_images as $index => $img)
                                                    <img src="{{ Str::startsWith($img, 'http') ? $img : (Str::startsWith($img, 'http') ? $img : ((Str::startsWith($img, 'images/') || Str::startsWith($img, 'videos/')) ? asset($img) : Storage::url($img))) }}"
                                                         x-show="currentSlide === {{ $index }}"
                                                         x-transition.opacity.duration.700ms
                                                         loading="lazy"
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
                                            <img src="{{ Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : (Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path))) }}"
                                                 alt="{{ $proj->title }}"
                                                 loading="lazy"
                                                 class="w-full h-auto object-cover">
                                        @else
                                            <div class="w-full" style="padding-top: 56.25%;"></div>
                                            <svg class="w-12 h-12 text-black/15 absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Mobile Focus Indicator -->
                                    <div x-show="isMobile && mobileFocusId === itemId" 
                                         x-transition.opacity.duration.300ms
                                         class="absolute bottom-4 left-4 right-4 z-30 px-4 py-2.5 bg-black/80 backdrop-blur-md border border-white/20 rounded-xl flex items-center justify-between text-white pointer-events-none shadow-lg">
                                        <span class="font-mono text-[10px] uppercase tracking-widest text-white/90">click to preview project</span>
                                        <svg class="w-3.5 h-3.5 text-white/80 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>

                                    <!-- Hover overlay -->
                                    <div class="absolute inset-0 rounded-none bg-black/0 group-hover:bg-black/60 transition-all duration-300 flex flex-col justify-end p-5 pointer-events-none z-10">
                                        <div class="translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-white font-mono text-[9px] uppercase tracking-widest mb-2">
                                                {{ $proj->medium ?? 'Project' }}
                                            </span>
                                            <p class="text-white font-display text-lg uppercase leading-tight">{{ $proj->title }}</p>
                                            @if(!$isFallbackUi)
                                                <span class="mt-3 inline-flex items-center gap-1.5 text-white font-mono text-[10px] uppercase tracking-widest">
                                                    View Case Study
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isFallbackUi)
                                        <div class="absolute bottom-5 right-5 z-30 flex flex-col items-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                            <span class="font-logo text-[12px] md:text-sm text-white/80 uppercase tracking-widest">Case study coming soon...</span>
                                            <div class="inline-flex items-center gap-2 px-4 py-2 border border-white bg-[#6829AA] text-white font-logo text-[11px] md:text-xs uppercase tracking-widest transition-transform hover:scale-105 shadow-lg">
                                                {{ $isVideoProjectUi ? 'See full video' : 'See full image' }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Medium badge -->
                                    <div class="absolute top-3.5 left-3.5 z-20 px-2.5 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[9px] uppercase tracking-widest text-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        {{ $proj->medium ?? 'Project' }}
                                    </div>

                                    @if($proj->year)
                                        <div class="absolute top-3.5 right-3.5 z-20 px-2.5 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[9px] text-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
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
                            <div class="shrink-0 w-[82vw] md:w-[560px] aspect-video rounded-none relative group bg-[#1A1A1A] border-r border-black flex flex-col items-center justify-center transition-opacity duration-500 hover:!opacity-100"
                                 :class="(dimming ? 'opacity-25 ' : 'opacity-100 ') + ({{ $uiProjects->count() }} === current ? '' : '')">
                                <div class="text-center opacity-50 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 rounded-full bg-black border border-white/20 flex items-center justify-center mx-auto mb-4 transition-colors group-hover:border-white/50">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                    <h4 class="font-display text-lg uppercase text-white tracking-wider">More in the works</h4>
                                    <p class="font-mono text-[10px] text-white/60 uppercase tracking-widest mt-1">Coming Soon</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Dot indicators + progress bar -->
                <div class="py-6 flex items-center justify-center gap-5 bg-[#D4D4D4] border-y border-black px-6 w-full">
                    <div class="flex items-center gap-2">
                        @foreach($uiProjects as $index => $proj)
                            <button @click="$refs.track.scrollTo({ left: {{ $index }} * ($refs.track.querySelector('a')?.offsetWidth ?? 0), behavior: 'smooth' });"
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
            $bestWorks = $projects->where('category', 'visual')->where('is_best_work', true)->shuffle();
            $otherWorks = $projects->where('category', 'visual')->where('is_best_work', false)->shuffle();
            $visualProjects = $bestWorks->concat($otherWorks)->values();

            // Collect distinct mediums for filter pills
            $mediums = $visualProjects->pluck('medium')->filter()->unique()->values();

            // Map medium → play icon visibility (anything motion/video shows play)
            $playTypes = ['Motion', 'Video', 'Video Edit', 'Animation', 'Motion Design'];
        @endphp

        <div class="w-full pt-0 pb-20"
             x-data="{ activeFilter: 'all', comingSoonModal: false, modalVideoSrc: '', modalImageSrc: '', modalTitle: '' }">

            <!-- Brutalist Edge-to-Edge Header & Filters -->
            <div class="w-full bg-[#D4D4D4] border-t border-black flex flex-col border-b sticky top-[72px] z-40">
                <!-- Large Header Row -->
                <div class="relative w-full py-8 md:py-12 text-center flex items-center justify-center overflow-hidden">
                    <h3 class="font-display text-2xl md:text-4xl lg:text-5xl uppercase text-black tracking-tighter leading-none relative z-10 mt-2">Visual &amp; Motion Design</h3>
                </div>

                <!-- Tabs Row -->
                <div class="flex w-full border-t border-black overflow-x-auto hide-scrollbar">
                    <button @click="activeFilter = 'all'"
                            :class="activeFilter === 'all' ? 'bg-white text-black' : 'text-black hover:bg-white/50'"
                            class="min-w-[120px] flex-1 py-3 px-4 text-[10px] md:text-sm font-display font-bold uppercase tracking-wider border-r border-black transition-colors duration-200 shrink-0">
                        All
                    </button>
                    @foreach($mediums as $med)
                        <button @click="activeFilter = '{{ $med }}'"
                                :class="activeFilter === '{{ $med }}' ? 'bg-white text-black' : 'text-black hover:bg-white/50'"
                                class="min-w-[120px] flex-1 py-3 px-4 text-[10px] md:text-sm font-display font-bold uppercase tracking-wider border-r border-black last:border-r-0 transition-colors duration-200 shrink-0 truncate">
                            {{ $med }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Pinterest masonry — seamless edge-to-edge layout --}}
            <div class="w-full relative" style="background-color: #020617;">
                
                {{-- Cropped Height Wrapper (150vh allowance) --}}
                <div class="relative overflow-hidden" style="max-height: 150vh;">
                    
                    <div class="columns-2 md:columns-3 lg:columns-4 gap-0"
                         x-data="{ dimming: false, hoverTimer: null, mobileFocusId: null, isMobile: ('ontouchstart' in window || navigator.maxTouchPoints > 0) }"
                         @click.outside="if(isMobile) { mobileFocusId = null; dimming = false; }"
                         @mouseenter="if(!isMobile) { hoverTimer = setTimeout(() => { dimming = true; }, 5000); }"
                         @mouseleave="if(!isMobile) { clearTimeout(hoverTimer); dimming = false; }"
                         @mobile-focus-reset.window="if ($event.detail.id !== mobileFocusId) { mobileFocusId = null; dimming = false; }">

                        @forelse($visualProjects as $proj)
                            @php
                                $hasBodyContentVis = $proj->hasBodyContent();
                                $hasAdminLinkVis = !empty($proj->full_video_url) || !empty($proj->embed_url) || !empty($proj->video_url);
                                $adminLinkUrlVis = $proj->full_video_url ?: $proj->embed_url ?: $proj->video_url;
                                $isVideoProjectVis = $proj->main_media_type === 'video' || !empty($proj->main_video_path) || $hasAdminLinkVis;
                                
                                $localVideoVis = $proj->main_video_path ? (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path))) : ($proj->thumbnail_video_path ? (Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : ((Str::startsWith($proj->thumbnail_video_path, 'images/') || Str::startsWith($proj->thumbnail_video_path, 'videos/')) ? asset($proj->thumbnail_video_path) : Storage::url($proj->thumbnail_video_path))) : '');
                                
                                $localImageVis = '';
                                if ($proj->main_image_path) {
                                    $localImageVis = (Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path)));
                                } elseif ($proj->thumbnail_path) {
                                    $localImageVis = Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path)));
                                } elseif (!empty($proj->thumbnail_images)) {
                                    $localImageVis = (Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0])));
                                }
                                
                                $isFallbackVis = !$hasBodyContentVis;

                                if ($isFallbackVis) {
                                    if ($hasAdminLinkVis) {
                                        $cardHrefVis = $adminLinkUrlVis;
                                        $cardTargetVis = '_blank';
                                        $onClickVis = '';
                                    } else {
                                        $cardHrefVis = '#';
                                        $cardTargetVis = '_self';
                                        $onClickVis = "\$event.preventDefault(); comingSoonModal = true; modalVideoSrc = '{$localVideoVis}'; modalImageSrc = '{$localImageVis}'; modalTitle = '".addslashes($proj->title)."'; modalMedium = '".addslashes($proj->medium)."'; modalYear = '".addslashes($proj->year)."';";
                                    }
                                } else {
                                    $cardHrefVis = route('portfolio.project.show', $proj->slug);
                                    $cardTargetVis = '_self';
                                    $onClickVis = '';
                                }
                            @endphp
                            {{-- By removing padding-top and absolute positioning, the img tag naturally defines the box height, perfect for masonry! --}}
                               <a href="{{ $cardHrefVis }}"
                               target="{{ $cardTargetVis }}"
                               {!! $onClickVis ? 'x-on:click="'.$onClickVis.'"' : '' !!}
                               @if($isFallbackVis && $hasAdminLinkVis) rel="noopener noreferrer" @endif
                               x-data="{ isDimmed: false, vidLoaded: false, isHovered: false, itemTimer: null, itemId: 'proj-{{$proj->id}}' }"
                               x-show="activeFilter === 'all' || activeFilter === '{{ $proj->medium }}'"
                               @mouseenter="if(!isMobile) { itemTimer = setTimeout(() => { isHovered = true; dimming = true; }, 1500); }"
                               @mouseleave="if(!isMobile) { clearTimeout(itemTimer); isHovered = false; dimming = false; }"
                               @click="if(isMobile && mobileFocusId !== itemId) { $event.preventDefault(); mobileFocusId = itemId; dimming = true; window.dispatchEvent(new CustomEvent('mobile-focus-reset', { detail: { id: itemId } })); }"
                               x-transition:enter="transition-opacity duration-300"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               x-transition:leave="transition-opacity duration-200"
                               x-transition:leave-start="opacity-100"
                               x-transition:leave-end="opacity-0"
                               :class="dimming && (!isMobile ? !isHovered : mobileFocusId !== itemId) ? 'opacity-25' : 'opacity-100'"
                               class="block w-full break-inside-avoid mb-0 rounded-none relative group cursor-pointer transition-opacity duration-500 hover:!opacity-100 z-10"
                               :style="((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) ? 'z-index: 20;' : ''">

                                <div class="relative w-full overflow-hidden flex items-center justify-center bg-black">
                                    @if(($proj->thumbnail_type === 'video' && $proj->thumbnail_video_path) || ($proj->main_media_type === 'video' && ($proj->main_video_path || $proj->video_url)))
                                        @php
                                            $vidSrc = '';
                                            if ($proj->thumbnail_type === 'video' && $proj->thumbnail_video_path) {
                                                $vidSrc = Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : (Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : ((Str::startsWith($proj->thumbnail_video_path, 'images/') || Str::startsWith($proj->thumbnail_video_path, 'videos/')) ? asset($proj->thumbnail_video_path) : Storage::url($proj->thumbnail_video_path)));
                                            } elseif ($proj->main_media_type === 'video') {
                                                $vidSrc = $proj->main_video_path ? (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path)))) : $proj->video_url;
                                            }
                                            
                                            $localImage = '';
                                            if ($proj->thumbnail_path) {
                                                $localImage = Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path)));
                                            } elseif ($proj->main_image_path) {
                                                $localImage = Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : (Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path)));
                                            } elseif (!empty($proj->thumbnail_images)) {
                                                $localImage = Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : (Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0])));
                                            }
                                            
                                            // Auto-generate Cloudinary poster from video URL, grabbing the frame at 2 seconds to avoid black fade-ins
                                            if (empty($localImage) && $vidSrc && Str::contains($vidSrc, 'res.cloudinary.com')) {
                                                $autoJpg = preg_replace('/\.[a-zA-Z0-9]+$/i', '.jpg', $vidSrc);
                                                $localImage = str_replace('/upload/', '/upload/so_2/', $autoJpg);
                                            }
                                        @endphp
                                        
                                        {{-- Video: preload=none prevents fetching until play() is called --}}
                                        <video src="{{ $vidSrc }}"
                                               @if($localImage) poster="{{ $localImage }}" @endif
                                               @loadeddata="vidLoaded = true"
                                               @canplay="vidLoaded = true"
                                               muted playsinline loop preload="none"
                                               x-effect="if ((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) { $el.play().catch(()=>{}) } else { $el.pause(); }"
                                               class="w-full h-auto block pointer-events-none transition-all duration-700"
                                               :class="!vidLoaded ? 'animate-pulse grayscale opacity-60' : 'opacity-100'"
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
                                                   vid.addEventListener('loadedmetadata', initLoop);
                                                   vid.addEventListener('timeupdate', () => {
                                                       if (loopEnd > 0 && vid.currentTime >= loopEnd) vid.currentTime = loopStart;
                                                   });
                                               "></video>
                                        
                                        @if($localImage)
                                        <img src="{{ $localImage }}"
                                             class="absolute inset-0 w-full h-full object-cover pointer-events-none transition-opacity duration-700 z-10"
                                             :class="((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) ? 'opacity-0' : 'opacity-100'">
                                        @endif
                                    @elseif(!empty($proj->thumbnail_images))
                                        <div x-data="{ currentSlide: 0, total: {{ count($proj->thumbnail_images) }} }"
                                             class="relative w-full overflow-hidden">
                                            <!-- To maintain natural aspect ratio for masonry, use the first image for height, rest absolute -->
                                            <img src="{{ Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : (Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0]))) }}"
                                                 class="w-full h-auto object-cover invisible" loading="lazy">
                                            @foreach($proj->thumbnail_images as $index => $img)
                                                <img src="{{ Str::startsWith($img, 'http') ? $img : (Str::startsWith($img, 'http') ? $img : ((Str::startsWith($img, 'images/') || Str::startsWith($img, 'videos/')) ? asset($img) : Storage::url($img))) }}"
                                                     x-show="currentSlide === {{ $index }}"
                                                     x-transition.opacity.duration.700ms
                                                     loading="lazy"
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
                                        <img src="{{ Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : (Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path))) }}"
                                             alt="{{ $proj->title }}"
                                             class="w-full h-auto object-cover" loading="lazy">
                                    @else
                                        {{-- Fallback placeholder if missing --}}
                                        <div class="w-full" style="padding-top: 100%;">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                                                <svg class="w-8 h-8 text-black/12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            <div class="absolute inset-0 bg-neutral-900 flex items-center justify-center">
                                                <span class="text-neutral-700 font-mono text-xs uppercase tracking-widest">No Media</span>
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
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent transition-opacity duration-300 flex flex-col justify-end p-4 pointer-events-none z-10"
                                         :class="(isMobile && mobileFocusId === itemId) ? 'opacity-100' : 'opacity-0 xl:group-hover:opacity-100'">
                                        <div class="transition-all duration-250"
                                             :class="(isMobile && mobileFocusId === itemId) ? 'translate-y-0 opacity-100' : 'translate-y-2 opacity-0 xl:group-hover:translate-y-0 xl:group-hover:opacity-100'">
                                            
                                            @if($proj->medium)
                                                <span class="font-mono text-[9px] text-white/70 uppercase tracking-widest">{{ $proj->medium }}</span>
                                            @endif
                                            <p class="text-white font-sans text-sm font-semibold mt-1 leading-snug">{{ $proj->title }}</p>

                                        </div>
                                    </div>

                                    {{-- Type pill —always visible top-right --}}
                                    @if($proj->medium)
                                        <div class="absolute top-3 right-3 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span class="px-2 py-0.5 rounded-full bg-white/80 backdrop-blur-sm border border-black/10 font-mono text-[8px] uppercase tracking-wider text-black/55 shadow-sm">
                                                {{ $proj->medium }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Year & Best Work badge top-left --}}
                                    <div class="absolute top-3 left-3 z-20 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        @if($proj->is_best_work)
                                            <span class="px-2 py-0.5 rounded-full bg-black text-white font-mono text-[8px] uppercase tracking-wider shadow-sm">
                                                Best Work
                                            </span>
                                        @endif
                                        @if($proj->year)
                                            <span class="px-2 py-0.5 rounded-full bg-black/40 backdrop-blur-sm font-mono text-[8px] text-white/80">
                                                {{ $proj->year }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($isFallbackVis)
                                        <div class="absolute bottom-4 right-4 z-30 flex flex-col items-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                            <span class="font-logo text-[12px] md:text-sm text-white/80 uppercase tracking-widest">Story Coming soon...</span>
                                            <div class="inline-flex items-center gap-2 px-4 py-2 border border-white bg-[#6829AA] text-white font-logo text-[11px] md:text-xs uppercase tracking-widest transition-transform hover:scale-105 shadow-lg">
                                                {{ $isVideoProjectVis ? 'See full video' : 'See full image' }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Floating Mobile Focus Indicator outside the bottom of the item -->
                                <div x-show="isMobile && mobileFocusId === itemId" 
                                     x-transition.opacity.duration.300ms
                                     class="absolute left-0 right-0 -bottom-8 flex justify-center items-center pointer-events-none z-50">
                                    <span class="font-mono text-[9px] uppercase tracking-widest text-white flex items-center gap-1.5 drop-shadow-md bg-black/80 px-3 py-1.5 rounded-full border border-white/20">
                                        click to preview project
                                        <svg class="w-3 h-3 text-white/80 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-4 py-20 flex items-center justify-center text-black/30 font-mono text-xs uppercase tracking-widest">
                                No creative outputs yet.
                            </div>
                        @endforelse

                    </div>

                    {{-- Gradient Overlay to blend into the black section so the white wave mask reveals the black background --}}
                    <div class="absolute inset-x-0 bottom-0 h-[400px] pointer-events-none z-10"
                         style="background: linear-gradient(to top, #020617 0%, rgba(2,6,23,0.9) 30%, transparent 100%);"></div>
                </div>

                {{-- See More Button --}}
                <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-20">
                    <a href="{{ route('portfolio.outputs') }}" 
                       class="inline-block px-10 py-3 border-[1.5px] border-white font-sans font-bold text-sm tracking-wider uppercase transition-colors duration-300 shadow-lg backdrop-blur-sm rounded-full"
                       style="font-family: 'Poppins', sans-serif; background-color: white; color: #020617;"
                       onmouseover="this.style.backgroundColor='transparent'; this.style.color='white';"
                       onmouseout="this.style.backgroundColor='white'; this.style.color='#020617';">
                        See More
                    </a>
                </div>

            </div>

            <!-- Coming Soon / Video Modal -->
            <div x-show="comingSoonModal" 
                 style="display: none;"
                 class="fixed inset-0 z-[200] flex items-center justify-center p-4 md:p-6 bg-black/80 backdrop-blur-md"
                 x-transition.opacity>
                
                <!-- Adapt width to content -->
                <div @click.away="comingSoonModal = false; if($refs.modalVid) $refs.modalVid.pause();"
                     class="bg-[#0A0A0A] w-auto min-w-[300px] max-w-[95vw] md:max-w-5xl rounded-3xl border border-white/10 shadow-2xl flex flex-col overflow-hidden relative"
                     x-transition.scale.95>
                    
                    <!-- Top Center: Story coming soon indicator -->
                    <div class="absolute top-4 left-1/2 -translate-x-1/2 md:top-6 z-50">
                        <div class="px-3 py-1.5 bg-black/50 backdrop-blur-sm border border-white/10 rounded-full flex items-center gap-2 shadow-sm">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#6829AA] animate-pulse"></div>
                            <span class="font-mono text-[10px] text-white/80 uppercase tracking-widest whitespace-nowrap">Story coming soon</span>
                        </div>
                    </div>

                    <!-- Close button on top right -->
                    <button @click="comingSoonModal = false; if($refs.modalVid) $refs.modalVid.pause();" class="absolute top-4 right-4 md:top-6 md:right-6 z-50 shrink-0 w-10 h-10 bg-black/50 hover:bg-white/10 border border-white/10 rounded-full flex items-center justify-center text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <!-- Content Area (Center Row) -->
                    <div class="relative bg-black flex items-center justify-center p-4 md:p-8 pt-20 md:pt-20">
                        <!-- We re-bind src on modal open via Alpine so it only loads/plays when opened -->
                        <video x-show="modalVideoSrc" x-ref="modalVid" :src="comingSoonModal ? modalVideoSrc : ''" class="rounded-xl shadow-2xl object-contain" style="max-width: 100%; max-height: 65vh;" controls autoplay playsinline></video>
                        
                        <img x-show="!modalVideoSrc && modalImageSrc" :src="comingSoonModal ? modalImageSrc : ''" class="rounded-xl shadow-2xl object-contain" style="max-width: 100%; max-height: 65vh;">
                        
                        <div x-show="!modalVideoSrc && !modalImageSrc" class="py-24 px-6 flex flex-col items-center text-center">
                            <svg class="w-16 h-16 text-white/20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <h3 class="font-logo text-3xl md:text-4xl text-white tracking-widest uppercase mb-2" x-text="modalTitle"></h3>
                            <p class="font-mono text-sm text-white/50 uppercase tracking-widest">Story coming soon</p>
                        </div>
                    </div>

                    <!-- Footer: Title and Badges (Bottom Row) -->
                    <div class="p-6 md:p-8 flex flex-col gap-2 bg-[#1A1A1A] border-t border-white/5">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="font-logo text-2xl md:text-3xl text-white tracking-widest uppercase drop-shadow-xl" x-text="modalTitle"></h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span x-show="modalYear" class="px-2 py-0.5 rounded bg-black/40 backdrop-blur-md border border-white/10 font-mono text-[10px] text-white/90 uppercase shadow-sm" x-text="modalYear"></span>
                            <span x-show="modalMedium" class="px-2 py-0.5 rounded bg-black/40 backdrop-blur-md border border-white/10 font-mono text-[10px] text-white/90 uppercase shadow-sm" x-text="modalMedium"></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </section>
    <!-- END COMBINED WORKS AREA WRAPPER -->
    </div>

    <!-- ACHIEVEMENTS SECTION (Modern Two-Column Layout) -->
    <section id="achievements" class="pb-[88px] lg:pb-[156px] bg-white text-black font-sans border-b border-gray-100 relative -mt-[40px] md:-mt-[60px] pt-[96px] md:pt-[156px] -mb-[40px] md:-mb-[60px] z-20 wave-mask-both" 
             x-data="{ activeTab: 'all', selectedItem: null }">

        <!-- Floating 2D Visualizers for Achievements -->
        <div class="absolute inset-0 pointer-events-none z-0" x-data="{
            shapes: [],
            shown: false,
            init() {
                // Pre-defined perfectly balanced aesthetic composition (no randomness to prevent clashing)
                this.shapes = [
                    // --- DOTTED GRIDS (Anchored to the absolute hard corners) ---
                    { type: 'dots', color: 'text-blue-500/20', top: '-2%', left: '-5%', size: '180px', anim: 'anim-travel-right', delay: '-1s', rotation: '0deg' },
                    { type: 'dots', color: 'text-blue-500/20', top: '-2%', left: '95%', size: '180px', anim: 'anim-travel-left', delay: '-5s', rotation: '0deg' },
                    { type: 'dots', color: 'text-blue-500/20', top: '88%', left: '-5%', size: '180px', anim: 'anim-travel-up', delay: '-3s', rotation: '0deg' },
                    { type: 'dots', color: 'text-blue-500/20', top: '88%', left: '95%', size: '180px', anim: 'anim-travel-down', delay: '-8s', rotation: '0deg' },

                    // --- LINE ANIMATIONS (Dynamic 'drawing' paths to add energetic motion) ---
                    { type: 'zigzag', color: 'text-[#4dd9f0]/40', top: '10%', left: '-5%', size: '100px', anim: 'anim-travel-up', delay: '-2s', rotation: '20deg' },
                    { type: 'wave', color: 'text-[#FF851B]/30', top: '15%', left: '95%', size: '120px', anim: 'anim-travel-down', delay: '-6s', rotation: '-15deg' },
                    { type: 'straight-line', color: 'text-[#d0f69a]/80', top: '75%', left: '-5%', size: '150px', anim: 'anim-travel-right', delay: '-4s', rotation: '45deg', lowOpMobile: true },
                    { type: 'cross', color: 'text-[#4dd9f0]/40', top: '80%', left: '95%', size: '80px', anim: 'anim-travel-left', delay: '-7s', rotation: '10deg', lowOpMobile: true },

                    // --- CORNERS (Always visible, responsive size) ---
                    { type: 'morph', color: 'text-[#4dd9f0]/30', top: '-5%', left: '-5%', size: '170px', anim: 'anim-travel-up', delay: '0s', rotation: '15deg' }, // Top-Left
                    { type: 'half-circle', color: 'text-[#d0f69a]/60', top: '-5%', left: '95%', size: '160px', anim: 'anim-travel-down', delay: '-4s', rotation: '80deg' }, // Top-Right
                    { type: 'right-triangle', color: 'text-[#d0f69a]/60', top: '95%', left: '-5%', size: '150px', anim: 'anim-travel-down', delay: '-8s', rotation: '-15deg', lowOpMobile: true }, // Bottom-Left
                    { type: 'quarter-circle', color: 'text-[#FF851B]/20', top: '95%', left: '95%', size: '150px', anim: 'anim-travel-left', delay: '-2s', rotation: '25deg', lowOpMobile: true }, // Bottom-Right

                    // --- TOP EDGE (Hidden on mobile to prevent title clash) ---
                    { type: 'stairs', desktopOnly: true, color: 'text-[#FF851B]/20', top: '-5%', left: '20%', size: '130px', anim: 'anim-travel-right', delay: '-3s', rotation: '45deg' },
                    { type: 'pill', desktopOnly: true, color: 'text-[#4dd9f0]/30', top: '-5%', left: '80%', size: '140px', anim: 'anim-travel-left', delay: '-5s', rotation: '-10deg' },

                    // --- BOTTOM EDGE (Translucent watermark on mobile) ---
                    { type: 'circle', color: 'text-[#d0f69a]/60', top: '95%', left: '25%', size: '140px', anim: 'anim-travel-right', delay: '-7s', rotation: '30deg', lowOpMobile: true },
                    { type: 'diamond', color: 'text-[#4dd9f0]/30', top: '95%', left: '75%', size: '120px', anim: 'anim-travel-up', delay: '-1s', rotation: '60deg', lowOpMobile: true },

                    // --- LEFT EDGE (Always visible, responsive size) ---
                    { type: 'circle', color: 'text-[#4dd9f0]/30', top: '35%', left: '-5%', size: '140px', anim: 'anim-travel-left', delay: '-3s', rotation: '-20deg' },
                    { type: 'pill', color: 'text-[#FF851B]/20', top: '65%', left: '-5%', size: '160px', anim: 'anim-travel-right', delay: '-6s', rotation: '45deg', lowOpMobile: true },

                    // --- RIGHT EDGE (Always visible, responsive size) ---
                    { type: 'diamond', color: 'text-[#FF851B]/20', top: '35%', left: '95%', size: '140px', anim: 'anim-travel-right', delay: '-7s', rotation: '-30deg' },
                    { type: 'morph', color: 'text-[#4dd9f0]/30', top: '65%', left: '95%', size: '170px', anim: 'anim-travel-up', delay: '-1s', rotation: '60deg', lowOpMobile: true }
                ];

                // Scroll observer to trigger pop-in animation
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        this.shown = true;
                        observer.disconnect(); // Only animate once
                    }
                }, { threshold: 0.1 }); // Trigger when 10% of section is visible
                observer.observe(this.$el);
            }
        }">
            <template x-for="(shape, index) in shapes" :key="index">
                <!-- Outer wrapper: Handles absolute positioning and the pop-in scroll transition -->
                <div class="absolute items-center justify-center transition-all duration-1000 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-center"
                     :class="[
                        shown ? 'scale-100 opacity-100' : 'scale-0 opacity-0',
                        shape.desktopOnly ? 'hidden md:flex' : 'flex'
                     ]"
                     :style="`top: ${shape.top}; left: ${shape.left}; width: clamp(50px, 12vw, ${shape.size}); height: clamp(50px, 12vw, ${shape.size}); transition-delay: ${index * 80}ms;`">
                    
                    <!-- Inner wrapper: Handles the continuous travel animation -->
                    <div class="w-full h-full flex items-center justify-center transition-opacity duration-500"
                         :class="[shape.anim, shape.color, shape.lowOpMobile ? 'opacity-20 md:opacity-100' : '']"
                         :style="`animation-delay: ${shape.delay};`">
                         
                        <!-- Shape rotation container -->
                        <div class="w-full h-full flex items-center justify-center" :style="`transform: rotate(${shape.rotation});`">
                            
                            <!-- Bauhaus Half Circle -->
                            <template x-if="shape.type === 'half-circle'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M 0 50 A 50 50 0 0 1 100 50 Z" />
                            </svg>
                        </template>
                        
                        <!-- Bauhaus Right Triangle -->
                        <template x-if="shape.type === 'right-triangle'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <polygon points="0,0 100,100 0,100" />
                            </svg>
                        </template>

                        <!-- Morphing Square to Circle -->
                        <template x-if="shape.type === 'morph'">
                            <div class="w-full h-full bg-current anim-morph"></div>
                        </template>

                        <!-- Solid Circle -->
                        <template x-if="shape.type === 'circle'">
                            <div class="w-full h-full rounded-full bg-current"></div>
                        </template>

                        <!-- Quarter Circle -->
                        <template x-if="shape.type === 'quarter-circle'">
                            <div class="w-full h-full bg-current rounded-tl-full"></div>
                        </template>

                        <!-- Solid Pill -->
                        <template x-if="shape.type === 'pill'">
                            <div class="w-full h-1/2 rounded-full bg-current"></div>
                        </template>

                        <!-- Solid Diamond -->
                        <template x-if="shape.type === 'diamond'">
                            <div class="w-full h-full bg-current" style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"></div>
                        </template>

                        <!-- Stepped Stairs -->
                        <template x-if="shape.type === 'stairs'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="currentColor">
                                <polygon points="0,100 0,66 33,66 33,33 66,33 66,0 100,0 100,100" />
                            </svg>
                        </template>

                        <!-- Dotted Grid Pattern -->
                        <template x-if="shape.type === 'dots'">
                            <svg class="w-full h-full" width="100%" height="100%">
                                <defs>
                                    <pattern :id="'dotPatternAch' + index" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                                        <circle cx="9" cy="9" r="1.5" fill="currentColor" class="anim-dot-1" />
                                        <circle cx="27" cy="9" r="1.5" fill="currentColor" class="anim-dot-2" />
                                        <circle cx="9" cy="27" r="1.5" fill="currentColor" class="anim-dot-3" />
                                        <circle cx="27" cy="27" r="1.5" fill="currentColor" class="anim-dot-4" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" :fill="'url(#' + 'dotPatternAch' + index + ')'" />
                            </svg>
                        </template>

                        <!-- Line Animations -->
                        <template x-if="shape.type === 'zigzag'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M 10,50 L 30,20 L 50,80 L 70,20 L 90,50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'wave'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M 10,50 Q 30,20 50,50 T 90,50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'straight-line'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <line x1="10" y1="50" x2="90" y2="50" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>
                        <template x-if="shape.type === 'cross'">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <line x1="20" y1="20" x2="80" y2="80" pathLength="100" class="anim-draw-line" />
                                <line x1="80" y1="20" x2="20" y2="80" pathLength="100" class="anim-draw-line" />
                            </svg>
                        </template>

                    </div>
                </div>
            </template>
        </div>
        <div x-data="{ sectionVisible: false }" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" class="transition-all duration-1000 ease-out max-w-[1400px] mx-auto px-4 lg:px-6 relative z-10 opacity-0 translate-y-12">
            
            <!-- Top Header & Pills -->
            <div class="text-center mb-8 lg:mb-24">
                <h3 class="text-[3rem] lg:text-[5rem] font-bold tracking-tighter text-black mb-5 lg:mb-8 font-display uppercase leading-none">Achievements</h3>

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
                <div class="w-full lg:w-[40%] xl:w-[35%] text-center lg:text-left pt-2 lg:pt-4 order-2 lg:order-1">
                    <!-- Mobile heading: single line, centered -->
                    <h2 class="text-[1.75rem] lg:text-[2.75rem] xl:text-[3.25rem] whitespace-nowrap font-bold tracking-tight text-black mb-3 lg:mb-6 leading-tight font-poppins">
                        Proof of <span class="font-bold" style="font-weight: 700 !important;" x-text="activeTab === 'all' ? 'Impact' : (activeTab === 'award' ? 'Excellence' : 'Skill')"></span>
                    </h2>
                    <p class="text-gray-500 text-sm lg:text-lg leading-relaxed mb-6 lg:mb-12 font-medium font-poppins max-w-xs mx-auto lg:mx-0 lg:max-w-none">
                        Being appreciated for the work I do means the world to me. Whether achieved individually or alongside a talented team, these milestones translate beautifully into continuous learning and industry recognition.
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
                                 @if(!$profile->disable_achievements_modal)
                                 @click="selectedItem = { title: {{ Js::from($item->title) }}, issuer: {{ Js::from($item->issuer) }}, year: {{ Js::from($item->year) }}, description: {{ Js::from($item->description) }}, type: '{{ $itemType }}', media_path: {{ Js::from($item->media_path ? (Str::startsWith($item->media_path, 'http') ? $item->media_path : ((Str::startsWith($item->media_path, 'images/') || Str::startsWith($item->media_path, 'videos/')) ? asset($item->media_path) : Storage::url($item->media_path))) : null) }} }"
                                 @endif
                                 class="flex-none snap-center lg:snap-start group {{ $profile->disable_achievements_modal ? '' : 'cursor-pointer' }} relative"
                                 style="width: min(78vw, 300px);">
                                 
                                <div class="relative overflow-hidden bg-white rounded-[1.5rem] lg:rounded-[2rem] p-6 lg:p-10 flex flex-col items-center text-center transition-all duration-500 transform shadow-[0_8px_30px_rgb(0,0,0,0.06)] {{ $profile->disable_achievements_modal ? '' : 'group-hover:-translate-y-2 group-hover:shadow-[0_20px_40px_rgb(94,23,235,0.12)] group-hover:bg-[#E5C14D] group-hover:border-[#C4A030]' }} border-2 border-gray-100 h-auto lg:h-[420px] min-h-[300px]">
                                    
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
    <section id="experience" class="relative overflow-hidden flex flex-col" style="background:#0d0d0d; min-height: 100vh;" x-data="{ activeIndex: null, bgIndex: 0, isLoading: false, select(i) { if (this.activeIndex === i) { this.activeIndex = null; } else if (this.activeIndex !== null) { this.isLoading = true; this.activeIndex = i; setTimeout(() => { this.isLoading = false; }, 600); } else { this.activeIndex = i; } } }">
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
                        <video src="{{ (Str::startsWith($profile->exp_default_bg_media_path, 'http') ? $profile->exp_default_bg_media_path : ((Str::startsWith($profile->exp_default_bg_media_path, 'images/') || Str::startsWith($profile->exp_default_bg_media_path, 'videos/')) ? asset($profile->exp_default_bg_media_path) : Storage::url($profile->exp_default_bg_media_path))) }}" loop muted playsinline preload="none" x-intersect:enter="$el.play().catch(()=>{})" x-intersect:leave="$el.pause()" class="w-full h-full object-cover opacity-60"></video>
                    @elseif($profile->exp_default_bg_type === 'slideshow' && !empty($profile->exp_default_bg_gallery_images))
                        <div x-data="{ sIndex: 0, sTotal: {{ count($profile->exp_default_bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === null) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                            @foreach($profile->exp_default_bg_gallery_images as $slideIndex => $sImage)
                                <img src="{{ (Str::startsWith($sImage, 'http') ? $sImage : ((Str::startsWith($sImage, 'images/') || Str::startsWith($sImage, 'videos/')) ? asset($sImage) : Storage::url($sImage))) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                            @endforeach
                        </div>
                    @elseif($profile->exp_default_bg_media_path)
                        <img src="{{ (Str::startsWith($profile->exp_default_bg_media_path, 'http') ? $profile->exp_default_bg_media_path : ((Str::startsWith($profile->exp_default_bg_media_path, 'images/') || Str::startsWith($profile->exp_default_bg_media_path, 'videos/')) ? asset($profile->exp_default_bg_media_path) : Storage::url($profile->exp_default_bg_media_path))) }}" class="w-full h-full object-cover opacity-60">
                    @endif
                </div>

                <!-- Individual Experience Media when selected -->
                @foreach($experiences as $i => $exp)
                    <div x-show="activeIndex === {{ $i }}"
                         x-transition.opacity.duration.1500ms
                         class="absolute inset-0">
                         @if($exp->bg_media_type === 'video' && $exp->bg_media_path)
                             <video src="{{ (Str::startsWith($exp->bg_media_path, 'http') ? $exp->bg_media_path : ((Str::startsWith($exp->bg_media_path, 'images/') || Str::startsWith($exp->bg_media_path, 'videos/')) ? asset($exp->bg_media_path) : Storage::url($exp->bg_media_path))) }}" loop muted playsinline preload="none" x-intersect:enter="$el.play()" x-intersect:leave="$el.pause(); $el.removeAttribute('src'); $el.load();" class="w-full h-full object-cover opacity-60"></video>
                         @elseif($exp->bg_media_type === 'slideshow' && !empty($exp->bg_gallery_images))
                             <div x-data="{ sIndex: 0, sTotal: {{ count($exp->bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === {{ $i }}) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                                 @foreach($exp->bg_gallery_images as $slideIndex => $sImage)
                                     <img src="{{ (Str::startsWith($sImage, 'http') ? $sImage : ((Str::startsWith($sImage, 'images/') || Str::startsWith($sImage, 'videos/')) ? asset($sImage) : Storage::url($sImage))) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                                 @endforeach
                             </div>
                         @elseif($exp->bg_media_path)
                             <img src="{{ (Str::startsWith($exp->bg_media_path, 'http') ? $exp->bg_media_path : ((Str::startsWith($exp->bg_media_path, 'images/') || Str::startsWith($exp->bg_media_path, 'videos/')) ? asset($exp->bg_media_path) : Storage::url($exp->bg_media_path))) }}" class="w-full h-full object-cover opacity-60">
                         @elseif($exp->image_path)
                             <img src="{{ (Str::startsWith($exp->image_path, 'http') ? $exp->image_path : ((Str::startsWith($exp->image_path, 'images/') || Str::startsWith($exp->image_path, 'videos/')) ? asset($exp->image_path) : Storage::url($exp->image_path))) }}" class="w-full h-full object-cover opacity-60">
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
                             <video src="{{ (Str::startsWith($exp->bg_media_path, 'http') ? $exp->bg_media_path : ((Str::startsWith($exp->bg_media_path, 'images/') || Str::startsWith($exp->bg_media_path, 'videos/')) ? asset($exp->bg_media_path) : Storage::url($exp->bg_media_path))) }}" loop muted playsinline preload="none" x-intersect:enter="$el.play()" x-intersect:leave="$el.pause(); $el.removeAttribute('src'); $el.load();" class="w-full h-full object-cover opacity-60"></video>
                         @elseif($exp->bg_media_type === 'slideshow' && !empty($exp->bg_gallery_images))
                             <div x-data="{ sIndex: 0, sTotal: {{ count($exp->bg_gallery_images) }} }" x-init="setInterval(() => { if (activeIndex === {{ $i }} || (activeIndex === null && bgIndex === {{ $i }})) sIndex = (sIndex + 1) % sTotal }, 4000)" class="w-full h-full">
                                 @foreach($exp->bg_gallery_images as $slideIndex => $sImage)
                                     <img src="{{ (Str::startsWith($sImage, 'http') ? $sImage : ((Str::startsWith($sImage, 'images/') || Str::startsWith($sImage, 'videos/')) ? asset($sImage) : Storage::url($sImage))) }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" :class="sIndex === {{ $slideIndex }} ? 'opacity-60' : 'opacity-0'">
                                 @endforeach
                             </div>
                         @elseif($exp->bg_media_path)
                             <img src="{{ (Str::startsWith($exp->bg_media_path, 'http') ? $exp->bg_media_path : ((Str::startsWith($exp->bg_media_path, 'images/') || Str::startsWith($exp->bg_media_path, 'videos/')) ? asset($exp->bg_media_path) : Storage::url($exp->bg_media_path))) }}" class="w-full h-full object-cover opacity-60">
                         @elseif($exp->image_path)
                             <img src="{{ (Str::startsWith($exp->image_path, 'http') ? $exp->image_path : ((Str::startsWith($exp->image_path, 'images/') || Str::startsWith($exp->image_path, 'videos/')) ? asset($exp->image_path) : Storage::url($exp->image_path))) }}" class="w-full h-full object-cover opacity-60">
                         @endif
                    </div>
                @endforeach
                <!-- Muted dark overlay so text pops -->
                <div class="absolute inset-0 bg-black/85 transition-opacity duration-500" :class="activeIndex !== null ? 'opacity-90' : 'opacity-70'"></div>
                <!-- Extra vignette gradient top & bottom -->
                <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(13,13,13,0.9) 0%, transparent 25%, transparent 75%, rgba(13,13,13,0.95) 100%); pointer-events: none;"></div>
            </div>
        @endif


        <div x-data="{ sectionVisible: false }" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" class="transition-all duration-1000 ease-out relative z-20 flex flex-col max-w-[1200px] mx-auto w-full px-6 pt-28 pb-24 opacity-0 translate-y-12"
             style="flex: 1;">

            <!-- Section header -->
            <div class="text-center mb-16">
                <p class="font-mono text-[11px] uppercase tracking-[0.35em] text-[#FF851B] mb-3">Career Path</p>
                <h2 class="font-display text-[3rem] sm:text-[5rem] uppercase tracking-tighter text-white leading-none" style="text-shadow: 0 0 40px rgba(255,133,27,0.3);">Work Experience</h2>
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
                     class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex flex-col p-6 pt-20 h-[100dvh] md:relative md:inset-auto md:z-auto md:bg-transparent md:backdrop-blur-none md:p-0 md:flex-1 md:h-[calc(100vh-12rem)] md:pr-8 md:sticky md:top-28"
                     style="display: none;">
                     
                    <!-- Back to Timeline Button -->
                    <button @click="activeIndex = null" class="group flex items-center gap-2 text-[#FF851B] hover:text-[#ff9c45] transition-colors mb-4 md:mb-0 md:absolute md:-top-12 md:left-0 z-[110] text-[10px] sm:text-xs font-mono uppercase tracking-[0.2em] shrink-0 w-fit">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Timeline
                    </button>
                    
                    <div class="relative w-full mt-4 md:mt-0" style="flex: 1; min-height: 0;">
                        <!-- Skeleton Loader -->
                        <div x-show="isLoading"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0 flex flex-col z-[110]" style="min-height: 0;">
                            
                             <div class="flex-shrink-0 pt-4 pb-2">
                                 <div class="block w-full border border-white/10 rounded-xl px-5 py-4 backdrop-blur-md animate-pulse" style="background: rgba(255,255,255,0.05);">
                                     <div class="h-[1.4rem] sm:h-[1.8rem] bg-white/10 rounded w-2/3 mb-3"></div>
                                     <div class="flex items-center gap-2 mt-2">
                                         <div class="h-[10px] bg-white/10 rounded w-1/4"></div>
                                         <div class="w-1 h-1 bg-white/10 rounded-full"></div>
                                         <div class="h-[10px] bg-white/10 rounded w-1/6"></div>
                                     </div>
                                 </div>
                             </div>

                             <div class="flex-1 overflow-hidden pr-2 pb-5 space-y-6 mt-4">
                                 <div class="space-y-4 animate-pulse">
                                     <div class="h-4 bg-white/10 rounded w-full"></div>
                                     <div class="h-4 bg-white/10 rounded w-[95%]"></div>
                                     <div class="h-4 bg-white/10 rounded w-[90%]"></div>
                                     <div class="h-4 bg-white/10 rounded w-[85%]"></div>
                                     <div class="h-4 bg-white/10 rounded w-[60%]"></div>
                                 </div>
                                 <div class="space-y-4 animate-pulse pt-4">
                                     <div class="h-4 bg-white/10 rounded w-full"></div>
                                     <div class="h-4 bg-white/10 rounded w-[92%]"></div>
                                     <div class="h-4 bg-white/10 rounded w-[88%]"></div>
                                     <div class="h-4 bg-white/10 rounded w-[75%]"></div>
                                 </div>
                             </div>
                        </div>

                    @foreach($experiences as $i => $exp)
                        <div x-show="activeIndex === {{ $i }} && !isLoading"
                             x-transition:enter="transition ease-out duration-400"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0 flex flex-col" style="min-height: 0;">

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
                                     :class="activeIndex === {{ $i }} ? 'bg-[#a3ff6b] shadow-[0_0_12px_rgba(163,255,107,0.5)] scale-110' : 'bg-white/10'">
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
    <section id="contact" class="bg-[#161616] text-white relative pt-0 pb-24 md:pb-32">


        <div x-data="{ sectionVisible: false }" x-intersect.once.margin.-10%="sectionVisible = true" :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" class="transition-all duration-1000 ease-out max-w-7xl mx-auto px-6 pt-20 relative z-10 opacity-0 translate-y-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <!-- Contact info cards -->
                <div class="lg:col-span-5">
                    <h2 class="text-xs font-mono font-bold uppercase tracking-widest text-[#ff6b00] mb-3">Collaborate</h2>
                    <h3 class="font-display text-[3rem] sm:text-[4.5rem] font-bold tracking-tighter leading-none mb-6 text-white uppercase">Let's craft something premium together</h3>
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
