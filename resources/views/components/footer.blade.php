<footer class="bg-[#FAF7E6] border-t-4 border-black py-16 text-black relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
        
        <!-- Left Side -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left">
            <span class="text-xl font-black tracking-tight text-[#ff6b00] uppercase border-2 border-black px-2 py-0.5 bg-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] mb-3">
                IX-MEDIA
            </span>
            <p class="text-xs font-mono text-slate-600 font-medium">Crafting unique digital products, one pixel at a time.</p>
            <p class="text-xs text-slate-500 mt-2">&copy; {{ date('Y') }} Alex Morgan. All rights reserved.</p>
        </div>

        <!-- Right Side: Links & Portal -->
        <div class="flex flex-wrap items-center justify-center gap-6">
            <a href="https://github.com" target="_blank" class="text-sm font-bold uppercase tracking-wider text-black hover:underline decoration-yellow-400 decoration-2">GitHub</a>
            <a href="https://linkedin.com" target="_blank" class="text-sm font-bold uppercase tracking-wider text-black hover:underline decoration-yellow-400 decoration-2">LinkedIn</a>
            <a href="https://twitter.com" target="_blank" class="text-sm font-bold uppercase tracking-wider text-black hover:underline decoration-yellow-400 decoration-2">Twitter</a>
            
            <span class="text-slate-300 hidden md:inline">|</span>
            
            <a href="{{ route('admin.login') }}" class="text-xs font-bold uppercase tracking-widest px-4 py-2 bg-white border-2 border-black text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[1.5px] hover:translate-y-[1.5px] transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                CMS Login
            </a>
        </div>
        
    </div>
</footer>
