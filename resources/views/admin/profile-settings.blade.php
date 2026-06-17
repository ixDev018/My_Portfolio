@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }

    /* ── shared tokens ── */
    .lt-label {
        display:block; font-family:'Space Mono',monospace;
        font-size:0.58rem; text-transform:uppercase;
        letter-spacing:0.1em; color:#9B9589; margin-bottom:0.35rem;
    }
    .lt-input {
        width:100%; background:#fff; border:1px solid #D8D4C8;
        border-radius:0.5rem; padding:0.45rem 0.8rem;
        color:#1a1207; font-size:0.8125rem; font-family:'Inter',sans-serif;
        outline:none; transition:border-color 0.18s,box-shadow 0.18s;
    }
    .lt-input:focus { border-color:#6829AA; box-shadow:0 0 0 3px rgba(104,41,170,0.1); }
    .lt-input::placeholder { color:#B0A99F; }
    .lt-err { color:#dc2626; font-size:0.72rem; margin-top:0.25rem; }

    /* panel */
    .ps-panel {
        background:#fff; border:1px solid #D8D4C8;
        border-radius:1rem; overflow:hidden;
        box-shadow:0 1px 3px rgba(0,0,0,0.05);
    }
    .ps-panel-header {
        display:flex; align-items:center; justify-content:space-between;
        padding:0.7rem 1.25rem;
        border-bottom:1px solid #E2DDD3;
        background:#F7F5EE;
    }
    .ps-panel-label {
        font-family:'Outfit',sans-serif;
        font-size:0.82rem; font-weight:700; color:#1a1207;
    }
    .ps-body { padding:1.25rem; display:flex; flex-direction:column; gap:1rem; }

    /* status pills */
    .pill-ok {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.2rem 0.65rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem;
        font-weight:700; text-transform:uppercase; letter-spacing:0.08em;
        background:#E6FAF5; color:#0A8C5E; border:1px solid #A3E6CF;
    }
    .pill-warn {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.2rem 0.65rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem;
        font-weight:700; text-transform:uppercase; letter-spacing:0.08em;
        background:#FFF4E5; color:#C2480A; border:1px solid #FDDAAA;
    }

    /* avatar ring */
    .avatar-ring {
        width:76px; height:76px; border-radius:50%; overflow:hidden;
        border:2.5px solid #D8D4C8; background:#F0EDE6; flex-shrink:0;
        transition:border-color .2s;
    }
    .avatar-ring:hover { border-color:#6829AA; }
    .avatar-ring img { width:100%; height:100%; object-fit:cover; }

    /* CV preview box */
    .cv-box {
        width:100%; height:180px; border-radius:0.75rem; overflow:hidden;
        background:#F0EDE6; border:1px solid #E2DDD3;
        display:flex; align-items:center; justify-content:center;
    }
    .cv-box iframe { width:100%; height:100%; border:none; }

    /* social icon well */
    .social-row { display:flex; align-items:center; gap:0.65rem; }
    .social-icon {
        width:34px; height:34px; border-radius:0.45rem;
        background:#F7F5EE; border:1px solid #D8D4C8;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        color:#9B9589;
    }

    /* save button */
    .lt-btn-save {
        display:inline-flex; align-items:center; gap:0.45rem;
        padding:0.65rem 2.2rem; background:#6829AA; color:#fff;
        border:none; border-radius:0.65rem; font-size:0.875rem;
        font-weight:700; font-family:'Outfit',sans-serif; cursor:pointer;
        box-shadow:0 4px 16px rgba(104,41,170,0.28); transition:all .15s;
    }
    .lt-btn-save:hover { background:#5720A0; }

    /* file input */
    .lt-file {
        display:block; width:100%; font-size:0.8rem; color:#5A5248;
        cursor:pointer;
    }
    .lt-file::file-selector-button {
        margin-right:0.75rem; padding:0.35rem 0.75rem;
        background:#F0E8FF; color:#6829AA; border:1px solid #D8C0F8;
        border-radius:0.4rem; font-size:0.75rem; font-weight:700;
        font-family:'Outfit',sans-serif; cursor:pointer;
        transition:background .15s;
    }
    .lt-file::file-selector-button:hover { background:#EEE6FF; }

    .field-note {
        font-family:'Space Mono',monospace; font-size:0.58rem;
        color:#B0A99F; line-height:1.6;
    }
    .field-label-sm {
        font-family:'Space Mono',monospace; font-size:0.58rem;
        text-transform:uppercase; letter-spacing:0.1em; color:#9B9589;
    }
</style>

<div style="margin-bottom:0.85rem;">
    <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Profile Settings</h1>
    <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Contact info · Social links · Avatar · Resume / CV</p>
</div>

<form action="{{ route('admin.profile_settings.update') }}" method="POST" enctype="multipart/form-data"
      style="display:flex;flex-direction:column;gap:1.25rem;">
    @csrf

    {{-- ── ROW 1: Contact + Social ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

        {{-- Contact --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">📬 Contact &amp; Collaboration</span>
            </div>
            <div class="ps-body">
                <p class="field-note">These appear in the Collaborate / Contact section of the public site.</p>
                <div>
                    <label for="email" class="lt-label">Contact Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $profile->email ?? '') }}"
                           class="lt-input" placeholder="hello@example.com">
                    @error('email')<p class="lt-err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="location" class="lt-label">Location</label>
                    <input type="text" name="location" id="location"
                           placeholder="e.g. Cebu, Philippines"
                           value="{{ old('location', $profile->location ?? '') }}"
                           class="lt-input">
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">🔗 Social Links</span>
            </div>
            <div class="ps-body">

                {{-- GitHub --}}
                <div>
                    <label for="github_url" class="lt-label">GitHub</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        </div>
                        <input type="url" name="github_url" id="github_url"
                               placeholder="https://github.com/..."
                               value="{{ old('github_url', $profile->github_url ?? '') }}"
                               class="lt-input" style="flex:1;">
                    </div>
                </div>

                {{-- LinkedIn --}}
                <div>
                    <label for="linkedin_url" class="lt-label">LinkedIn</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </div>
                        <input type="url" name="linkedin_url" id="linkedin_url"
                               placeholder="https://linkedin.com/in/..."
                               value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                               class="lt-input" style="flex:1;">
                    </div>
                </div>

                {{-- Twitter / X --}}
                <div>
                    <label for="twitter_url" class="lt-label">Twitter / X</label>
                    <div class="social-row">
                        <div class="social-icon">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </div>
                        <input type="url" name="twitter_url" id="twitter_url"
                               placeholder="https://x.com/..."
                               value="{{ old('twitter_url', $profile->twitter_url ?? '') }}"
                               class="lt-input" style="flex:1;">
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── ROW 2: Avatar + CV ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

        {{-- Avatar --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">🖼️ Profile Avatar</span>
                @if($profile && $profile->avatar_path)
                    <span class="pill-ok">✓ Custom</span>
                @else
                    <span class="pill-warn">Default</span>
                @endif
            </div>
            <div class="ps-body">
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div class="avatar-ring">
                        <img id="avatar-preview"
                             src="{{ $profile && $profile->avatar_path ? (Str::startsWith($profile->avatar_path, 'http') ? $profile->avatar_path : ((Str::startsWith($profile->avatar_path, 'images/') || Str::startsWith($profile->avatar_path, 'videos/')) ? asset($profile->avatar_path) : Storage::url($profile->avatar_path))) : asset('images/intro/profile.png') }}"
                             alt="Avatar">
                    </div>
                    <div>
                        <p class="field-label-sm" style="margin-bottom:0.2rem;">Current avatar</p>
                        @if($profile && $profile->avatar_path)
                            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#5A5248;">{{ basename($profile->avatar_path) }}</p>
                        @else
                            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#B0A99F;">Using default profile image</p>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="lt-label" style="margin-bottom:0.4rem;">
                        Upload new avatar
                        <span style="font-weight:400;color:#B0A99F;text-transform:none;letter-spacing:0;">(JPG, PNG, SVG)</span>
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="lt-file"
                           onchange="document.getElementById('avatar-preview').src = URL.createObjectURL(this.files[0])">
                </div>
            </div>
        </div>

        {{-- CV --}}
        <div class="ps-panel">
            <div class="ps-panel-header">
                <span class="ps-panel-label">📄 Resume / CV</span>
                @if($profile && $profile->cv_path)
                    <span class="pill-ok">✓ Uploaded</span>
                @else
                    <span class="pill-warn">Missing</span>
                @endif
            </div>
            <div class="ps-body">
@php
                    $adminCvUrl = '';
                    $adminCvEmbedUrl = '';
                    if ($profile && $profile->cv_path) {
                        $adminCvUrl = \Illuminate\Support\Str::startsWith($profile->cv_path, 'http')
                            ? $profile->cv_path
                            : asset($profile->cv_path);
                        $adminCvEmbedUrl = 'https://docs.google.com/viewer?url=' . urlencode($adminCvUrl) . '&embedded=true';
                    }
                @endphp
                <div class="cv-box">
                    @if($adminCvEmbedUrl)
                        <iframe src="{{ $adminCvEmbedUrl }}" style="width:100%;height:100%;border:none;"></iframe>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.6rem;opacity:0.5;">
                            <svg style="width:2.5rem;height:2.5rem;color:#C4BDB2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#B0A99F;">No CV uploaded</span>
                        </div>
                    @endif
                </div>
                @if($adminCvUrl)
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <span style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#7A7267;">{{ basename($profile->cv_path) }}</span>
                        <a href="{{ $adminCvUrl }}" target="_blank"
                           style="font-family:'Space Mono',monospace;font-size:0.6rem;color:#6829AA;text-decoration:underline;">Open ↗</a>
                    </div>
                @endif
                <div>
                    <label class="lt-label" style="margin-bottom:0.4rem;">
                        Upload new CV
                        <span style="font-weight:400;color:#B0A99F;text-transform:none;letter-spacing:0;">(PDF)</span>
                    </label>
                    <input type="file" name="cv" id="cv" accept="application/pdf" class="lt-file">
                </div>
            </div>
        </div>

    </div>

    {{-- Save --}}
    <div style="display:flex;justify-content:flex-end;padding-bottom:0.5rem;">
        <button type="submit" class="lt-btn-save">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Save Profile Settings
        </button>
    </div>

</form>

@endsection
