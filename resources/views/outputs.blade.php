@extends('layouts.app')

@section('content')
    <section class="pt-32 pb-20 bg-[#FAF7E6] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6 relative" x-data="{ activeFilter: 'all' }">
            
            {{-- FLOATING BACK NAVIGATION --}}
            <a href="{{ route('portfolio.index') }}#outputs"
               class="absolute top-0 left-6 z-50 flex items-center gap-3 font-sans text-[13px] text-[#ff6b00] hover:text-black transition-colors duration-300">
                <div class="w-8 h-8 rounded-full border border-current flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="font-bold uppercase tracking-widest mt-0.5">Back</span>
            </a>

            <div class="mt-16 mb-12 text-center">
                <h1 class="font-display text-4xl uppercase tracking-tighter text-black">All Outputs</h1>
                <p class="text-sm font-mono text-black/40 mt-2">Product, UI, Graphic Arts, Motion &amp; Video</p>
            </div>

            @php
                $mediums = $visualProjects->pluck('medium')->filter()->unique()->values();
                $playTypes = ['Motion', 'Video', 'Video Edit', 'Animation', 'Motion Design'];
            @endphp

            <!-- Filter pills -->
            <div class="flex items-center justify-center gap-2 flex-wrap mb-10">
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

            {{-- Pinterest masonry — columns layout, natively sized by images --}}
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

        </div>
    </section>
@endsection
