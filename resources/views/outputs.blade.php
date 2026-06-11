@extends('layouts.app')

@section('content')
    <style>
        /* Hide the global navbar on this specific page to match the neo-brutalist design */
        header, nav { display: none !important; }

        .grid-bg-section {
            background-image:
                linear-gradient(rgba(104,41,170,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(104,41,170,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            background-attachment: fixed;
        }
    </style>

    <!-- ── STICKY NAV WRAPPER ── -->
    <div class="sticky top-0 z-[100] w-full flex flex-col">
        <!-- Custom Black Header -->
        <div class="bg-black w-full py-5 px-6 shadow-md border-b border-black/10">
            <div class="max-w-[1400px] mx-auto flex items-center">
                <a href="{{ route('portfolio.index') }}" class="inline-block transition-transform hover:scale-105 active:scale-95" title="Back to Home">
                    <span class="text-[#ff6b00] font-display text-[24px] tracking-widest uppercase font-black leading-none mt-1">IX-MEDIA</span>
                </a>
            </div>
        </div>

        {{-- STICKY BACK NAVIGATION --}}
        <div class="w-full bg-[#fdfaf0]/95 backdrop-blur-md border-b border-black/5 py-2.5 px-6 transition-all">
            <div class="max-w-[1400px] mx-auto flex items-center">
                <a href="{{ route('portfolio.index') }}#outputs"
                   class="inline-flex items-center gap-2.5 font-sans text-[12px] text-[#ff6b00] hover:text-[#e66000] transition-colors duration-300">
                    <div class="w-7 h-7 rounded-full border border-current flex items-center justify-center bg-[#fdfaf0]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    <span class="font-bold uppercase tracking-widest mt-0.5">Back</span>
                </a>
            </div>
        </div>
    </div>

    <section class="pt-6 pb-20 bg-[#fdfaf0] grid-bg-section min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6 relative" x-data="{ activeFilter: 'all' }">

            <div class="mb-10 text-center">
                <h1 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tighter text-black">All Outputs</h1>
                <p class="text-[13px] md:text-[14px] font-mono text-black/40 mt-3 font-semibold">Product, UI, Graphic Arts, Motion &amp; Video</p>
            </div>

            @php
                $mediums = $visualProjects->pluck('medium')->filter()->unique()->values();
                $playTypes = ['Motion', 'Video', 'Video Edit', 'Animation', 'Motion Design'];
            @endphp

            <!-- Filter pills -->
            <div class="flex items-center justify-center gap-2 flex-wrap mb-10">
                <button @click="activeFilter = 'all'"
                        :class="activeFilter === 'all' ? 'bg-black text-white border-black' : 'bg-white text-black/40 border-black/10 hover:text-black hover:border-black/30'"
                        class="px-5 py-1.5 rounded-full border font-mono text-[10px] md:text-[11px] font-bold uppercase tracking-widest transition-all duration-200">
                    All
                </button>
                @foreach($mediums as $med)
                    <button @click="activeFilter = '{{ $med }}'"
                            :class="activeFilter === '{{ $med }}' ? 'bg-black text-white border-black' : 'bg-white text-black/40 border-black/10 hover:text-black hover:border-black/30'"
                            class="px-5 py-1.5 rounded-full border font-mono text-[10px] md:text-[11px] font-bold uppercase tracking-widest transition-all duration-200">
                        {{ $med }}
                    </button>
                @endforeach
            </div>

            {{-- Pinterest masonry — columns layout, natively sized by images --}}
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
                            @if(in_array($proj->medium, $playTypes) || $proj->thumbnail_type === 'video')
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

                            {{-- Year & Top badges top-left --}}
                            <div class="absolute top-3 left-3 z-20 flex gap-1.5">
                                @if($proj->is_top)
                                    <span class="px-2 py-0.5 rounded-full bg-[#0A8C5E] font-mono text-[8px] font-bold uppercase tracking-widest text-white shadow-sm">
                                        Top
                                    </span>
                                @endif
                                @if($proj->year)
                                    <span class="px-2 py-0.5 rounded-full bg-black/40 backdrop-blur-sm font-mono text-[8px] text-white/80">
                                        {{ $proj->year }}
                                    </span>
                                @endif
                            </div>
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
