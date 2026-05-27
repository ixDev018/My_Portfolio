@extends('admin.layout')

@section('admin_content')

<style>
    .ps-panel {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 1rem;
        overflow: hidden;
    }
    .ps-panel-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.65rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        background: rgba(0,0,0,0.25);
    }
    .ps-panel-label {
        font-family: 'Poppins', sans-serif;
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: rgba(255,255,255,0.8);
    }
    .ps-body { padding: 1.5rem; }

    /* Avatar ring preview */
    .avatar-ring {
        width: 80px; height: 80px;
        border-radius: 50%; overflow: hidden;
        border: 2.5px solid rgba(255,255,255,0.15);
        background: #000; flex-shrink: 0;
        transition: border-color .2s ease;
    }
    .avatar-ring:hover { border-color: #FF851B; }
    .avatar-ring img { width: 100%; height: 100%; object-fit: cover; }

    /* CV preview box */
    .cv-box {
        width: 100%; aspect-ratio: 16/9;
        border-radius: 0.75rem; overflow: hidden;
        background: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center;
    }
    .cv-box iframe { width: 100%; height: 100%; border: none; background: #fff; }

    /* Social input row */
    .social-row { display: flex; align-items: center; gap: 0.75rem; }
    .social-icon {
        width: 36px; height: 36px; border-radius: 0.5rem;
        background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .social-icon svg { width: 16px; height: 16px; color: rgba(255,255,255,0.5); }

    /* Status pill */
    .status-pill {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.15rem 0.6rem; border-radius: 100px;
        font-family: 'Space Mono', monospace; font-size: 0.58rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;
    }
    .pill-green { background: rgba(110,231,183,0.12); color: #6ee7b7; border: 1px solid rgba(110,231,183,0.25); }
    .pill-amber { background: rgba(253,186,116,0.12); color: #fdba74; border: 1px solid rgba(253,186,116,0.25); }

    /* Section label */
    .field-label-sm {
        font-family: 'Space Mono', monospace; font-size: 0.6rem;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: rgba(255,255,255,0.45); margin-bottom: 0.25rem;
    }
</style>

<div class="mb-6">
    <h1 class="cms-page-title">Profile Settings</h1>
    <p class="cms-page-subtitle">Contact info · Social links · Avatar · Resume / CV</p>
</div>

<form action="{{ route('admin.profile_settings.update') }}" method="POST" enctype="multipart/form-data"
      class="flex flex-col gap-5">
    @csrf

    {{-- ── ROW 1: Contact + Social ─────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Contact & Collaboration --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">📬 Contact & Collaboration</span>
            </div>
            <div class="ps-body flex flex-col gap-4">
                <p style="font-family:'Space Mono',monospace;font-size:0.6rem;color:rgba(255,255,255,0.4);line-height:1.6;">
                    These appear in the Collaborate / Contact section of the public site.
                </p>
                <div>
                    <label for="email" class="cms-label">Contact Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $profile->email ?? '') }}"
                           class="cms-input">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="location" class="cms-label">Location</label>
                    <input type="text" name="location" id="location"
                           placeholder="e.g. Cebu, Philippines"
                           value="{{ old('location', $profile->location ?? '') }}"
                           class="cms-input">
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">🔗 Social Links</span>
            </div>
            <div class="ps-body flex flex-col gap-4">

                {{-- GitHub --}}
                <div>
                    <label for="github_url" class="cms-label">GitHub</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        </div>
                        <input type="url" name="github_url" id="github_url"
                               placeholder="https://github.com/..."
                               value="{{ old('github_url', $profile->github_url ?? '') }}"
                               class="cms-input flex-1">
                    </div>
                </div>

                {{-- LinkedIn --}}
                <div>
                    <label for="linkedin_url" class="cms-label">LinkedIn</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </div>
                        <input type="url" name="linkedin_url" id="linkedin_url"
                               placeholder="https://linkedin.com/in/..."
                               value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                               class="cms-input flex-1">
                    </div>
                </div>

                {{-- Twitter / X --}}
                <div>
                    <label for="twitter_url" class="cms-label">Twitter / X</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </div>
                        <input type="url" name="twitter_url" id="twitter_url"
                               placeholder="https://x.com/..."
                               value="{{ old('twitter_url', $profile->twitter_url ?? '') }}"
                               class="cms-input flex-1">
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── ROW 2: Avatar + CV ──────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Avatar --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">🖼️ Profile Avatar</span>
                @if($profile && $profile->avatar_path)
                    <span class="status-pill pill-green">✓ Custom</span>
                @else
                    <span class="status-pill pill-amber">Default</span>
                @endif
            </div>
            <div class="ps-body">
                <div class="flex items-start gap-5 mb-5">
                    {{-- Avatar Preview --}}
                    <div class="avatar-ring">
                        <img id="avatar-preview"
                             src="{{ $profile && $profile->avatar_path ? asset('storage/'.$profile->avatar_path) : asset('images/intro/profile.png') }}"
                             alt="Avatar">
                    </div>
                    <div>
                        <p class="field-label-sm">Current avatar</p>
                        @if($profile && $profile->avatar_path)
                            <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:rgba(255,255,255,0.7);">
                                {{ basename($profile->avatar_path) }}
                            </p>
                        @else
                            <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:rgba(255,255,255,0.35);">
                                Using default profile image
                            </p>
                        @endif
                    </div>
                </div>
                <label class="cms-label mb-2 block">Upload new avatar <span style="color:rgba(255,255,255,0.35);font-weight:400;">(JPG, PNG, SVG)</span></label>
                <input type="file" name="avatar" id="avatar" accept="image/*"
                       onchange="document.getElementById('avatar-preview').src = URL.createObjectURL(this.files[0])"
                       class="block w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white/70 hover:file:bg-white/15 cursor-pointer text-slate-400">
            </div>
        </div>

        {{-- CV --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">📄 Resume / CV</span>
                @if($profile && $profile->cv_path)
                    <span class="status-pill pill-green">✓ Uploaded</span>
                @else
                    <span class="status-pill pill-amber">Missing</span>
                @endif
            </div>
            <div class="ps-body">
                {{-- CV preview --}}
                <div class="cv-box mb-4">
                    @if($profile && $profile->cv_path)
                        <iframe src="{{ asset('storage/'.$profile->cv_path) }}"></iframe>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;opacity:0.4;">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span style="font-family:'Space Mono',monospace;font-size:0.65rem;color:rgba(255,255,255,0.6);">No CV uploaded</span>
                        </div>
                    @endif
                </div>
                @if($profile && $profile->cv_path)
                    <div class="flex items-center gap-3 mb-4">
                        <span style="font-family:'Space Mono',monospace;font-size:0.65rem;color:rgba(255,255,255,0.6);">{{ basename($profile->cv_path) }}</span>
                        <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank"
                           style="font-family:'Space Mono',monospace;font-size:0.6rem;color:#79ECFF;text-decoration:underline;">Open ↗</a>
                    </div>
                @endif
                <label class="cms-label mb-2 block">Upload new CV <span style="color:rgba(255,255,255,0.35);font-weight:400;">(PDF)</span></label>
                <input type="file" name="cv" id="cv" accept="application/pdf"
                       class="block w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white/70 hover:file:bg-white/15 cursor-pointer text-slate-400">
            </div>
        </div>

    </div>

    {{-- Save --}}
    <div style="display:flex;justify-content:flex-end;padding-top:0.5rem;padding-bottom:1rem;">
        <button type="submit" class="cms-btn-primary" style="padding:0.75rem 2.5rem;font-size:0.9rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save Profile Settings
        </button>
    </div>

</form>

@endsection
