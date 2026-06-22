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

        /* ── Global Top Line Loader ── */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            z-index: 999999;
            pointer-events: none;
            opacity: 1;
            transition: opacity 0.3s ease;
            overflow: hidden;
        }
        .progress-line {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #ff6b00;
            width: 0%;
            transition: width 0.2s ease-out;
            box-shadow: 0 0 10px #ff6b00, 0 0 5px #ff6b00;
        }
    </style>
</head>
<body x-data="{ showResumeModal: false }" class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white min-h-screen flex flex-col overflow-x-hidden">

    <!-- Global Loader -->
    <div id="global-loader">
        <div class="progress-line"></div>
    </div>
    <script>
        (function() {
            var loader = document.getElementById('global-loader');
            var progressLine = loader.querySelector('.progress-line');
            var navigating = false;

            function startLoader() {
                if (!loader || !progressLine) return;
                // Prevent restarting the animation if already running
                if (navigating && progressLine.style.width !== '' && progressLine.style.width !== '0%') return;
                
                loader.style.display = 'block';
                loader.style.opacity = '1';
                loader.style.transition = 'none';
                
                // Snap to 0% without transition
                progressLine.style.transition = 'none';
                progressLine.style.width = '0%';
                
                // Force reflow
                void progressLine.offsetWidth;
                
                // Slow crawl to 95% over 10 seconds
                progressLine.style.transition = 'width 10s cubic-bezier(0.1, 0.8, 0.2, 1)';
                progressLine.style.width = '95%';
            }

            function hideLoader(instant) {
                if (navigating) return;
                if (!loader || !progressLine) return;
                
                if (instant) {
                    loader.style.opacity = '0';
                    loader.style.pointerEvents = 'none';
                    loader.style.display = 'none';
                    progressLine.style.transition = 'none';
                    progressLine.style.width = '0%';
                } else {
                    // Fast zip to 100%
                    progressLine.style.transition = 'width 0.3s ease-out';
                    progressLine.style.width = '100%';
                    
                    // Wait for the zip to finish, then fade out
                    setTimeout(function() {
                        loader.style.transition = 'opacity 0.4s ease';
                        loader.style.opacity = '0';
                        loader.style.pointerEvents = 'none';
                        
                        setTimeout(function() {
                            if (!navigating) {
                                loader.style.display = 'none';
                                progressLine.style.transition = 'none';
                                progressLine.style.width = '0%';
                            }
                        }, 400);
                    }, 300);
                }
            }

            // Fire on initial load
            startLoader();

            window.addEventListener('pageshow', function(e) {
                navigating = false;
                if (e.persisted) {
                    hideLoader(true);
                } else {
                    hideLoader(false);
                }
            });

            // Fallback for document load
            if (document.readyState === 'complete') {
                navigating = false;
                hideLoader(false);
            } else {
                window.addEventListener('load', function() {
                    navigating = false;
                    hideLoader(false);
                });
            }

            // Maximum timeout failsafe
            setTimeout(function() { navigating = false; hideLoader(false); }, 15000);

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
                        e.preventDefault(); // Delay actual navigation
                        navigating = true;
                        startLoader();
                        
                        // Wait 300ms for the loader to visually appear, then navigate
                        setTimeout(function() {
                            window.location.href = link.href;
                        }, 300);
                    }
                } catch (err) {}
            });

            window.addEventListener('beforeunload', function() {
                navigating = true;
                startLoader();
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
        $cvUrl = asset('Cura_BrixJorie_CV.pdf');
        $cvEmbedUrl = $cvUrl . '#navpanes=0&view=FitH&scrollbar=0';
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
                     style="max-height: 96vh; width: min(900px, calc((96vh - 40px) * (210/297)));">

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
                            :src="showResumeModal ? '{{ $cvEmbedUrl }}' : ''"
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
        show: false, v: '', i: '', t: '', m: '', y: '', gallery: [], galleryRatio: '16/9', currentSlide: 0
    }" @open-global-preview.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y; gallery = $event.detail.g || []; galleryRatio = $event.detail.r || '16/9'; currentSlide = 0"
       @open-ui-modal.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y; gallery = $event.detail.g || []; galleryRatio = $event.detail.r || '16/9'; currentSlide = 0"
       @show-outputs-preview.window="show = true; v = $event.detail.v; i = $event.detail.i; t = $event.detail.t; m = $event.detail.m; y = $event.detail.y; gallery = $event.detail.g || []; galleryRatio = $event.detail.r || '16/9'; currentSlide = 0">
        
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
                    
                    <div x-show="!v && gallery.length > 0" class="relative w-full flex items-center justify-center" style="max-height: 65vh;">
                        <div class="relative rounded-xl overflow-hidden shadow-2xl flex items-center justify-center" :style="`aspect-ratio: ${galleryRatio.replace(':', '/')}; height: 65vh; max-height: 65vh; max-width: 100%; width: auto;`">
                            <template x-for="(img, idx) in gallery" :key="idx">
                                <img :src="img" x-show="currentSlide === idx" loading="lazy" class="absolute inset-0 w-full h-full object-contain transition-opacity duration-500" :class="currentSlide === idx ? 'opacity-100' : 'opacity-0 pointer-events-none'">
                            </template>
                            
                            <!-- Gallery Dots -->
                            <div x-show="gallery.length > 1" class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20">
                                <template x-for="(img, idx) in gallery" :key="idx">
                                    <button @click="currentSlide = idx" class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-all shadow-sm" :class="currentSlide === idx ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/60'"></button>
                                </template>
                            </div>
                            
                            <!-- Prev/Next -->
                            <button x-show="gallery.length > 1" @click="currentSlide = currentSlide === 0 ? gallery.length - 1 : currentSlide - 1" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/80 border border-white/10 text-white flex items-center justify-center transition-all z-20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button x-show="gallery.length > 1" @click="currentSlide = currentSlide === gallery.length - 1 ? 0 : currentSlide + 1" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/80 border border-white/10 text-white flex items-center justify-center transition-all z-20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <img x-show="!v && gallery.length === 0 && i" :src="show ? i : ''" class="rounded-xl shadow-2xl object-contain" style="max-width: 100%; max-height: 65vh;">
                    
                    <div x-show="!v && gallery.length === 0 && !i" class="py-24 px-6 flex flex-col items-center text-center">
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
