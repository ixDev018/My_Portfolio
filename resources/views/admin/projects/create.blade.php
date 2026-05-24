@extends('admin.layout')

@section('admin_content')

    <!-- Navigation Back Link -->
    <div class="mb-4">
        <a href="{{ route('admin.projects.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
            &larr; Back to Listings
        </a>
    </div>

    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Add New Project</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Publish a new build to your public portfolio showcase</p>
    </div>

    <!-- Creation Form -->
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-850 p-8 rounded-2xl shadow-xl space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Project Title</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   required 
                   value="{{ old('title') }}"
                   class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
            @error('title')
                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Project Description</label>
            <textarea name="description" 
                      id="description" 
                      rows="6"
                      required
                      class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200 resize-none">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tags -->
        <div>
            <label for="tags" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tags / Technology Stack (Comma Separated)</label>
            <input type="text" 
                   name="tags" 
                   id="tags" 
                   value="{{ old('tags') }}"
                   placeholder="e.g. Laravel, Tailwind CSS, VueJS, Stripe"
                   class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
            @error('tags')
                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Demo URL -->
            <div>
                <label for="demo_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Live Demo Link (URL)</label>
                <input type="url" 
                       name="demo_url" 
                       id="demo_url" 
                       value="{{ old('demo_url') }}"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                @error('demo_url')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- GitHub URL -->
            <div>
                <label for="github_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">GitHub Repository Link (URL)</label>
                <input type="url" 
                       name="github_url" 
                       id="github_url" 
                       value="{{ old('github_url') }}"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                @error('github_url')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Featured & Media Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-center p-6 bg-slate-950/60 border border-slate-850 rounded-xl">
            <!-- Thumbnail File -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Project Thumbnail Graphic</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                       class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:hover:bg-slate-850 cursor-pointer">
                @error('thumbnail')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured Checkbox -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="featured" id="featured" value="1"
                       class="w-5 h-5 bg-slate-950 border border-slate-800 rounded focus:ring-cyan-500/20 text-cyan-500 cursor-pointer">
                <label for="featured" class="text-sm font-semibold text-slate-300 cursor-pointer select-none">
                    Feature this project on home grid
                </label>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="pt-6 border-t border-slate-800 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-95 transition-all duration-200">
                Publish Project
            </button>
        </div>

    </form>

@endsection
