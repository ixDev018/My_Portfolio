<header x-data="{ mobileMenuOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-black/75 backdrop-blur-md py-4 border-b border-white/10 shadow-lg': scrolled, 'bg-transparent py-6': !scrolled }"
        class="fixed top-0 left-0 right-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('portfolio.index') }}" class="group flex items-center">
            <span class="text-2xl font-black tracking-tight text-[#ff6b00] hover:text-white transition-colors duration-300 font-display uppercase">
                IX-MEDIA
            </span>
        </a>

        <!-- Desktop Navigation Links (Hidden on Project Show) -->
        @if(!request()->routeIs('portfolio.project.show'))
        <nav class="hidden md:flex items-center gap-10">
            <a href="{{ route('portfolio.index') }}#self-intro" :class="scrolled ? 'text-white' : 'text-black'" class="text-sm font-bold uppercase tracking-wider hover:text-[#ff6b00] transition-colors duration-300">Services</a>
            <a href="{{ route('portfolio.index') }}#projects" :class="scrolled ? 'text-white' : 'text-black'" class="text-sm font-bold uppercase tracking-wider hover:text-[#ff6b00] transition-colors duration-300">Outputs</a>
            <a href="{{ route('portfolio.index') }}#contact" :class="scrolled ? 'text-white' : 'text-black'" class="text-sm font-bold uppercase tracking-wider hover:text-[#ff6b00] transition-colors duration-300">Collaborate</a>
        </nav>

        <!-- CTA / Resume link -->
        <div class="hidden md:flex items-center gap-4">
            <a href="#" @click.prevent="showResumeModal = true"
               :class="scrolled ? 'text-white border-white hover:bg-white hover:text-black' : 'text-black border-black hover:bg-black hover:text-[#FAF7E6]'"
               class="text-xs font-bold uppercase tracking-wider px-6 py-2 bg-transparent border rounded-none transition-colors duration-300">
                See Resume
            </a>
        </div>
        @endif

        <!-- Mobile Menu Toggle Button -->
        @if(!request()->routeIs('portfolio.project.show'))
        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                :class="scrolled ? 'text-white' : 'text-black'"
                class="md:hidden focus:outline-none transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        @endif
    </div>

    <!-- Mobile Drawer Overlay Menu -->
    @if(!request()->routeIs('portfolio.project.show'))
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.away="mobileMenuOpen = false"
         class="md:hidden absolute top-full left-0 right-0 bg-[#FAF7E6] border-b border-black/10 px-6 py-8 flex flex-col gap-6 shadow-xl z-50">
        <a href="{{ route('portfolio.index') }}#self-intro" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Services</a>
        <a href="{{ route('portfolio.index') }}#projects" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Outputs</a>
        <a href="{{ route('portfolio.index') }}#contact" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Collaborate</a>
        
        <div class="pt-4 mt-2 border-t border-black/10">
            <a href="#" @click.prevent="showResumeModal = true; mobileMenuOpen = false" class="inline-block text-sm font-bold uppercase tracking-wider px-6 py-3 bg-black text-[#FAF7E6] rounded-none">
                See Resume
            </a>
        </div>
    </div>
    @endif
</header>
