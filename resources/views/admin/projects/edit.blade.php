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
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Edit Project Details</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Modify published metrics for "{{ $project->title }}"</p>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-850 p-8 rounded-2xl shadow-xl space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Project Title</label>
                <input type="text" name="title" id="title" required value="{{ old('title', $project->title) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('title') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Subtitle (Optional)</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $project->subtitle) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('subtitle') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Category</label>
                <select name="category" id="category" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    <option value="ui" {{ old('category', $project->category) == 'ui' ? 'selected' : '' }}>UI/UX / Product Design</option>
                    <option value="visual" {{ old('category', $project->category) == 'visual' ? 'selected' : '' }}>Creative Visual Outputs</option>
                    <option value="other" {{ old('category', $project->category) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Medium -->
            <div>
                <label for="medium" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Medium (e.g. SaaS, Motion Graphics)</label>
                <input type="text" name="medium" id="medium" value="{{ old('medium', $project->medium) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('medium') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Client -->
            <div>
                <label for="client" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Client</label>
                <input type="text" name="client" id="client" value="{{ old('client', $project->client) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('client') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Role -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Role</label>
                <input type="text" name="role" id="role" value="{{ old('role', $project->role) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('role') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Year -->
            <div>
                <label for="year" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Year</label>
                <input type="text" name="year" id="year" value="{{ old('year', $project->year) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('year') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Collaborators -->
            <div>
                <label for="collaborators" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Collaborators</label>
                <input type="text" name="collaborators" id="collaborators" value="{{ old('collaborators', $project->collaborators) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('collaborators') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Short Description</label>
            <textarea name="description" id="description" rows="3" required class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 resize-none">{{ old('description', $project->description) }}</textarea>
            @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>
        
        <!-- Body Content -->
        <div>
            <label for="body_content" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Full Case Study / Body Content (HTML allowed)</label>
            <textarea name="body_content" id="body_content" rows="8" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 resize-none">{{ old('body_content', $project->body_content) }}</textarea>
            @error('body_content') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- Tags -->
        <div>
            <label for="tags" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tags / Technology Stack (Comma Separated)</label>
            <input type="text" name="tags" id="tags" value="{{ old('tags', $project->tags) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
            @error('tags') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Demo URL -->
            <div>
                <label for="demo_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Live Demo Link (URL)</label>
                <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $project->demo_url) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('demo_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- GitHub URL -->
            <div>
                <label for="github_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">GitHub Repository Link (URL)</label>
                <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $project->github_url) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('github_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Video URL -->
            <div>
                <label for="video_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Video Link (YouTube/Vimeo)</label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $project->video_url) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('video_url') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Media Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start p-6 bg-slate-950/60 border border-slate-850 rounded-xl">
            <!-- Thumbnail File -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Update Project Thumbnail</label>
                @if($project->thumbnail_path)
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ asset('storage/' . $project->thumbnail_path) }}" class="w-16 h-12 rounded object-cover border border-slate-800 shadow">
                        <span class="text-xs text-slate-500">Current thumbnail</span>
                    </div>
                @endif
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:hover:bg-slate-850 cursor-pointer">
                @error('thumbnail') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <!-- Gallery Files -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Add Gallery Images</label>
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:hover:bg-slate-850 cursor-pointer">
                <p class="text-xs text-slate-500 mt-2 font-mono">Hold Ctrl/Cmd to select multiple images to add to the existing gallery.</p>
                @error('gallery.*') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Existing Gallery Management -->
            @if(!empty($project->gallery_images))
                <div class="lg:col-span-2 border-t border-slate-800 pt-6 mt-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 font-mono">Manage Existing Gallery</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        @foreach($project->gallery_images as $index => $imagePath)
                            <div class="relative group rounded-lg overflow-hidden border border-slate-800 aspect-video">
                                <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="delete_gallery[]" value="{{ $index }}" class="w-4 h-4 text-rose-500 bg-slate-900 border-slate-700 rounded focus:ring-rose-500/20">
                                        <span class="text-xs text-rose-400 font-bold">Delete</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Featured Checkbox -->
            <div class="flex items-center gap-3 lg:col-span-2 border-t border-slate-800 pt-6 mt-2">
                <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }} class="w-5 h-5 bg-slate-950 border border-slate-800 rounded focus:ring-cyan-500/20 text-cyan-500 cursor-pointer">
                <label for="featured" class="text-sm font-semibold text-slate-300 cursor-pointer select-none">
                    Feature this project on home grid
                </label>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="pt-6 border-t border-slate-800 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-95 transition-all duration-200">
                Save Project Changes
            </button>
        </div>

    </form>

@endsection
