<header x-data="{ mobileMenuOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-[#FAF7E6]/95 backdrop-blur-md py-4': scrolled, 'bg-transparent py-6': !scrolled }"
        class="fixed top-0 left-0 right-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('portfolio.index') }}" class="group flex items-center">
            <span class="text-2xl font-black tracking-tight text-[#ff6b00] hover:text-black transition-colors duration-300 font-display uppercase">
                IX-MEDIA
            </span>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-10">
            <a href="#self-intro" class="text-sm font-bold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Services</a>
            <a href="#projects" class="text-sm font-bold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Outputs</a>
            <a href="#contact" class="text-sm font-bold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Collaborate</a>
        </nav>

        <!-- CTA / Resume link -->
        <div class="hidden md:flex items-center gap-4">
            <a href="#" @click.prevent="showResumeModal = true"
               class="text-xs font-bold uppercase tracking-wider px-6 py-2 bg-transparent hover:bg-black hover:text-[#FAF7E6] text-black border border-black rounded-none transition-colors duration-300">
                See Resume
            </a>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                class="md:hidden text-black focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Drawer Overlay Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.away="mobileMenuOpen = false"
         class="md:hidden absolute top-full left-0 right-0 bg-[#FAF7E6] border-b border-black/10 px-6 py-8 flex flex-col gap-6 shadow-xl z-50">
        <a href="#self-intro" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Services</a>
        <a href="#projects" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Outputs</a>
        <a href="#contact" @click="mobileMenuOpen = false" class="text-base font-extrabold uppercase tracking-wider text-black hover:text-[#ff6b00] transition-colors duration-300">Collaborate</a>
        <hr class="border-black/10">
        <a href="#" @click.prevent="showResumeModal = true; mobileMenuOpen = false"
           class="text-center font-bold uppercase tracking-wider px-4 py-2.5 bg-transparent hover:bg-black hover:text-[#FAF7E6] text-black border border-black transition-colors duration-300">
            See Resume
        </a>
    </div>
</header>
