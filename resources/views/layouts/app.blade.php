<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
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
            animation: marquee 20s linear infinite;
            min-width: max-content;
        }
        
        /* Hide scrollbar for carousel */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body x-data="{ showResumeModal: false }" class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white min-h-screen flex flex-col overflow-x-hidden">

    <!-- Glowing Background blobs for modern premium aesthetic -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-cyan-900/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-900/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    <!-- Header / Navbar -->
    <x-navbar />

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Toast Notification for contact submission states -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-6 right-6 bg-slate-900 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 backdrop-blur-xl z-50">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <p class="font-medium text-slate-100">Success!</p>
                <p class="text-sm text-slate-400">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-200 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
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
