@extends('layouts.app')

@section('title', $project->title . ' | Project')

@section('content')

    <!-- Outer Wrapper in Neo-Brutalist styling -->
    <div class="bg-[#FAF7E6] text-black min-h-screen pt-32 pb-24 px-6 relative">
        <div class="max-w-4xl mx-auto">
            
            <!-- Navigation Back Button (Neo-Brutalist Card styling) -->
            <div class="mb-8">
                <a href="{{ route('portfolio.index') }}#projects" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-black font-mono text-xs font-bold uppercase tracking-wider rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                    &larr; Back to Portfolio
                </a>
            </div>

            <!-- Project Details Container Card -->
            <article class="bg-white border-4 border-black rounded-3xl p-8 sm:p-12 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                
                <!-- Main Header Title -->
                <header class="mb-8">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($project->featured)
                            <span class="px-3 py-1 bg-cyan-200 border-2 border-black font-mono text-[10px] font-extrabold uppercase tracking-widest rounded-md">
                                Featured Work
                            </span>
                        @endif
                        
                        <span class="px-3 py-1 bg-purple-200 border-2 border-black font-mono text-[10px] font-extrabold uppercase tracking-widest rounded-md">
                            Outputs
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-none mb-3">
                        {{ $project->title }}
                    </h1>
                    
                    <p class="text-xs text-slate-500 font-mono tracking-wider">Published on {{ $project->created_at->format('F d, Y') }}</p>
                </header>

                <!-- Project Preview Image -->
                <div class="border-4 border-black rounded-2xl overflow-hidden aspect-video bg-slate-900 mb-10 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative">
                    @if($project->thumbnail_path)
                        <img src="{{ asset('storage/' . $project->thumbnail_path) }}" 
                             alt="{{ $project->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <!-- Graphic vector fallback -->
                        <div class="w-full h-full bg-gradient-to-br from-indigo-950 to-slate-950 flex flex-col justify-center items-center p-6 relative">
                            <div class="absolute inset-0 opacity-15 bg-[linear-gradient(to_right,#FAF7E6_1px,transparent_1px),linear-gradient(to_bottom,#FAF7E6_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                            <div class="w-16 h-16 rounded-2xl bg-yellow-400 border-4 border-black text-black flex items-center justify-center mb-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            </div>
                            <span class="text-sm font-bold uppercase tracking-widest text-slate-400 font-mono">Digital Codebase Workspace</span>
                        </div>
                    @endif
                </div>

                <!-- Description & Details split grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Left Side: Overview & Description -->
                    <div class="lg:col-span-8 space-y-6">
                        <h2 class="text-lg font-extrabold uppercase tracking-wide border-b-2 border-black pb-2 font-display">Project Overview</h2>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed space-y-4 font-sans whitespace-pre-line">
                            {{ $project->description }}
                        </div>
                    </div>

                    <!-- Right Side: Tech Stack & Links -->
                    <div class="lg:col-span-4 space-y-8">
                        
                        <!-- Tech stack list -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest font-mono text-slate-500 mb-4">Tech Stack</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_filter(array_map('trim', explode(',', $project->tags ?? ''))) as $tag)
                                    <span class="text-[11px] font-bold text-black bg-yellow-300 border-2 border-black px-3 py-1 rounded-lg shadow-[1.5px_1.5px_0px_0px_rgba(0,0,0,1)]">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Links -->
                        <div class="pt-6 border-t-2 border-black/10 space-y-3">
                            @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" 
                                   class="w-full text-center py-3 bg-[#a855f7] hover:bg-purple-600 text-white font-bold text-sm uppercase tracking-wider rounded-xl border-2 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-1.5">
                                    Launch Live Demo
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif

                            @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" 
                                   class="w-full text-center py-3 bg-white hover:bg-slate-50 text-black font-bold text-sm uppercase tracking-wider rounded-xl border-2 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-1.5">
                                    Explore Repository
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

            </article>
        </div>
    </div>

@endsection
