@extends('layouts.app')

@section('title', $project->title . ' | ' . ($project->medium ?? 'Output'))

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
        <div class="w-full bg-[#FAF7E6]/95 backdrop-blur-md border-b border-black/5 py-2.5 px-6 transition-all">
            <div class="max-w-[1400px] mx-auto flex items-center">
                @php
                    $backUrl = url()->previous();
                    if ($backUrl === url()->current()) {
                        $backUrl = route('portfolio.index');
                    }
                @endphp
                <a href="{{ $backUrl }}"
                   class="inline-flex items-center gap-2.5 font-sans text-[12px] text-[#ff6b00] hover:text-[#e66000] transition-colors duration-300">
                    <div class="w-7 h-7 rounded-full border border-current flex items-center justify-center bg-[#fdfaf0]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    <span class="font-bold uppercase tracking-widest mt-0.5">Back</span>
                </a>
            </div>
        </div>
    </div>

{{-- ═══════════════════════════════════════════════════════════
     PROJECT OUTPUT — SPA-STYLE DETAIL PAGE
     Layout: sticky marquee bg → playback hero → CMS body
═══════════════════════════════════════════════════════════ --}}
<div class="bg-[#fdfaf0] grid-bg-section text-black min-h-screen" style="font-family: 'Bitcount Single', monospace;">

    {{-- ── HERO BLOCK ── --}}
    <div class="relative w-full pt-16 pb-6">
        
        {{-- Container for Back Button & Title --}}
        <div class="max-w-4xl mx-auto px-6 w-full relative z-20">
            
            {{-- Project label + title --}}
            <div class="text-center">
                @if($project->medium)
                    <span class="inline-block px-3 py-1 rounded-full border border-black/20 font-mono text-[10px] uppercase tracking-widest text-black/50 mb-2">
                        {{ $project->medium }}
                    </span>
                @endif
                <h1 class="font-display text-2xl sm:text-4xl lg:text-5xl uppercase tracking-widest text-black leading-none">
                    {{ $project->title }}
                </h1>
                @if($project->subtitle)
                    <p class="mt-2 font-mono text-xs text-black/45 tracking-widest">{{ $project->subtitle }}</p>
                @endif
            </div>
        </div>

        {{-- Marquee & Playback Container (Marquee is perfectly centered behind the playback card) --}}
        <div class="relative w-full flex items-center justify-center mt-8">
            
            {{-- Marquee Background --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none select-none flex flex-col justify-center z-0">
                <div class="flex whitespace-nowrap"
                     style="animation: marquee-left 18s linear infinite;">
                    @php $marqueeText = strtoupper($project->title) . ' &nbsp;&nbsp;&nbsp;◆&nbsp;&nbsp;&nbsp; '; @endphp
                    @for ($i = 0; $i < 6; $i++)
                        <span class="font-poppins font-semibold text-[#512b81]/12 leading-none shrink-0"
                              style="font-size: clamp(80px, 14vw, 240px);">{!! $marqueeText !!}</span>
                    @endfor
                </div>
                {{-- Fade edges --}}
                <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#fdfaf0] to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#fdfaf0] to-transparent"></div>
            </div>

    {{-- ── PLAYBACK DISPLAY CARD ── --}}
            <div class="relative z-10 w-full max-w-4xl px-6">
                @php
                    // ── EMBED URL: Primary player priority ──
                    $embedUrl   = $project->embed_url ?? null;
                    $iframeUrl  = null;

                    if ($embedUrl) {
                        // YouTube: youtu.be/ID or youtube.com/watch?v=ID or /embed/ID
                        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_\-]{11})/', $embedUrl, $ytM)) {
                            $iframeUrl = 'https://www.youtube-nocookie.com/embed/' . $ytM[1] . '?autoplay=0&rel=0';
                        }
                        // Vimeo
                        elseif (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $embedUrl, $vmM)) {
                            $iframeUrl = 'https://player.vimeo.com/video/' . $vmM[1];
                        }
                        // Otherwise treat as direct video or iframe src as-is
                    }
                @endphp

                @if($embedUrl)
                    {{-- ── EMBEDDED PLAYER (primary) ── --}}
                    <div class="w-full aspect-video rounded-md overflow-hidden bg-black border border-black/10 shadow-sm relative">
                        {{-- Marquee title loop behind the embedded video --}}
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none flex flex-col justify-center z-[1]">
                            <div class="flex whitespace-nowrap" style="animation: marquee-left 14s linear infinite;">
                                @for ($i = 0; $i < 8; $i++)
                                    <span class="font-poppins font-black text-white/10 leading-none shrink-0"
                                          style="font-size: clamp(40px, 8vw, 120px);">{{ strtoupper($project->title) }}&nbsp;&nbsp;◆&nbsp;&nbsp;</span>
                                @endfor
                            </div>
                        </div>
                        @if($iframeUrl)
                            <iframe src="{{ $iframeUrl }}"
                                    class="absolute inset-0 w-full h-full border-none z-[2]"
                                    allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    title="{{ $project->title }}">
                            </iframe>
                        @else
                            {{-- Direct video/mp4 URL --}}
                            <video src="{{ $embedUrl }}"
                                   autoplay loop muted playsinline controls preload="auto"
                                   class="absolute inset-0 w-full h-full object-contain z-[2]">
                            </video>
                        @endif
                    </div>
                @else
                    {{-- ── FALLBACK: Existing local media display ── --}}
                    <div x-data="{ isDimmed: false, timeoutStarted: false }" class="w-full aspect-video rounded-md overflow-hidden bg-black border border-black/10 shadow-sm relative group flex items-center justify-center">
                    @if($project->main_media_type === 'video' && $project->main_video_path)
                        <video src="{{ asset($project->main_video_path) }}"
                               autoplay loop muted playsinline controls preload="auto"
                               class="w-full h-full object-contain"
                               @play="if (!timeoutStarted) { timeoutStarted = true; setTimeout(() => { $el.pause(); isDimmed = true; }, 15000); }">
                        </video>
                    @elseif($project->thumbnail_video_path)
                        <video src="{{ asset($project->thumbnail_video_path) }}"
                               autoplay loop muted playsinline controls preload="auto"
                               class="w-full h-full object-contain"
                               @play="if (!timeoutStarted) { timeoutStarted = true; setTimeout(() => { $el.pause(); isDimmed = true; }, 15000); }">
                        </video>
                    @elseif($project->video_url)
                        <video src="{{ $project->video_url }}"
                               autoplay loop muted playsinline controls preload="auto"
                               class="w-full h-full object-contain"
                               @play="if (!timeoutStarted) { timeoutStarted = true; setTimeout(() => { $el.pause(); isDimmed = true; }, 15000); }">
                        </video>
                    @elseif(!empty($project->main_images) || !empty($project->thumbnail_images))
                        @php $images = !empty($project->main_images) ? $project->main_images : $project->thumbnail_images; @endphp
                        <div x-data="{ currentSlide: 0, total: {{ count($images) }} }"
                             x-init="setInterval(() => { currentSlide = (currentSlide + 1) % total }, 3500)"
                             class="relative w-full h-full overflow-hidden">
                            @foreach($images as $index => $img)
                                <img src="{{ asset($img) }}"
                                     x-show="currentSlide === {{ $index }}"
                                     x-transition.opacity.duration.700ms
                                     class="absolute inset-0 w-full h-full object-contain">
                            @endforeach
                            <!-- Dots indicator -->
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20">
                                <template x-for="i in total" :key="i">
                                    <div class="w-2 h-2 rounded-full transition-all duration-300 shadow-sm cursor-pointer"
                                         :class="(i - 1) === currentSlide ? 'bg-white scale-125' : 'bg-white/40'"
                                         @click="currentSlide = i - 1"></div>
                                </template>
                            </div>
                        </div>
                    @elseif($project->main_image_path)
                        <img src="{{ Str::startsWith($project->main_image_path, 'http') ? $project->main_image_path : asset($project->main_image_path) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-full object-contain">
                    @elseif($project->thumbnail_path)
                        <img src="{{ Str::startsWith($project->thumbnail_path, 'http') ? $project->thumbnail_path : asset($project->thumbnail_path) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-full object-contain">
                    @else
                        {{-- Placeholder display --}}
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 via-white to-slate-50 flex flex-col items-center justify-center relative">
                            <div class="absolute inset-0 opacity-40"
                                 style="background-image: radial-gradient(circle, #512b81/8 1px, transparent 1px); background-size: 28px 28px;"></div>
                            <div class="relative z-10 text-center">
                                <div class="w-14 h-14 mx-auto rounded-xl bg-[#512b81]/10 flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7 text-[#512b81]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="font-mono text-[10px] uppercase tracking-widest text-black/25">Playback / Display</p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($project->full_video_url))
                        <!-- Dimming Overlay -->
                        <div x-show="isDimmed" style="display: none;"
                             x-transition.opacity.duration.1000ms
                             class="absolute inset-0 bg-black/70 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center p-4">
                            <div class="text-white font-mono text-[10px] tracking-widest uppercase mb-3 opacity-80">Preview Ended</div>
                            <a href="{{ $project->full_video_url }}" target="_blank"
                               class="px-5 py-2.5 bg-white text-black font-semibold rounded-full text-sm shadow-xl hover:bg-gray-100 transition-colors">
                                Watch Full Video
                            </a>
                        </div>
                    @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="relative w-full max-w-4xl mx-auto px-6 z-10">
            @if($embedUrl && isset($iframeUrl) && str_contains($iframeUrl, 'youtube-nocookie.com'))
                <div class="mt-3 text-center text-xs font-poppins text-black/50 px-4">
                    <svg class="inline-block w-3.5 h-3.5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Due to copyright restrictions, some videos cannot be played here. 
                    <a href="https://www.youtube.com/watch?v={{ $ytM[1] ?? '' }}" target="_blank" class="underline hover:text-black transition-colors font-semibold">Watch directly on YouTube</a>.
                </div>
            @endif

            {{-- ── META ROW — source, date, tags ── --}}
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-1">
                    <div class="flex items-center gap-4">
                        @if($project->client)
                            <div>
                                <p class="font-mono text-[9px] uppercase tracking-widest text-black/35 mb-0.5">Source</p>
                                <p class="font-mono text-[11px] text-black/70">{{ $project->client }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="text-left sm:text-right">
                        @if($project->date_published || $project->year)
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/35 mb-0.5">Date Published</p>
                            <p class="font-mono text-[11px] text-black/70">{{ $project->date_published ?? $project->year }}</p>
                        @endif
                    </div>
                </div>

                {{-- Tags --}}
                @if($project->tags)
                    <div class="mt-4 flex flex-wrap gap-2 px-1">
                        @foreach(array_filter(array_map('trim', explode(',', $project->tags))) as $tag)
                            <span class="px-3 py-1 rounded-full border border-black/20 font-mono text-[9px] uppercase tracking-widest text-black/55">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    {{-- ══ CMS CONTENT BODY ══════════════════════════════════════
         Everything below the playback card — the "more story" area
    ═══════════════════════════════════════════════════════════ --}}
    <div class="relative z-10 max-w-3xl mx-auto px-6 pt-4 pb-24" x-data="{ lightboxOpen: false, lightboxSrc: '' }">

        {{-- Divider --}}
        <div class="flex items-center gap-4 mb-10">
            <div class="flex-1 h-px bg-black/10"></div>
            <span class="font-mono text-[9px] uppercase tracking-widest text-black/30">The Story</span>
            <div class="flex-1 h-px bg-black/10"></div>
        </div>

        {{-- Overview short description removed per user request --}}

        {{-- Content Body Render --}}
        @if($project->body_content)
            <div class="space-y-2 mb-16 text-black/80" 
                 @click="if($event.target.tagName === 'IMG') { lightboxOpen = true; lightboxSrc = $event.target.src; }">
                
                @php
                    $content = trim($project->body_content);
                    $isJsonBlocks = Str::startsWith($content, '[');
                    $isLegacyJson = Str::startsWith($content, '{');
                    $isHtml = preg_match('/<[a-z][\s\S]*>/i', $content);
                @endphp

                @if($isJsonBlocks)
                    @php
                        $blocks = json_decode($content, true);
                        $inBulletList = false;
                        $inNumberedList = false;
                    @endphp
                    @if(is_array($blocks))
                        @foreach($blocks as $index => $block)
                            @php
                                $type = $block['type'] ?? '';
                                $nextType = $blocks[$index + 1]['type'] ?? '';
                            @endphp
                            
                            @switch($type)
                                @case('heading2')
                                    <h2 class="mt-12 mb-6 font-display text-2xl font-bold text-black tracking-wide" style="font-family: 'Bitcount Single', monospace;">{!! $block['content'] ?? '' !!}</h2>
                                    @break
                                @case('heading3')
                                    <h3 class="mt-8 mb-4 font-display text-xl font-bold text-black tracking-wide" style="font-family: 'Bitcount Single', monospace;">{!! $block['content'] ?? '' !!}</h3>
                                    @break
                                @case('paragraph')
                                    <p class="font-poppins text-sm sm:text-base leading-[1.85] mb-6">{!! $block['content'] ?? '' !!}</p>
                                    @break
                                @case('bullet')
                                    @if(!$inBulletList) <ul class="list-disc pl-6 mb-6 space-y-2 font-poppins"> @php $inBulletList = true; @endphp @endif
                                    <li class="text-sm sm:text-base leading-[1.85]">{!! $block['content'] ?? '' !!}</li>
                                    @if($nextType !== 'bullet') </ul> @php $inBulletList = false; @endphp @endif
                                    @break
                                @case('numbered')
                                    @if(!$inNumberedList) <ol class="list-decimal pl-6 mb-6 space-y-2 font-poppins"> @php $inNumberedList = true; @endphp @endif
                                    <li class="text-sm sm:text-base leading-[1.85]">{!! $block['content'] ?? '' !!}</li>
                                    @if($nextType !== 'numbered') </ol> @php $inNumberedList = false; @endphp @endif
                                    @break
                                @case('quote')
                                    <blockquote class="font-poppins border-l-[3px] border-[#512b81] pl-6 my-8 py-2 italic text-black/60 font-medium text-lg">
                                        {!! $block['content'] ?? '' !!}
                                    </blockquote>
                                    @break
                                @case('code')
                                    <pre class="bg-[#FAF7E6] border border-black/10 rounded-xl p-5 my-6 overflow-x-auto shadow-inner"><code class="font-mono text-xs sm:text-sm text-[#512b81]">{!! $block['content'] ?? '' !!}</code></pre>
                                    @break
                                @case('image')
                                    @if(!empty($block['src']))
                                        @php
                                            $ratio = $block['ratio'] ?? 'auto';
                                            $posX = $block['posX'] ?? 50;
                                            $posY = $block['posY'] ?? 50;
                                            $imgClass = "w-full rounded-xl shadow-md border border-black/5 cursor-zoom-in hover:scale-[1.01] transition-transform duration-300";
                                            $imgStyle = "";
                                            if ($ratio === '16:9') {
                                                $imgStyle = "aspect-ratio: 16/9; object-fit: cover; object-position: {$posX}% {$posY}%;";
                                            } elseif ($ratio === '3:4') {
                                                $imgStyle = "aspect-ratio: 3/4; object-fit: cover; object-position: {$posX}% {$posY}%;";
                                            }
                                        @endphp
                                        <figure class="my-10">
                                            <img src="{{ $block['src'] }}" alt="{{ $block['caption'] ?? 'Project image' }}" class="{{ $imgClass }}" style="{{ $imgStyle }}">
                                            @if(!empty($block['caption']))
                                                <figcaption class="text-center mt-4 font-mono text-[10px] uppercase tracking-widest text-black/40">{{ $block['caption'] }}</figcaption>
                                            @endif
                                        </figure>
                                    @endif
                                    @break
                                @case('video')
                                    @if(!empty($block['src']))
                                        @php
                                            $ratio = $block['ratio'] ?? '16:9';
                                            $vidStyle = ($ratio === '3:4') ? "aspect-ratio: 3/4;" : "aspect-ratio: 16/9;";
                                            $videoSrc = $block['src'];
                                            $isYouTube = preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $videoSrc, $ytMatch);
                                            $isVimeo = !$isYouTube && preg_match('/vimeo\.com\/(\d+)/', $videoSrc, $vmMatch);
                                            $isEmbed = $isYouTube || $isVimeo;
                                        @endphp
                                        <div class="relative w-full rounded-xl overflow-hidden my-10 shadow-md border border-black/5" style="{{ $vidStyle }}">
                                            @if($isEmbed)
                                                @php
                                                    $embedUrl = $isYouTube
                                                        ? 'https://www.youtube-nocookie.com/embed/' . $ytMatch[1] . '?autoplay=0&rel=0'
                                                        : 'https://player.vimeo.com/video/' . $vmMatch[1];
                                                @endphp
                                                <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full border-none" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                            @else
                                                <video src="{{ $videoSrc }}" class="absolute inset-0 w-full h-full object-contain" controls playsinline preload="auto"></video>
                                            @endif
                                        </div>
                                        @if($isYouTube ?? false)
                                            <div class="-mt-7 mb-10 text-center text-[11px] font-poppins text-black/40 px-4">
                                                <svg class="inline-block w-3 h-3 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Due to copyright restrictions, some videos cannot be played here. 
                                                <a href="https://www.youtube.com/watch?v={{ $ytMatch[1] ?? '' }}" target="_blank" class="underline hover:text-black transition-colors font-semibold">Watch directly on YouTube</a>.
                                            </div>
                                        @endif
                                        @if(!empty($block['caption']))
                                            <p class="text-center mt-2 font-mono text-[10px] uppercase tracking-widest text-black/40">{{ $block['caption'] }}</p>
                                        @endif
                                    @endif
                                    @break
                                @case('divider')
                                    <hr class="my-12 border-t border-black/10">
                                    @break
                            @endswitch
                        @endforeach
                    @endif
                @elseif($isHtml)
                    {{-- Legacy TipTap HTML Content --}}
                    <div class="cms-tiptap-content">
                        {!! $project->body_content !!}
                    </div>
                @elseif($isLegacyJson)
                    <p class="text-sm italic text-black/40">Legacy JSON format content block. Please edit and re-save project to render rich HTML.</p>
                @else
                    {{-- Very old plaintext fallback --}}
                    @foreach(array_filter(explode("\n\n", $project->body_content)) as $para)
                        <p class="font-poppins text-sm sm:text-base leading-[1.85] mb-6">{{ trim($para) }}</p>
                    @endforeach
                @endif
            </div>

            <style>
                /* TipTap Markdown Styles (For legacy HTML fallback) */
                .cms-tiptap-content p { margin-bottom: 1.5em; line-height: 1.85; font-size: 15px; }
                .cms-tiptap-content h2 { font-size: 1.5rem; font-weight: 700; font-family: 'Bitcount Single', monospace; margin-top: 2em; margin-bottom: 1em; color: black; }
                .cms-tiptap-content h3 { font-size: 1.25rem; font-weight: 600; font-family: 'Bitcount Single', monospace; margin-top: 1.5em; margin-bottom: 0.75em; color: black; }
                .cms-tiptap-content strong { font-weight: 700; color: black; }
                .cms-tiptap-content blockquote { border-left: 3px solid #512b81; padding-left: 1.5rem; font-style: italic; color: rgba(0,0,0,0.6); margin: 2em 0; }
                .cms-tiptap-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5em; }
                .cms-tiptap-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.5em; }
                .cms-tiptap-content li { margin-bottom: 0.5em; line-height: 1.85; }
                .cms-tiptap-content img { width: 100%; height: auto; border-radius: 0.75rem; margin: 2em 0; cursor: zoom-in; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0,0,0,0.05); transition: transform 0.3s; }
                .cms-tiptap-content img:hover { transform: scale(1.01); }
                .cms-tiptap-content iframe { width: 100%; aspect-ratio: 16/9; border-radius: 0.75rem; margin: 2em 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
            </style>
        @endif

        {{-- Lightbox Overlay (Alpine) --}}
        <div x-show="lightboxOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-md"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-md"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 z-[200] bg-black/90 flex items-center justify-center p-4 sm:p-10"
             style="display: none;">
             
             <!-- Close button -->
             <button @click="lightboxOpen = false" class="absolute top-6 right-6 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-[210]">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
             </button>
             
             <!-- Fullscreen Image -->
             <img :src="lightboxSrc" @click.away="lightboxOpen = false" class="max-w-full max-h-full rounded-xl shadow-2xl object-contain border border-white/10" alt="Fullscreen Media">
        </div>

        {{-- ── SLIDES GALLERY SECTION ── --}}
        @if(!empty($project->gallery_images))
            @php
                $slidesJson = collect($project->gallery_images)->map(function($path) {
                    return asset($path);
                })->toJson();
            @endphp
            <div class="mb-14" x-data="{
                    currentSlide: 0,
                    slides: {{ $slidesJson }},
                    touchStartX: 0,
                    touchEndX: 0,
                    showSwipeHint: true,
                    onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
                    onTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        this.showSwipeHint = false;
                        const diff = this.touchStartX - this.touchEndX;
                        if (Math.abs(diff) > 40) {
                            if (diff > 0) {
                                this.currentSlide = this.currentSlide === this.slides.length - 1 ? 0 : this.currentSlide + 1;
                            } else {
                                this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                            }
                        }
                    }
                }">
                <div class="relative w-full aspect-video bg-black/5 rounded-xl overflow-hidden group"
                     @touchstart="onTouchStart($event)"
                     @touchend="onTouchEnd($event)">
                    <template x-for="(slide, index) in slides" :key="index">
                        <img :src="slide" 
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out" 
                             :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'" 
                             alt="Project Slide">
                    </template>

                    {{-- Desktop-only Arrow Controls --}}
                    <div class="hidden md:flex absolute inset-x-0 top-1/2 -translate-y-1/2 justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                        <button @click="currentSlide = currentSlide === 0 ? slides.length - 1 : currentSlide - 1" class="w-10 h-10 rounded-full bg-white/80 backdrop-blur border border-black/10 flex items-center justify-center hover:bg-white transition-colors text-black shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="currentSlide = currentSlide === slides.length - 1 ? 0 : currentSlide + 1" class="w-10 h-10 rounded-full bg-white/80 backdrop-blur border border-black/10 flex items-center justify-center hover:bg-white transition-colors text-black shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    {{-- Mobile Swipe Hint --}}
                    <div x-show="showSwipeHint"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="md:hidden absolute inset-0 flex items-center justify-center gap-4 z-20 pointer-events-none">
                        <div class="flex items-center gap-2 bg-black/30 backdrop-blur-sm rounded-full px-4 py-2">
                            <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span class="font-mono text-[9px] uppercase tracking-widest text-white/80 whitespace-nowrap">Swipe to browse</span>
                            <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>

                    {{-- Dot Indicators --}}
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index"
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="currentSlide === index ? 'w-6 bg-white shadow' : 'w-2 bg-white/50 hover:bg-white/80'"></button>
                        </template>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── CREDITS BLOCK ── role + collaborators ── --}}
        <div class="border-t border-black/10 pt-10 grid grid-cols-1 sm:grid-cols-2 gap-8">

            @if($project->role)
                <div>
                    <p class="font-mono text-[9px] uppercase tracking-widest text-black/35 mb-2">My Role</p>
                    <p class="font-mono text-sm text-black/75">{{ $project->role }}</p>
                </div>
            @endif

            @if($project->collaborators)
                <div>
                    <p class="font-mono text-[9px] uppercase tracking-widest text-black/35 mb-2">Collaborators</p>
                    <div class="space-y-1">
                        @foreach(array_filter(array_map('trim', explode(',', $project->collaborators))) as $collab)
                            <p class="font-mono text-sm text-black/75">{{ $collab }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ── ACTION LINKS ── --}}
        @if($project->demo_url || $project->github_url)
            <div class="mt-12 flex flex-wrap gap-3">
                @if($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#512b81] text-white font-mono text-[10px] uppercase tracking-widest hover:bg-[#3d1f61] transition-colors duration-200">
                        View Live
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                @endif
                @if($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-black/25 text-black font-mono text-[10px] uppercase tracking-widest hover:bg-black hover:text-[#FAF7E6] transition-colors duration-200">
                        Repository
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </a>
                @endif
            </div>
        @endif

    </div>

</div>

{{-- Marquee keyframe (left-scroll) --}}
<style>
    @keyframes marquee-left {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
</style>

@endsection
