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
                    <span class="text-3xl text-[#ff6b00] hover:text-white transition-colors duration-300 font-logo uppercase leading-none">IX-MEDIA</span>
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
        <div class="w-full relative" x-data="{ activeFilter: 'all', comingSoonModal: false, modalVideoSrc: '', modalImageSrc: '', modalTitle: '', modalMedium: '', modalYear: '', dimming: false, mobileFocusId: null, isMobile: ('ontouchstart' in window || navigator.maxTouchPoints > 0) }"
             @click.outside="if(isMobile) { mobileFocusId = null; dimming = false; }"
             @mobile-focus-reset.window="if ($event.detail.id !== mobileFocusId) { mobileFocusId = null; dimming = false; }">

            <div class="max-w-[1400px] mx-auto px-6">

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
            </div>

            {{-- Pinterest masonry — columns layout, natively sized by images --}}
            <div class="w-full columns-2 md:columns-3 lg:columns-4 gap-0">

                @forelse($visualProjects as $index => $proj)
                    @php
                        $hasBodyContent = $proj->hasBodyContent();
                        $hasAdminLink = !empty($proj->full_video_url) || !empty($proj->embed_url) || !empty($proj->video_url);
                        $adminLinkUrl = $proj->full_video_url ?: $proj->embed_url ?: $proj->video_url;
                        $isVideoProject = $proj->main_media_type === 'video' || !empty($proj->main_video_path) || $hasAdminLink;
                        
                        $localVideo = $proj->main_video_path ? (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path))) : ($proj->thumbnail_video_path ? (Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : ((Str::startsWith($proj->thumbnail_video_path, 'images/') || Str::startsWith($proj->thumbnail_video_path, 'videos/')) ? asset($proj->thumbnail_video_path) : Storage::url($proj->thumbnail_video_path))) : '');
                        
                        $localImage = '';
                        if ($proj->main_image_path) {
                            $localImage = Str::startsWith($proj->main_image_path, 'http') ? $proj->main_image_path : ((Str::startsWith($proj->main_image_path, 'images/') || Str::startsWith($proj->main_image_path, 'videos/')) ? asset($proj->main_image_path) : Storage::url($proj->main_image_path));
                        } elseif ($proj->thumbnail_path) {
                            $localImage = Str::startsWith($proj->thumbnail_path, 'http') ? $proj->thumbnail_path : ((Str::startsWith($proj->thumbnail_path, 'images/') || Str::startsWith($proj->thumbnail_path, 'videos/')) ? asset($proj->thumbnail_path) : Storage::url($proj->thumbnail_path));
                        } elseif (!empty($proj->thumbnail_images)) {
                            $localImage = Str::startsWith($proj->thumbnail_images[0], 'http') ? $proj->thumbnail_images[0] : ((Str::startsWith($proj->thumbnail_images[0], 'images/') || Str::startsWith($proj->thumbnail_images[0], 'videos/')) ? asset($proj->thumbnail_images[0]) : Storage::url($proj->thumbnail_images[0]));
                        } elseif (!empty($proj->main_images)) {
                            $localImage = Str::startsWith($proj->main_images[0], 'http') ? $proj->main_images[0] : ((Str::startsWith($proj->main_images[0], 'images/') || Str::startsWith($proj->main_images[0], 'videos/')) ? asset($proj->main_images[0]) : Storage::url($proj->main_images[0]));
                        }
                        
                        $isFallback = !$hasBodyContent;

                        if ($isFallback) {
                            if ($hasAdminLink) {
                                $cardHref = $adminLinkUrl;
                                $cardTarget = '_blank';
                                $onClick = '';
                            } else {
                                $cardHref = '#';
                                $cardTarget = '_self';
                                $onClick = "\$event.preventDefault(); comingSoonModal = true; modalVideoSrc = '{$localVideo}'; modalImageSrc = '{$localImage}'; modalTitle = '".addslashes($proj->title)."'; modalMedium = '".addslashes($proj->medium)."'; modalYear = '".addslashes($proj->year)."';";
                            }
                        } else {
                            $cardHref = route('portfolio.project.show', $proj->slug);
                            $cardTarget = '_self';
                            $onClick = '';
                        }
                    @endphp
                    {{-- By removing padding-top and absolute positioning, the img tag naturally defines the box height, perfect for masonry! --}}
                    <a href="{{ $cardHref }}"
                       target="{{ $cardTarget }}"
                       {!! $onClick ? 'x-on:click="'.$onClick.'"' : '' !!}
                       @if($isFallback && $hasAdminLink) rel="noopener noreferrer" @endif
                       x-data="{ isDimmed: false, vidLoaded: false, isHovered: false, itemTimer: null, itemId: 'proj-{{$proj->id}}', intersecting: false }"
                       x-show="activeFilter === 'all' || activeFilter === '{{ $proj->medium }}'"
                       @mouseenter="if(!isMobile) { itemTimer = setTimeout(() => { isHovered = true; dimming = true; }, 1500); $el.style.transform='scale(1.018)'; }"
                       @mouseleave="if(!isMobile) { clearTimeout(itemTimer); isHovered = false; dimming = false; $el.style.transform='scale(1)'; }"
                       @click="if(isMobile && mobileFocusId !== itemId) { $event.preventDefault(); mobileFocusId = itemId; dimming = true; $el.style.transform='scale(1.018)'; window.dispatchEvent(new CustomEvent('mobile-focus-reset', { detail: { id: itemId } })); } else if(isMobile) { $el.style.transform='scale(1)'; }"
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
                                        $vidSrc = Str::startsWith($proj->thumbnail_video_path, 'http') ? $proj->thumbnail_video_path : ((Str::startsWith($proj->thumbnail_video_path, 'images/') || Str::startsWith($proj->thumbnail_video_path, 'videos/')) ? asset($proj->thumbnail_video_path) : Storage::url($proj->thumbnail_video_path));
                                    } elseif ($proj->main_media_type === 'video') {
                                        $vidSrc = $proj->main_video_path ? (Str::startsWith($proj->main_video_path, 'http') ? $proj->main_video_path : ((Str::startsWith($proj->main_video_path, 'images/') || Str::startsWith($proj->main_video_path, 'videos/')) ? asset($proj->main_video_path) : Storage::url($proj->main_video_path))) : $proj->video_url;
                                    }
                                    
                                    if (empty($localImage) && $vidSrc && Str::contains($vidSrc, 'res.cloudinary.com')) {
                                        $autoJpg = preg_replace('/\.[a-zA-Z0-9]+$/i', '.jpg', $vidSrc);
                                        $localImage = str_replace('/upload/', '/upload/so_2/', $autoJpg);
                                    }
                                @endphp

                                <div x-show="intersecting && !vidLoaded" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                                    <svg class="w-6 h-6 text-black/40 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                <video src="{{ $vidSrc }}"
                                       @if($localImage) poster="{{ $localImage }}" @endif
                                       @loadeddata="vidLoaded = true"
                                       @canplay="vidLoaded = true"
                                       muted playsinline loop preload="metadata"
                                       x-effect="if ((!isMobile && isHovered) || (isMobile && mobileFocusId === itemId)) { $el.play().catch(()=>{}) } else { $el.pause(); }"
                                       class="w-full h-auto block pointer-events-none transition-all duration-700"
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
                            @elseif(!empty($proj->thumbnail_images) || !empty($proj->main_images))
                                @php
                                    $slideImages = !empty($proj->thumbnail_images) ? $proj->thumbnail_images : $proj->main_images;
                                @endphp
                                <div x-data="{ currentSlide: 0, total: {{ count($slideImages) }} }"
                                     class="relative w-full overflow-hidden">
                                     <img src="{{ Str::startsWith($slideImages[0], 'http') ? $slideImages[0] : ((Str::startsWith($slideImages[0], 'images/') || Str::startsWith($slideImages[0], 'videos/')) ? asset($slideImages[0]) : Storage::url($slideImages[0])) }}"
                                          class="w-full h-auto block object-cover invisible" loading="lazy">
                                     @foreach($slideImages as $index => $img)
                                         <img src="{{ Str::startsWith($img, 'http') ? $img : ((Str::startsWith($img, 'images/') || Str::startsWith($img, 'videos/')) ? asset($img) : Storage::url($img)) }}"
                                              x-show="currentSlide === {{ $index }}"
                                              x-transition.opacity.duration.700ms
                                              loading="lazy"
                                              class="absolute inset-0 w-full h-full block object-cover">
                                     @endforeach
                                     <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-20">
                                         <template x-for="i in total" :key="i">
                                             <div class="w-1.5 h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                                  :class="(i - 1) === currentSlide ? 'bg-white scale-125' : 'bg-white/40'"></div>
                                         </template>
                                     </div>
                                 </div>
                            @elseif($proj->thumbnail_path || $proj->main_image_path)
                                @php
                                    $singleImage = $proj->thumbnail_path ?: $proj->main_image_path;
                                @endphp
                                <img src="{{ Str::startsWith($singleImage, 'http') ? $singleImage : ((Str::startsWith($singleImage, 'images/') || Str::startsWith($singleImage, 'videos/')) ? asset($singleImage) : Storage::url($singleImage)) }}"
                                     alt="{{ $proj->title }}"
                                     class="w-full h-auto block object-cover" loading="lazy">
                            @else
                                <div class="w-full" style="padding-top: 100%;">
                                    <div class="absolute inset-0 bg-neutral-900 flex items-center justify-center">
                                        <span class="text-neutral-700 font-mono text-xs uppercase tracking-widest">No Media</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Play button for motion/video types --}}
                            @if(in_array($proj->medium, $playTypes) || $proj->thumbnail_type === 'video')
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                                    <div class="w-11 h-11 rounded-full bg-black/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
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

                            @if($isFallback)
                                <div class="absolute bottom-4 right-4 z-30 flex flex-col items-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                    <span class="font-logo text-[12px] md:text-sm text-white/80 uppercase tracking-widest">Story Coming soon...</span>
                                    <div class="inline-flex items-center gap-2 px-4 py-2 border border-white bg-[#6829AA] text-white font-logo text-[11px] md:text-xs uppercase tracking-widest transition-transform hover:scale-105 shadow-lg">
                                        {{ $isVideoProject ? 'See full video' : 'See full image' }}
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
@endsection
