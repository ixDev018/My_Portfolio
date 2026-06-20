<!DOCTYPE html>
<html lang="en" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <title>@yield('title', 'Brix Cura | Portfolio')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Full-Stack Developer & UI/UX Specialist crafting ultra-premium digital solutions.')">
    <meta name="author" content="Brix Cura">
    <meta property="og:title" content="@yield('title', 'Brix Cura | Portfolio')">
    <meta property="og:description" content="@yield('meta_description', 'Full-Stack Developer & UI/UX Specialist crafting ultra-premium digital solutions.')">
    <meta property="og:type" content="website">
    
    <!-- Google Fonts: Space Grotesk (Titles), Jaro (Logo) & Bitcount Single (Body/Menu) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Single&family=Jaro:opsz@6..72&family=Space+Grotesk:wght@700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS and Vite asset compilation -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS for fluid interactive behaviors -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Default body text → Bitcount Single (subtitles, nav, labels, buttons) */
        body, .font-sans, .font-mono, button, a, span, label, input, textarea, li, td, th {
            font-family: 'Bitcount Single', monospace !important;
            font-weight: 400 !important;
        }
        /* Logo → Jaro */
        .font-logo {
            font-family: 'Jaro', sans-serif !important;
            font-weight: 400 !important;
            letter-spacing: normal !important;
        }
        /* Display headings → Grotesk Display */
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Space Grotesk', 'Grotesk Display', sans-serif !important;
            font-weight: 700 !important;
        }
        /* Content/description paragraphs → Poppins */
        .font-poppins, .font-poppins p, .font-poppins * {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Marquee Animation */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 80s linear infinite;
            min-width: max-content;
        }
        
        /* Foolproof pause on hover */
        .group\/marquee:has(:hover) .animate-marquee {
            animation-play-state: paused !important;
        }
        
        /* Hide scrollbar for carousel */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Introduction slides: auto-height on mobile, fills flex parent on desktop */
        .intro-slides-container {
            height: auto;
        }
        @media (min-width: 768px) {
            .intro-slides-container {
                flex: 1;
                height: 100%;
                min-height: 400px;
                max-height: 680px;
                overflow: hidden;
            }
        }

        /* ── Success Modal ── */
        @keyframes successModalIn {
            0%   { opacity: 0; transform: scale(0.7) translateY(30px); }
            65%  { opacity: 1; transform: scale(1.04) translateY(-4px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes successModalOut {
            0%   { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.85) translateY(20px); }
        }
        @keyframes checkDraw {
            0%   { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes circlePop {
            0%   { transform: scale(0); opacity: 0; }
            60%  { transform: scale(1.12); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes progressShrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
        @keyframes rippleOut {
            0%   { transform: scale(1); opacity: 0.35; }
            100% { transform: scale(2.4); opacity: 0; }
        }
        .success-modal-card {
            animation: successModalIn 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .success-modal-card.hiding {
            animation: successModalOut 0.3s ease forwards;
        }
        .success-check-circle {
            animation: circlePop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
        }
        .success-check-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkDraw 0.45s ease 0.4s forwards;
        }
        .success-ripple {
            animation: rippleOut 1s ease 0.3s forwards;
        }
        .success-progress-bar {
            animation: progressShrink linear forwards;
        }

        /* ── Global Wave Loader ── */
        #global-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background-color: #cfd0d1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 1;
            pointer-events: all;
            transition: opacity 0.2s ease;
        }
        .loader-boxes {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }
        .loader-boxes .box {
            width: 20px;
            height: 20px;
            border: 1.5px solid #000;
            box-shadow: 3px 3px 0 #000;
            animation: wave-boxes 1.2s ease-in-out infinite;
        }
        .loader-boxes .box:nth-child(1) { background-color: #f40220; animation-delay: 0s; }
        .loader-boxes .box:nth-child(2) { background-color: #ff7b30; animation-delay: 0.15s; }
        .loader-boxes .box:nth-child(3) { background-color: #e0b617; animation-delay: 0.3s; }
        .loader-boxes .box:nth-child(4) { background-color: #09a953; animation-delay: 0.45s; }

        @keyframes wave-boxes {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes loader-morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 50% 50% 30% 70% / 50% 70% 30% 50%; }
            50% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; }
            75% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        }

        /* ── Floating Visuals for Loader ── */
        @keyframes loader-float-1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(25px, -30px) rotate(15deg) scale(1.05); }
        }
        @keyframes loader-float-2 {
            0%, 100% { transform: translate(0, 0) rotate(45deg) scale(1); }
            50% { transform: translate(-30px, 25px) rotate(25deg) scale(0.95); }
        }
        @keyframes loader-float-3 {
            0%, 100% { transform: translate(0, 0) rotate(-15deg) scale(1); }
            50% { transform: translate(20px, 35px) rotate(5deg) scale(1.1); }
        }
        @keyframes loader-float-4 {
            0%, 100% { transform: translate(0, 0) rotate(90deg) scale(1); }
            50% { transform: translate(-15px, -20px) rotate(75deg) scale(1.05); }
        }
        @keyframes loader-float-5 {
            0%, 100% { transform: translate(0, 0) rotate(-10deg) scale(1); }
            50% { transform: translate(-20px, -15px) rotate(-25deg) scale(1.05); }
        }
        @keyframes loader-float-6 {
            0%, 100% { transform: translate(0, 0) rotate(5deg) scale(1); }
            50% { transform: translate(15px, 20px) rotate(15deg) scale(0.95); }
        }
        @keyframes loader-float-7 {
            0%, 100% { transform: translate(0, 0) rotate(90deg) scale(1); }
            50% { transform: translate(-25px, 10px) rotate(70deg) scale(1.1); }
        }
        @keyframes loader-float-8 {
            0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(20px, -25px) rotate(20deg) scale(0.95); }
        }
    </style>
</head>
<body x-data="{ showResumeModal: false }" class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white min-h-screen flex flex-col overflow-x-hidden">

    <!-- Global Loader -->
    <div id="global-loader" class="overflow-hidden">
        <!-- Floating Bauhaus Visual Elements (Using strict inline styles to bypass Tailwind cache) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center opacity-100">
            <!-- Half Circle (Modern Tangerine) - Top Left -->
            <div class="absolute" style="color: #ff7b30; top: -5%; left: -5%; width: clamp(120px, 20vw, 220px); height: clamp(120px, 20vw, 220px); animation: loader-float-1 8s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="currentColor">
                    <path d="M 0 50 A 50 50 0 0 1 100 50 Z" stroke="black" stroke-width="2" stroke-linejoin="miter" />
                </svg>
            </div>
            
            <!-- Right Triangle (Minimalist Herbs) - Top Right -->
            <div class="absolute" style="color: #09a953; top: 5%; right: -5%; width: clamp(100px, 18vw, 200px); height: clamp(100px, 18vw, 200px); animation: loader-float-2 10s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="currentColor">
                    <polygon points="0,0 100,100 0,100" stroke="black" stroke-width="2" stroke-linejoin="miter" />
                </svg>
            </div>
            
            <!-- Cross (Hexagonal Flamingo) - Bottom Left -->
            <div class="absolute" style="color: #ff5aa9; bottom: 5%; left: -5%; width: clamp(90px, 15vw, 160px); height: clamp(90px, 15vw, 160px); animation: loader-float-3 12s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="currentColor">
                    <path d="M 35 0 H 65 V 35 H 100 V 65 H 65 V 100 H 35 V 65 H 0 V 35 H 35 Z" stroke="black" stroke-width="2" stroke-linejoin="miter" />
                </svg>
            </div>

            <!-- Pill (Abstract Blueprint) - Bottom Right -->
            <div class="absolute" style="color: #0019ff; bottom: -5%; right: -5%; width: clamp(100px, 18vw, 180px); height: clamp(100px, 18vw, 180px); animation: loader-float-4 9s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="currentColor">
                    <rect x="10" y="25" width="80" height="50" rx="25" ry="25" stroke="black" stroke-width="2" />
                </svg>
            </div>

            <!-- Zigzag (Bauhaus Crimson) - Top Center -->
            <div class="absolute" style="color: #f40220; top: -2%; left: 42%; width: clamp(80px, 15vw, 140px); height: clamp(80px, 15vw, 140px); animation: loader-float-5 9s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="none">
                    <polyline points="0,20 25,80 50,20 75,80 100,20" stroke="black" stroke-width="8" stroke-linejoin="miter" />
                    <polyline points="0,20 25,80 50,20 75,80 100,20" stroke="currentColor" stroke-width="4" stroke-linejoin="miter" />
                </svg>
            </div>
            
            <!-- Dots (Geometry Saffron) - Bottom Center -->
            <div class="absolute" style="color: #e0b617; bottom: 2%; left: 45%; width: clamp(70px, 12vw, 120px); height: clamp(70px, 12vw, 120px); animation: loader-float-6 11s ease-in-out infinite;">
                <svg class="w-full h-full drop-shadow-[5px_5px_0_rgba(0,0,0,1)] overflow-visible" viewBox="0 0 100 100" fill="currentColor">
                    <circle cx="20" cy="20" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="50" cy="20" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="80" cy="20" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="20" cy="50" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="50" cy="50" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="80" cy="50" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="20" cy="80" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="50" cy="80" r="8" stroke="black" stroke-width="1.5" />
                    <circle cx="80" cy="80" r="8" stroke="black" stroke-width="1.5" />
                </svg>
            </div>

            <!-- Morphing Shape 1 (Modern Tangerine) - Left Center -->
            <div class="absolute" style="color: #ff7b30; top: 40%; left: 2%; width: clamp(80px, 14vw, 130px); height: clamp(80px, 14vw, 130px); animation: loader-float-7 13s ease-in-out infinite;">
                <div class="w-full h-full bg-current" style="border: 2px solid #000; box-shadow: 5px 5px 0 #000; animation: loader-morph 4s ease-in-out infinite alternate;"></div>
            </div>

            <!-- Morphing Shape 2 (Hexagonal Flamingo) - Right Center -->
            <div class="absolute" style="color: #ff5aa9; top: 45%; right: 2%; width: clamp(60px, 10vw, 100px); height: clamp(60px, 10vw, 100px); animation: loader-float-8 10s ease-in-out infinite;">
                <div class="w-full h-full bg-current" style="border: 2px solid #000; box-shadow: 5px 5px 0 #000; animation: loader-morph 5s ease-in-out infinite alternate-reverse;"></div>
            </div>
        </div>

        <div class="loader-boxes relative z-10">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        <div class="text-black font-bold text-[13px] uppercase tracking-[0.15em] ml-1.5 font-display relative z-10">LOADING</div>
    </div>
    <script>
        (function() {
            var loader = document.getElementById('global-loader');
            
            function hideLoader() {
                if (loader) {
                    loader.style.transition = 'opacity 0.3s ease';
                    loader.style.opacity = '0';
                    loader.style.pointerEvents = 'none';
                    setTimeout(function() { loader.style.display = 'none'; }, 300);
                }
            }

            // 1. Hide smoothly when page finishes loading or parsing
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(hideLoader, 200);
            } else {
                window.addEventListener('DOMContentLoaded', function() {
                    setTimeout(hideLoader, 200);
                });
                window.addEventListener('load', function() {
                    setTimeout(hideLoader, 200);
                });
            }

            // Safety timeout: never keep loader up for more than 2.5 seconds (prevents hangs on slow connections/assets)
            setTimeout(hideLoader, 2500);

            // 2. Instant feedback when clicking links
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (!link) return;
                
                var href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                
                try {
                    var url = new URL(link.href, window.location.href);
                    var isSamePage = (url.pathname === window.location.pathname && url.search === window.location.search);
                    var isAnchorOnly = isSamePage && url.hash;
                    
                    if (!isAnchorOnly && url.hostname === window.location.hostname) {
                        // Delay showing the loader slightly (50ms) to let the browser process the touch/click event
                        // and register the navigation first. Otherwise, showing a full-screen pointer-events overlay
                        // instantly can cancel the navigation on mobile devices (e.g. iOS Safari).
                        setTimeout(function() {
                            if (loader) {
                                loader.style.display = 'flex';
                                void loader.offsetWidth; // Force reflow
                                loader.style.transition = 'opacity 0.15s ease';
                                loader.style.opacity = '1';
                                loader.style.pointerEvents = 'all';
                                
                                // Safety timeout on navigation trigger (e.g. slow network or cancelled navigation)
                                setTimeout(hideLoader, 6000);
                            }
                        }, 50);
                    }
                } catch (err) {}
            });

            // 4. BFCache fix (user presses back button)
            window.addEventListener('pageshow', function(e) {
                if (e.persisted && loader) {
                    loader.style.opacity = '0';
                    loader.style.pointerEvents = 'none';
                    loader.style.display = 'none';
                }
            });
        })();
    </script>


    <!-- Glowing Background blobs for modern premium aesthetic -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-cyan-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-900/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Header / Navbar -->
    <x-navbar />

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    {{-- ── Success / Error Popup Modal ── --}}
    @if(session('success') || session('error'))
        @php
            $isSuccess = session('success');
            $msg = session('success') ?? session('error');
        @endphp
        <div
            id="cms-success-modal"
            x-data="{
                show: true,
                hiding: false,
                dismiss() {
                    this.hiding = true;
                    setTimeout(() => this.show = false, 300);
                }
            }"
            x-show="show"
            x-init="setTimeout(() => dismiss(), 3500)"
            style="display:none;"
            class="fixed inset-0 z-[999] flex items-center justify-center"
            @click.self="dismiss()"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            {{-- Modal Card --}}
            <div class="success-modal-card relative z-10 flex flex-col items-center text-center"
                 :class="hiding ? 'hiding' : ''"
                 style="
                    background: #111111;
                    border: 2px solid #ffffff;
                    border-radius: 0;
                    padding: 3rem 3.5rem 2.5rem;
                    min-width: 320px;
                    max-width: 400px;
                 "
            >
                {{-- Animated Check / Error Icon --}}
                <div class="relative mb-6" style="width:80px; height:80px;">
                    {{-- Flat Square Icon Container --}}
                    <div class="absolute inset-0 flex items-center justify-center"
                         style="background: {{ $isSuccess ? '#10b981' : '#ef4444' }}; border: 2px solid #ffffff;">
                        @if($isSuccess)
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="square" stroke-linejoin="miter">
                                <path class="success-check-path" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="square" stroke-linejoin="miter">
                                <path class="success-check-path" d="M18 6L6 18M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Title --}}
                <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.8rem; font-weight:700; color:#ffffff; margin-bottom:0.5rem; text-transform:uppercase;">
                    {{ $isSuccess ? 'Success!' : 'Error!' }}
                </h2>

                {{-- Message --}}
                <p style="font-family:'Bitcount Single',monospace; font-size:0.85rem; color:#a1a1aa; line-height:1.6; margin-bottom:2rem; text-transform:uppercase; letter-spacing:0.05em;">
                    {{ $msg }}
                </p>

                {{-- Dismiss button --}}
                <button
                    @click="dismiss()"
                    style="
                        font-family:'Bitcount Single',monospace;
                        font-weight:700;
                        font-size:0.8rem;
                        letter-spacing:0.1em;
                        text-transform:uppercase;
                        padding:0.75rem 2.5rem;
                        border-radius:0;
                        border:2px solid #ffffff;
                        cursor:pointer;
                        transition: all 0.2s ease;
                        background: {{ $isSuccess ? '#10b981' : '#ef4444' }};
                        color: white;
                    "
                    onmouseover="this.style.background='#ffffff'; this.style.color='#000000';"
                    onmouseout="this.style.background='{{ $isSuccess ? '#10b981' : '#ef4444' }}'; this.style.color='white';"
                >
                    Got it
                </button>

                {{-- Auto-dismiss progress bar --}}
                <div style="position:absolute; bottom:0; left:0; right:0; height:4px; background:#333333; overflow:hidden;">
                    <div class="success-progress-bar h-full" style="background: {{ $isSuccess ? '#10b981' : '#ef4444' }}; width: 100%; animation-duration: 3.5s;"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Resume PDF Modal -->
    @php
        $profile = \App\Models\Profile::first();
        $cvUrl = asset('resume.pdf');
        if ($profile && $profile->cv_path) {
            if (\Illuminate\Support\Str::startsWith($profile->cv_path, 'http')) {
                $cvUrl = $profile->cv_path;
            } else {
                // Relative path = file in public folder, serve directly
                $cvUrl = asset($profile->cv_path);
            }
        }
        // Use the direct PDF URL to render same-origin/public PDFs cleanly in browser without Google Docs Viewer failing and triggering automatic downloads
        $cvEmbedUrl = $cvUrl;
    @endphp
    <div x-show="showResumeModal" style="display: none;" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background dimming -->
        <div x-show="showResumeModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/85 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-3 sm:p-6">
                <!-- Modal panel: sized to show exactly one A4 page -->
                <div x-show="showResumeModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                     @click.away="showResumeModal = false"
                     class="relative flex flex-col shadow-[0_32px_80px_rgba(0,0,0,0.7)] transition-all w-full"
                     style="max-height: 96vh; width: min(640px, calc((96vh - 40px) * (210/297)));">

                    <!-- Slim dark header bar -->
                    <div class="flex items-center justify-between px-4 flex-shrink-0"
                         style="height:40px; background:#1e1e1e; border-bottom:1px solid rgba(255,255,255,0.08);">
                        <span style="font-family:'Space Mono',monospace;font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.4);">Resume / CV</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $cvUrl }}" download
                               style="font-family:'Space Mono',monospace;"
                               class="flex items-center gap-1.5 px-3 py-1 text-white/60 hover:text-white hover:bg-white/10 transition-all rounded text-[10px] font-bold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                            <button @click="showResumeModal = false"
                                    class="w-7 h-7 flex items-center justify-center rounded text-white/40 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- PDF embed via Google Docs Viewer (bypasses X-Frame-Options) -->
                    <div style="aspect-ratio: 210/297; overflow:hidden; background:#525659; flex-shrink:0;">
                        <iframe
                            src="{{ $cvEmbedUrl }}"
                            style="width:100%; height:100%; border:none; display:block;"
                            title="Resume PDF">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Preview Modal -->
    <div x-data="{
        show: false, v: '', i: '', t: '', m: '', y: ''
    }" @open-global-preview.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y"
       @open-ui-modal.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y"
       @show-outputs-preview.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y">
        
        <div x-show="show"
             style="display: none;"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:p-6 bg-black/80 backdrop-blur-md"
             x-transition.opacity>

            <div @click.away="show = false; if($refs.globalModalVid) $refs.globalModalVid.pause();"
                 class="bg-[#0A0A0A] w-auto min-w-[300px] max-w-[95vw] md:max-w-5xl rounded-3xl border border-white/10 shadow-2xl flex flex-col overflow-hidden relative"
                 x-transition.scale.95>

                <!-- Top Center: Story coming soon pill -->
                <div class="absolute top-4 left-1/2 -translate-x-1/2 md:top-6 z-50">
                    <div class="px-3 py-1.5 bg-black/50 backdrop-blur-sm border border-white/10 rounded-full flex items-center gap-2 shadow-sm">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#6829AA] animate-pulse"></div>
                        <span class="font-mono text-[10px] text-white/80 uppercase tracking-widest whitespace-nowrap">Story coming soon</span>
                    </div>
                </div>

                <!-- Close button -->
                <button @click="show = false; if($refs.globalModalVid) $refs.globalModalVid.pause();" class="absolute top-4 right-4 md:top-6 md:right-6 z-50 shrink-0 w-10 h-10 bg-black/50 hover:bg-white/10 border border-white/10 rounded-full flex items-center justify-center text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Media Area -->
                <div class="relative bg-black flex items-center justify-center p-4 md:p-8 pt-20 md:pt-20">
                    <video x-show="v" x-ref="globalModalVid" :src="show ? v : ''" class="rounded-xl shadow-2xl object-contain" style="max-width: 100%; max-height: 65vh;" controls autoplay playsinline></video>
                    <img x-show="!v && i" :src="show ? i : ''" class="rounded-xl shadow-2xl object-contain" style="max-width: 100%; max-height: 65vh;">
                    <div x-show="!v && !i" class="py-24 px-6 flex flex-col items-center text-center">
                        <svg class="w-16 h-16 text-white/20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <h3 class="font-logo text-3xl md:text-4xl text-white tracking-widest uppercase mb-2" x-text="t"></h3>
                        <p class="font-mono text-sm text-white/50 uppercase tracking-widest">Story coming soon</p>
                    </div>
                </div>

                <!-- Footer: Title & Badges -->
                <div class="p-6 md:p-8 flex flex-col gap-2 bg-[#1A1A1A] border-t border-white/5">
                    <div class="flex flex-wrap items-center gap-3">
                        <h3 class="font-logo text-2xl md:text-3xl text-white tracking-widest uppercase drop-shadow-xl" x-text="t"></h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span x-show="y" class="px-2 py-0.5 rounded bg-black/40 backdrop-blur-md border border-white/10 font-mono text-[10px] text-white/90 uppercase shadow-sm" x-text="y"></span>
                        <span x-show="m" class="px-2 py-0.5 rounded bg-black/40 backdrop-blur-md border border-white/10 font-mono text-[10px] text-white/90 uppercase shadow-sm" x-text="m"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
