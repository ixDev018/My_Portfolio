@extends('admin.layout')

@section('admin_content')
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/core@2.2.4/dist/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/starter-kit@2.2.4/dist/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/extension-image@2.2.4/dist/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/extension-youtube@2.2.4/dist/index.umd.min.js"></script>
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
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Edit Project Details</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Modify published metrics for "{{ $project->title }}"</p>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" 
          class="bg-slate-900 border border-slate-850 p-8 rounded-2xl shadow-xl space-y-8" 
          id="project-form"
          x-data="{ isSubmitting: false }"
          @submit="isSubmitting = true">
        @csrf

        {{-- ── METADATA CARDS ── --}}
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-white border-b border-white/10 pb-2">1. Metadata</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                
                <!-- Source / Studio (replaces client) -->
                <div>
                    <label for="client" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Source / Studio</label>
                    <input type="text" name="client" id="client" value="{{ old('client', $project->client) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    @error('client') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Date Published -->
                <div>
                    <label for="date_published" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Date Published (e.g. 2025)</label>
                    <input type="text" name="date_published" id="date_published" value="{{ old('date_published', $project->date_published) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    @error('date_published') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Role</label>
                    <input type="text" name="role" id="role" value="{{ old('role', $project->role) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    @error('role') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Collaborators -->
                <div>
                    <label for="collaborators" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Collaborators</label>
                    <input type="text" name="collaborators" id="collaborators" value="{{ old('collaborators', $project->collaborators) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                    @error('collaborators') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tags / Technology Stack (Comma Separated)</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', $project->tags) }}" placeholder="e.g. Laravel, Tailwind CSS" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                @error('tags') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Links -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Demo URL -->
                <div>
                    <label for="demo_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Live Demo Link</label>
                    <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $project->demo_url) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                </div>

                <!-- GitHub URL -->
                <div>
                    <label for="github_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">GitHub Repo Link</label>
                    <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $project->github_url) }}" class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }} class="w-5 h-5 bg-slate-950 border border-slate-800 rounded focus:ring-cyan-500/20 text-cyan-500 cursor-pointer">
                <label for="featured" class="text-sm font-semibold text-slate-300 cursor-pointer select-none">
                    Feature this project on home grid
                </label>
            </div>
        </div>

        {{-- ── THUMBNAIL / MEDIA ── --}}
        @include('admin.projects.partials.media_upload')
            
            <!-- Existing Gallery Management -->
            @if(!empty($project->gallery_images))
                <div class="border-t border-slate-800 pt-6 mt-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 font-mono">Manage Existing Legacy Gallery</label>
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
        </div>

        {{-- ── STORY / CONTENT ── --}}
        <div class="space-y-6 pt-6 border-t border-slate-800">
            <h2 class="text-xl font-bold text-white border-b border-white/10 pb-2">3. The Story</h2>
            
            <!-- Short Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Short Description (Card Summary)</label>
                <textarea name="description" id="description" rows="3" required class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 resize-none">{{ old('description', $project->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- TipTap Notion-style Editor -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Full Case Study (Rich Content)</label>
                
                <div class="bg-slate-950 border border-slate-800 rounded-xl overflow-hidden focus-within:border-cyan-500/50 transition-colors">
                    <!-- Toolbar -->
                    <div class="bg-slate-900 border-b border-slate-800 p-2 flex flex-wrap gap-2 items-center" id="tiptap-toolbar">
                        <button type="button" data-cmd="bold" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path></svg></button>
                        <button type="button" data-cmd="italic" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg></button>
                        <div class="w-px h-6 bg-slate-800 mx-1"></div>
                        <button type="button" data-cmd="h2" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors font-bold font-mono text-xs">H2</button>
                        <button type="button" data-cmd="h3" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors font-bold font-mono text-xs">H3</button>
                        <div class="w-px h-6 bg-slate-800 mx-1"></div>
                        <button type="button" data-cmd="bulletList" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                        <button type="button" data-cmd="orderedList" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors font-mono text-xs">1.</button>
                        <button type="button" data-cmd="blockquote" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg></button>
                        <div class="w-px h-6 bg-slate-800 mx-1"></div>
                        <label class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors cursor-pointer flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-semibold">Image/Slide</span>
                            <input type="file" id="tiptap-image-upload" accept="image/*" class="hidden" multiple>
                        </label>
                        <button type="button" data-cmd="video" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-semibold">YouTube</span>
                        </button>
                    </div>
                    
                    <!-- Editor Mount -->
                    <div id="tiptap-editor" class="p-6 text-slate-200 prose prose-invert max-w-none min-h-[300px] outline-none font-sans" style="font-family: 'Poppins', sans-serif;"></div>
                </div>
                
                <!-- Hidden input to store JSON content -->
                <input type="hidden" name="body_content" id="body_content" value="{{ old('body_content', $project->body_content) }}">
                @error('body_content') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                
                <style>
                    .ProseMirror p.is-editor-empty:first-child::before {
                        content: 'Start writing your story here. Use the toolbar or type...';
                        float: left;
                        color: #475569;
                        pointer-events: none;
                        height: 0;
                    }
                    .ProseMirror img {
                        border-radius: 0.5rem;
                        max-width: 100%;
                        height: auto;
                    }
                    .ProseMirror iframe {
                        width: 100%;
                        aspect-ratio: 16/9;
                        border-radius: 0.5rem;
                    }
                </style>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="pt-6 border-t border-slate-800 flex justify-end">
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-95 transition-all duration-200 disabled:opacity-75 disabled:cursor-not-allowed flex items-center gap-2">
                
                <!-- Spinner SVG (visible when submitting) -->
                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak>
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <span x-text="isSubmitting ? 'Uploading & Saving Changes...' : 'Save Project Changes'"></span>
            </button>
        </div>

    </form>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Check if TipTap loaded
            if(typeof window.tiptap === 'undefined') {
                console.error("TipTap failed to load via CDN");
                return;
            }

            const { Editor } = window.tiptap;
            const StarterKit = window.tiptapStarterKit;
            const Image = window.tiptapImage;
            const Youtube = window.tiptapYoutube;

            let initialContent = '';
            try {
                let rawVal = document.getElementById('body_content').value;
                if(rawVal && rawVal.trim().startsWith('{')) {
                    initialContent = JSON.parse(rawVal);
                } else if(rawVal) {
                    // fallback if there's old non-json data
                    initialContent = rawVal;
                }
            } catch(e) {}

            // Initialize Editor
            const editor = new Editor({
                element: document.querySelector('#tiptap-editor'),
                extensions: [
                    StarterKit.default,
                    Image.default,
                    Youtube.default.configure({
                        controls: false,
                    }),
                ],
                content: initialContent,
                onUpdate: ({ editor }) => {
                    // Export as HTML
                    document.getElementById('body_content').value = editor.getHTML();
                },
            });

            // Toolbar Commands
            document.querySelectorAll('#tiptap-toolbar button[data-cmd]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const cmd = btn.getAttribute('data-cmd');
                    if (cmd === 'bold') editor.chain().focus().toggleBold().run();
                    if (cmd === 'italic') editor.chain().focus().toggleItalic().run();
                    if (cmd === 'h2') editor.chain().focus().toggleHeading({ level: 2 }).run();
                    if (cmd === 'h3') editor.chain().focus().toggleHeading({ level: 3 }).run();
                    if (cmd === 'bulletList') editor.chain().focus().toggleBulletList().run();
                    if (cmd === 'orderedList') editor.chain().focus().toggleOrderedList().run();
                    if (cmd === 'blockquote') editor.chain().focus().toggleBlockquote().run();
                    if (cmd === 'video') {
                        const url = prompt('Enter YouTube URL:');
                        if (url) editor.chain().focus().setYoutubeVideo({ src: url }).run();
                    }
                });
            });

            // Handle Image / Slide Upload
            const imageUploadInput = document.getElementById('tiptap-image-upload');
            imageUploadInput.addEventListener('change', async (e) => {
                if(!e.target.files.length) return;
                
                for (const file of e.target.files) {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch('{{ route("admin.projects.upload_body_media") }}', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();
                        if (data.url) {
                            editor.chain().focus().setImage({ src: data.url }).run();
                        }
                    } catch (error) {
                        console.error('Upload failed', error);
                        alert('Image upload failed.');
                    }
                }
                e.target.value = ''; // reset
            });

            // Form Submit hook
            document.getElementById('project-form').addEventListener('submit', function() {
                if(editor.isEmpty) {
                    document.getElementById('body_content').value = "";
                } else {
                    document.getElementById('body_content').value = editor.getHTML();
                }
            });
        });
    </script>

@endsection
