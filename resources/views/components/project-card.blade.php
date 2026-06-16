@props(['project'])

@php
    $tagsArray = array_filter(array_map('trim', explode(',', $project->tags ?? '')));
@endphp

<!-- Clicking anywhere on the card redirects the user to the isolated project detail page -->
<a href="{{ route('portfolio.project.show', $project->slug) }}" 
   class="group block bg-white border-4 border-black p-4 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex flex-col h-full text-black">
    
    <!-- Thumbnail Graphic Area -->
    <div class="relative overflow-hidden aspect-video bg-slate-950 flex items-center justify-center border-2 border-black rounded-xl mb-4">
        @if($project->thumbnail_path)
            <img src="{{ (Str::startsWith($project->thumbnail_path, 'http') ? $project->thumbnail_path : ((Str::startsWith($project->thumbnail_path, 'images/') || Str::startsWith($project->thumbnail_path, 'videos/')) ? asset($project->thumbnail_path) : Storage::url($project->thumbnail_path))) }}" 
                 alt="{{ $project->title }}" 
                 class="w-full h-full object-cover">
        @else
            <!-- Elegant vector fallback if no thumbnail is uploaded -->
            <div class="w-full h-full bg-gradient-to-br from-indigo-950 to-slate-950 flex flex-col justify-center items-center p-4 relative">
                <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#faf7e6_1px,transparent_1px),linear-gradient(to_bottom,#faf7e6_1px,transparent_1px)] bg-[size:12px_12px]"></div>
                <div class="w-10 h-10 rounded-lg bg-yellow-400 border-2 border-black text-black flex items-center justify-center mb-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 font-mono">Outputs Build</span>
            </div>
        @endif

        <!-- Floating Badge for Featured Status -->
        @if($project->featured)
            <span class="absolute top-3 right-3 bg-yellow-300 border-2 border-black text-black text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded shadow-[1.5px_1.5px_0px_0px_rgba(0,0,0,1)]">
                Featured
            </span>
        @endif
    </div>

    <!-- Details/Text Area -->
    <div class="flex-grow flex flex-col">
        <!-- Title -->
        <h3 class="text-base font-extrabold text-black group-hover:text-[#ff6b00] transition-colors duration-200 mb-2 leading-tight font-display">
            {{ $project->title }}
        </h3>

        <!-- Description -->
        <p class="text-slate-600 text-xs leading-relaxed mb-4 flex-grow line-clamp-3 font-sans">
            {{ $project->description }}
        </p>

        <!-- Tags Grid -->
        <div class="flex flex-wrap gap-1 mt-auto pt-3 border-t border-slate-100">
            @foreach($tagsArray as $tag)
                <span class="text-[9px] font-bold text-black bg-slate-100 border border-black/80 px-2 py-0.5 rounded">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    </div>
</a>
