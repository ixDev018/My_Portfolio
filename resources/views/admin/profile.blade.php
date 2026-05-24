@extends('admin.layout')

@section('admin_content')

    <!-- Profile Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Profile Settings</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Configure your personal information, timeline details, and social links</p>
    </div>

    <!-- Edit Profile Form -->
    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-850 p-8 rounded-2xl shadow-xl space-y-8">
        @csrf

        <!-- Core Personal Information -->
        <div>
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2">Personal Information</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Your Full Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required 
                           value="{{ old('name', $profile->name ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Professional Title -->
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Professional Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           required 
                           value="{{ old('title', $profile->title ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Biographies -->
        <div>
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2">Biography Details</h2>
            
            <div class="space-y-6">
                <!-- Short Bio -->
                <div>
                    <label for="bio_short" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Short Introduction Bio (Hero Section)</label>
                    <textarea name="bio_short" 
                              id="bio_short" 
                              rows="3"
                              class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200 resize-none">{{ old('bio_short', $profile->bio_short ?? '') }}</textarea>
                    @error('bio_short')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Long Bio -->
                <div>
                    <label for="bio_long" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Full Detailed Bio (About Section)</label>
                    <textarea name="bio_long" 
                              id="bio_long" 
                              rows="6"
                              class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200 resize-none">{{ old('bio_long', $profile->bio_long ?? '') }}</textarea>
                    @error('bio_long')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact & Socials -->
        <div>
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2">Contact & Social Links</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Contact Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $profile->email ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- GitHub URL -->
                <div>
                    <label for="github_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">GitHub Profile Link</label>
                    <input type="url" 
                           name="github_url" 
                           id="github_url" 
                           value="{{ old('github_url', $profile->github_url ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('github_url')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- LinkedIn URL -->
                <div>
                    <label for="linkedin_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">LinkedIn Profile Link</label>
                    <input type="url" 
                           name="linkedin_url" 
                           id="linkedin_url" 
                           value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('linkedin_url')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Twitter URL -->
                <div>
                    <label for="twitter_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Twitter/X Profile Link</label>
                    <input type="url" 
                           name="twitter_url" 
                           id="twitter_url" 
                           value="{{ old('twitter_url', $profile->twitter_url ?? '') }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('twitter_url')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Files Upload Section -->
        <div>
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2">Media & Documents</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <!-- Avatar File -->
                <div class="p-6 rounded-xl bg-slate-950/60 border border-slate-850">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Avatar Image</label>
                    
                    @if($profile && $profile->avatar_path)
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/' . $profile->avatar_path) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-800 shadow">
                            <span class="text-xs text-emerald-400 font-mono font-medium">Avatar Active</span>
                        </div>
                    @endif

                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:hover:bg-slate-850 cursor-pointer">
                    @error('avatar')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CV PDF Document -->
                <div class="p-6 rounded-xl bg-slate-950/60 border border-slate-850">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Resume / CV Document (PDF Only)</label>
                    
                    @if($profile && $profile->cv_path)
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="text-xs text-cyan-400 font-semibold hover:text-cyan-300 transition-colors">View Current CV</a>
                        </div>
                    @endif

                    <input type="file" name="cv" id="cv" accept="application/pdf"
                           class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:hover:bg-slate-850 cursor-pointer">
                    @error('cv')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-slate-800 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-95 transition-all duration-200">
                Save All Changes
            </button>
        </div>

    </form>

@endsection
