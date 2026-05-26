@extends('admin.layout')

@section('admin_content')

    <!-- NoUiSlider for Video Trimming -->
    <link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Project Title</label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('title') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Subtitle (Optional)</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('subtitle') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Category</label>
                <select name="category" id="category" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    <option value="ui" {{ old('category') == 'ui' ? 'selected' : '' }}>UI/UX / Product Design</option>
                    <option value="visual" {{ old('category') == 'visual' ? 'selected' : '' }}>Creative Visual Outputs</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Medium -->
            <div>
                <label for="medium" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Medium (e.g. SaaS, Motion Graphics)</label>
                <input type="text" name="medium" id="medium" value="{{ old('medium') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('medium') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Client -->
            <div>
                <label for="client" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Client</label>
                <input type="text" name="client" id="client" value="{{ old('client') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('client') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Role -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Role</label>
                <input type="text" name="role" id="role" value="{{ old('role') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('role') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Year -->
            <div>
                <label for="year" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Year</label>
                <input type="text" name="year" id="year" value="{{ old('year') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('year') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Collaborators -->
            <div>
                <label for="collaborators" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Collaborators</label>
                <input type="text" name="collaborators" id="collaborators" value="{{ old('collaborators') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('collaborators') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Description (Short) -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Short Description (Card)</label>
            <textarea name="description" id="description" rows="3" required class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 resize-none">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>
        
        <!-- Body Content (Long) -->
        <div>
            <label for="body_content" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Full Case Study / Body Content (HTML allowed)</label>
            <textarea name="body_content" id="body_content" rows="8" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 resize-none">{{ old('body_content') }}</textarea>
            @error('body_content') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- Tags -->
        <div>
            <label for="tags" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tags / Technology Stack (Comma Separated)</label>
            <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="e.g. Laravel, Tailwind CSS" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
            @error('tags') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Demo URL -->
            <div>
                <label for="demo_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Live Demo Link</label>
                <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('demo_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- GitHub URL -->
            <div>
                <label for="github_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">GitHub Repo Link</label>
                <input type="url" name="github_url" id="github_url" value="{{ old('github_url') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('github_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Video URL -->
            <div>
                <label for="video_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Video Link (YouTube/Vimeo)</label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('video_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Featured & Media Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start p-6 bg-slate-950/60 border border-slate-850 rounded-xl">
            <div class="md:col-span-2">
                @include('admin.projects.partials.media_upload')
            </div>

            <!-- Featured Checkbox -->
            <div class="flex items-center gap-3 md:col-span-2">
                <input type="checkbox" name="featured" id="featured" value="1" class="w-5 h-5 bg-slate-950 border border-slate-800 rounded focus:ring-cyan-500/20 text-cyan-500 cursor-pointer">
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
