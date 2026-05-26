@extends('admin.layout')

@section('admin_content')

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="cms-page-title">Hero & Profile</h1>
        <p class="cms-page-subtitle">Hero video, personal info, contact links & collaboration details</p>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ── HERO SECTION ─────────────────────────────────── --}}
        <div class="cms-card p-6">
            <h2 style="font-family:'Outfit',sans-serif; font-size:1rem; font-weight:700; color:#fff; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                🎬 Hero Section (Text & Video)
            </h2>
            
            <div class="grid grid-cols-1 gap-5 mb-6">
                <div>
                    <label for="hero_top_text" class="cms-label">Hero Top Text</label>
                    <input type="text" name="hero_top_text" id="hero_top_text"
                           placeholder="e.g. TURNING IDEAS INTO"
                           value="{{ old('hero_top_text', $profile->hero_top_text ?? '') }}"
                           class="cms-input">
                </div>
                <div>
                    <label for="hero_title" class="cms-label">Hero Title</label>
                    <input type="text" name="hero_title" id="hero_title"
                           placeholder="e.g. REALITY"
                           value="{{ old('hero_title', $profile->hero_title ?? '') }}"
                           class="cms-input">
                </div>
                <div>
                    <label for="hero_subtitle" class="cms-label">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" id="hero_subtitle"
                           placeholder="e.g. One Pixel At A Time"
                           value="{{ old('hero_subtitle', $profile->hero_subtitle ?? '') }}"
                           class="cms-input">
                </div>
            </div>

            <p style="font-size:0.75rem; color:rgba(255,255,255,0.4); margin-bottom:1.25rem; font-family:'Space Mono',monospace;">
                Upload a custom .mp4 video to replace the default hero background. Leave blank to keep the default.
            </p>

            <div class="mb-5 bg-[#1a1c23] p-4 rounded-2xl border border-white/10 flex flex-col gap-4 shadow-lg shadow-black/20">
                <div class="flex justify-between items-center px-1">
                    <span class="text-xs font-mono font-bold text-slate-300 tracking-wider uppercase flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Current Background
                    </span>
                    @if($profile && $profile->hero_video_path)
                        <span class="text-[10px] bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-cyan-500/30">Custom Upload</span>
                    @else
                        <span class="text-[10px] bg-slate-700/50 text-slate-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-slate-600/50">System Default</span>
                    @endif
                </div>
                
                <div class="w-full aspect-video rounded-xl overflow-hidden border border-white/5 bg-black shadow-inner">
                    <video id="video-preview" autoplay loop muted playsinline class="w-full h-full object-cover">
                        @if($profile && $profile->hero_video_path)
                            <source id="video-source" src="{{ asset('storage/' . $profile->hero_video_path) }}" type="video/mp4">
                        @else
                            <source id="video-source" src="{{ asset('videos/bg_showreel_loop.mp4') }}" type="video/mp4">
                        @endif
                    </video>
                </div>
                
                <div class="mt-2">
                    <label class="cms-label mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload New Video <span class="text-white/30 font-normal">(MP4, WEBM — max 100MB)</span>
                    </label>
                    <input type="file" name="hero_video" id="hero_video" accept="video/mp4,video/mov,video/webm,video/ogg"
                           onchange="previewMedia(this, 'video-preview')"
                           class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white/80 hover:file:bg-white/20 cursor-pointer text-slate-400 transition-all">
                    @error('hero_video')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── CONTACT & COLLABORATION ───────────────────────── --}}
        <div class="cms-card p-6">
            <h2 style="font-family:'Outfit',sans-serif; font-size:1rem; font-weight:700; color:#fff; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                📬 Contact & Collaboration
            </h2>
            <p style="font-size:0.7rem; color:rgba(255,255,255,0.35); margin-bottom:1.25rem; font-family:'Space Mono',monospace;">
                These appear in the Collaborate / Contact section of the site.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="email" class="cms-label">Contact Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $profile->email ?? '') }}"
                           class="cms-input">
                    @error('email')<p style="color:#f87171;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="location" class="cms-label">Location</label>
                    <input type="text" name="location" id="location"
                           placeholder="e.g. Cebu, Philippines"
                           value="{{ old('location', $profile->location ?? '') }}"
                           class="cms-input">
                    @error('location')<p style="color:#f87171;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── SOCIAL LINKS ──────────────────────────────────── --}}
        <div class="cms-card p-6">
            <h2 style="font-family:'Outfit',sans-serif; font-size:1rem; font-weight:700; color:#fff; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                🔗 Social Links
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="github_url" class="cms-label">GitHub URL</label>
                    <input type="url" name="github_url" id="github_url"
                           placeholder="https://github.com/..."
                           value="{{ old('github_url', $profile->github_url ?? '') }}"
                           class="cms-input">
                </div>
                <div>
                    <label for="linkedin_url" class="cms-label">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" id="linkedin_url"
                           placeholder="https://linkedin.com/in/..."
                           value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                           class="cms-input">
                </div>
                <div>
                    <label for="twitter_url" class="cms-label">Twitter / X URL</label>
                    <input type="url" name="twitter_url" id="twitter_url"
                           placeholder="https://x.com/..."
                           value="{{ old('twitter_url', $profile->twitter_url ?? '') }}"
                           class="cms-input">
                </div>
            </div>
        </div>

        {{-- ── MEDIA & DOCUMENTS ────────────────────────────── --}}
        <div class="cms-card p-6">
            <h2 style="font-family:'Outfit',sans-serif; font-size:1rem; font-weight:700; color:#fff; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                🖼️ Avatar & CV
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Avatar -->
                <div class="bg-black/20 border border-white/5 rounded-2xl p-5 flex flex-col justify-between">
                    <div>
                        <label class="cms-label mb-4 flex items-center gap-2 text-white">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile Avatar Image
                        </label>
                        <div class="bg-[#1a1c23] p-4 rounded-xl border border-white/10 flex flex-col gap-4 mb-4 shadow-lg shadow-black/20">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-mono font-bold text-slate-300 tracking-wider uppercase">Avatar Preview</span>
                                @if($profile && $profile->avatar_path)
                                    <span class="text-[10px] bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-cyan-500/30">Custom</span>
                                @else
                                    <span class="text-[10px] bg-slate-700/50 text-slate-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-slate-600/50">Default</span>
                                @endif
                            </div>
                            <div class="flex justify-center py-2">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-white/10 bg-black shadow-inner">
                                    <img id="avatar-preview" 
                                         src="{{ $profile && $profile->avatar_path ? asset('storage/' . $profile->avatar_path) : asset('images/intro/profile.png') }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               onchange="previewMedia(this, 'avatar-preview')"
                               class="block w-full text-sm file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white/80 hover:file:bg-white/20 cursor-pointer text-slate-400 transition-all">
                    </div>
                </div>
                
                <!-- CV -->
                <div class="bg-black/20 border border-white/5 rounded-2xl p-5 flex flex-col justify-between">
                    <div>
                        <label class="cms-label mb-4 flex items-center gap-2 text-white">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Resume / CV (PDF)
                        </label>
                        <div class="bg-[#1a1c23] p-4 rounded-xl border border-white/10 flex flex-col gap-4 mb-4 shadow-lg shadow-black/20">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-mono font-bold text-slate-300 tracking-wider uppercase">Document Preview</span>
                                @if($profile && $profile->cv_path)
                                    <span class="text-[10px] bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-cyan-500/30">Custom</span>
                                @else
                                    <span class="text-[10px] bg-amber-500/20 text-amber-400 px-3 py-1 rounded-full font-bold uppercase tracking-widest border border-amber-500/30">Missing</span>
                                @endif
                            </div>
                            <div class="relative w-full aspect-video bg-black rounded-xl border border-white/5 flex flex-col items-center justify-center overflow-hidden shadow-inner">
                                @if($profile && $profile->cv_path)
                                    <iframe id="cv-preview" src="{{ asset('storage/' . $profile->cv_path) }}" class="w-full h-full border-none bg-white"></iframe>
                                    <div id="cv-fallback-text" class="hidden text-slate-500 font-mono text-xs text-center flex-col items-center gap-2"></div>
                                @else
                                    <iframe id="cv-preview" src="" class="w-full h-full border-none bg-white" style="display:none;"></iframe>
                                    <div id="cv-fallback-text" class="flex text-slate-500 font-mono text-xs text-center flex-col items-center gap-2">
                                        <svg class="w-8 h-8 opacity-40 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <span>No CV Document<br>Uploaded</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="file" name="cv" id="cv" accept="application/pdf"
                               onchange="previewMedia(this, 'cv-preview')"
                               class="block w-full text-sm file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white/80 hover:file:bg-white/20 cursor-pointer text-slate-400 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SAVE ─────────────────────────────────────────── --}}
        <div style="display:flex; justify-content:flex-end; padding-top:0.5rem;">
            <button type="submit" class="cms-btn-primary" style="padding:0.75rem 2.5rem; font-size:0.9rem;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save All Changes
            </button>
        </div>

    </form>

    <script>
        function previewMedia(input, previewId) {
            const previewElement = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewElement.src = e.target.result;
                    previewElement.style.display = 'block';

                    if (previewId === 'cv-preview') {
                        const fallbackText = document.getElementById('cv-fallback-text');
                        if (fallbackText) {
                            fallbackText.classList.remove('flex');
                            fallbackText.classList.add('hidden');
                        }
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
