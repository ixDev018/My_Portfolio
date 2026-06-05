<!DOCTYPE html>
<html lang="en" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Brix Cura | Portfolio')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Full-Stack Developer & UI/UX Specialist crafting ultra-premium digital solutions.')">
    <meta name="author" content="Brix Cura">
    <meta property="og:title" content="@yield('title', 'Brix Cura | Portfolio')">
    <meta property="og:description" content="@yield('meta_description', 'Full-Stack Developer & UI/UX Specialist crafting ultra-premium digital solutions.')">
    <meta property="og:type" content="website">
    
    <!-- Google Fonts: Jaro (Titles/Logo) & Bitcount Single (Body/Menu) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Single&family=Jaro:opsz@6..72&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS and Vite asset compilation -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS for fluid interactive behaviors -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Default body text → Bitcount Single (subtitles, nav, labels, buttons) */
        body, .font-sans, .font-mono, button, a, span, label, input, textarea, li, td, th {
            font-family: 'Bitcount Single', monospace !important;
        }
        /* Display headings → Jaro */
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Jaro', sans-serif !important;
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
    </style>
</head>
<body x-data="{ showResumeModal: false }" class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white min-h-screen flex flex-col overflow-x-hidden">

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
                    background: #1e293b; /* slate-800 */
                    border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 24px;
                    padding: 3rem 3.5rem 2.5rem;
                    min-width: 320px;
                    max-width: 400px;
                    box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
                 "
            >
                {{-- Animated Check / Error Icon --}}
                <div class="relative mb-6" style="width:88px; height:88px;">
                    {{-- Ripple ring --}}
                    <div class="success-ripple absolute inset-0 rounded-full" style="background: {{ $isSuccess ? 'rgba(16,185,129,0.18)' : 'rgba(239,68,68,0.18)' }};"></div>

                    {{-- Circle --}}
                    <div class="success-check-circle absolute inset-0 rounded-full flex items-center justify-center"
                         style="background: {{ $isSuccess ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }}; box-shadow: 0 8px 24px {{ $isSuccess ? 'rgba(16,185,129,0.4)' : 'rgba(239,68,68,0.4)' }};">
                        @if($isSuccess)
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path class="success-check-path" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path class="success-check-path" d="M18 6L6 18M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Title --}}
                <h2 style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:800; color:#f8fafc; margin-bottom:0.5rem; letter-spacing:-0.02em;">
                    {{ $isSuccess ? 'Success!' : 'Error!' }}
                </h2>

                {{-- Message --}}
                <p style="font-family:'Poppins',sans-serif; font-size:0.9rem; color:#94a3b8; line-height:1.6; margin-bottom:2rem;">
                    {{ $msg }}
                </p>

                {{-- Dismiss button --}}
                <button
                    @click="dismiss()"
                    style="
                        font-family:'Outfit',sans-serif;
                        font-weight:700;
                        font-size:0.8rem;
                        letter-spacing:0.06em;
                        text-transform:uppercase;
                        padding:0.65rem 2.5rem;
                        border-radius:100px;
                        border:none;
                        cursor:pointer;
                        transition: all 0.2s ease;
                        background: {{ $isSuccess ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }};
                        color: white;
                        box-shadow: 0 4px 14px {{ $isSuccess ? 'rgba(16,185,129,0.25)' : 'rgba(239,68,68,0.25)' }};
                    "
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px {{ $isSuccess ? 'rgba(16,185,129,0.4)' : 'rgba(239,68,68,0.4)' }}'"
                    onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px {{ $isSuccess ? 'rgba(16,185,129,0.25)' : 'rgba(239,68,68,0.25)' }}'"
                >
                    Got it
                </button>

                {{-- Auto-dismiss progress bar --}}
                <div style="position:absolute; bottom:0; left:0; right:0; height:3px; background:rgba(255,255,255,0.05); border-radius:0 0 24px 24px; overflow:hidden;">
                    <div
                        class="success-progress-bar"
                        style="
                            height:100%;
                            background: {{ $isSuccess ? 'linear-gradient(90deg,#10b981,#34d399)' : 'linear-gradient(90deg,#ef4444,#f87171)' }};
                            animation-duration: 3.5s;
                            border-radius:0 0 24px 24px;
                        "
                    ></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Resume PDF Modal -->
    <div x-show="showResumeModal" style="display: none;" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background dimming -->
        <div x-show="showResumeModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="showResumeModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="showResumeModal = false"
                     class="relative transform overflow-hidden bg-[#FAF7E6] border border-black text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl h-[85vh] flex flex-col">
                    
                    <!-- Header with close button -->
                    <div class="flex justify-between items-center border-b border-black px-6 py-4 bg-white">
                        <h3 class="text-sm font-bold uppercase tracking-widest font-sans text-black">Resume / CV</h3>
                        <button @click="showResumeModal = false" class="text-black hover:text-[#ff6b00] transition-colors focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- PDF Viewer -->
                    <div class="flex-grow w-full h-full relative">
                        @php
                            $profile = \App\Models\Profile::first();
                            $cvUrl = $profile && $profile->cv_path ? asset('storage/' . $profile->cv_path) : asset('resume.pdf');
                        @endphp
                        <iframe src="{{ $cvUrl }}" class="w-full h-full border-none" title="Resume PDF"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
