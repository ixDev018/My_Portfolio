@extends('layouts.app')

@section('title', $project->title . ' | ' . ($project->medium ?? 'Output'))

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     PROJECT OUTPUT — SPA-STYLE DETAIL PAGE
     Layout: sticky marquee bg → playback hero → CMS body
═══════════════════════════════════════════════════════════ --}}
<div class="bg-[#FAF7E6] text-black min-h-screen" style="font-family: 'Bitcount Single', monospace;">

    {{-- ── HERO BLOCK ── --}}
    <div class="relative w-full pt-32 pb-6">
        
        {{-- Container for Back Button & Title --}}
        <div class="max-w-4xl mx-auto px-6 w-full relative z-20">
            
            {{-- ── IN-FLOW BACK NAVIGATION (Aligned to left of content) ── --}}
            <a href="{{ route('portfolio.index') }}#works"
               class="absolute left-6 top-0 hidden md:inline-flex items-center gap-3 font-sans text-[13px] text-[#512b81] hover:opacity-70 transition-opacity"
               style="font-family: 'Poppins', sans-serif;">
                <span class="w-8 h-8 rounded-full border border-[#512b81] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </span>
                back to home
            </a>
            
            {{-- Mobile Back Button (Centered) --}}
            <a href="{{ route('portfolio.index') }}#works"
               class="md:hidden flex items-center justify-center gap-3 font-sans text-[13px] text-[#512b81] hover:opacity-70 transition-opacity mb-6"
               style="font-family: 'Poppins', sans-serif;">
                <span class="w-8 h-8 rounded-full border border-[#512b81] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </span>
                back to home
            </a>

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
                <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#FAF7E6] to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#FAF7E6] to-transparent"></div>
            </div>

            {{-- ── PLAYBACK DISPLAY CARD ── --}}
            <div class="relative z-10 w-full max-w-4xl px-6">
                <div class="w-full aspect-video rounded-md overflow-hidden bg-white border border-black/10 shadow-sm relative group flex items-center justify-center">
                    @if($project->media_type === 'video' && $project->video_url)
                        <video src="{{ $project->video_url }}"
                               autoplay loop muted playsinline controls
                               class="w-full h-full object-cover">
                        </video>
                    @elseif($project->thumbnail_path)
                        <img src="{{ Str::startsWith($project->thumbnail_path, 'http') ? $project->thumbnail_path : asset('storage/' . $project->thumbnail_path) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-full object-cover">
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
                </div>

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
                        @if($project->year)
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/35 mb-0.5">Date Published</p>
                            <p class="font-mono text-[11px] text-black/70">{{ $project->year }}</p>
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
    </div>

    {{-- ══ CMS CONTENT BODY ══════════════════════════════════════
         Everything below the playback card — the "more story" area
    ═══════════════════════════════════════════════════════════ --}}
    <div class="relative z-10 max-w-3xl mx-auto px-6 pt-4 pb-24">

        {{-- Divider --}}
        <div class="flex items-center gap-4 mb-10">
            <div class="flex-1 h-px bg-black/10"></div>
            <span class="font-mono text-[9px] uppercase tracking-widest text-black/30">The Story</span>
            <div class="flex-1 h-px bg-black/10"></div>
        </div>

        {{-- Overview short description --}}
        @if($project->description)
            <p class="font-mono text-sm text-black/60 leading-relaxed mb-10">
                {{ $project->description }}
            </p>
        @endif

        {{-- Body content — multi-paragraph CMS story --}}
        @if($project->body_content)
            <div class="space-y-6 mb-10">
                @foreach(array_filter(explode("\n\n", $project->body_content)) as $para)
                    <p class="font-sans text-sm sm:text-base text-black/75 leading-[1.85]" style="font-family: 'Poppins', sans-serif;">
                        {{ trim($para) }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- ── SLIDES GALLERY SECTION ── --}}
        <div class="mb-14" x-data="{ currentSlide: 0, slides: ['https://picsum.photos/id/237/1000/600', 'https://picsum.photos/id/1015/1000/600', 'https://picsum.photos/id/1025/1000/600'] }">
            <div class="relative w-full aspect-video bg-black/5 rounded-xl overflow-hidden group">
                <template x-for="(slide, index) in slides" :key="index">
                    <img :src="slide" 
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out" 
                         :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'" 
                         alt="Project Slide">
                </template>

                {{-- Controls --}}
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                    <button @click="currentSlide = currentSlide === 0 ? slides.length - 1 : currentSlide - 1" class="w-10 h-10 rounded-full bg-white/80 backdrop-blur border border-black/10 flex items-center justify-center hover:bg-white transition-colors text-black shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="currentSlide = currentSlide === slides.length - 1 ? 0 : currentSlide + 1" class="w-10 h-10 rounded-full bg-white/80 backdrop-blur border border-black/10 flex items-center justify-center hover:bg-white transition-colors text-black shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- Indicators --}}
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index"
                                class="h-1.5 rounded-full transition-all duration-300"
                                :class="currentSlide === index ? 'w-6 bg-white shadow' : 'w-2 bg-white/50 hover:bg-white/80'"></button>
                    </template>
                </div>
            </div>
        </div>

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
