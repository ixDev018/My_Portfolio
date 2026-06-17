@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }

    /* ── page header ── */
    .db-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: #1a1207; letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    /* ── hero banner ── */
    .db-hero {
        width: 100%;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, #6829AA 0%, #3a1860 60%, #1d0e3a 100%);
        margin-bottom: 1.25rem;
    }
    .db-hero-cover {
        width: 100%; height: 100px;
        object-fit: cover; display: block;
        opacity: 0.35;
    }
    .db-hero-cover-ph {
        width: 100%; height: 100px;
        background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 100%);
    }
    .db-hero-info {
        display: flex; align-items: center; gap: 1.25rem;
        padding: 0 1.75rem 1.5rem;
        margin-top: -38px;
        position: relative; z-index: 1;
    }
    .db-avatar {
        width: 76px; height: 76px;
        border-radius: 50%; overflow: hidden;
        border: 3px solid #EDEAE0;
        background: #D8D4C8; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .db-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .db-hero-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: #fff; letter-spacing: -0.01em;
        line-height: 1.2;
    }
    .db-hero-sub {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: rgba(255,255,255,0.5);
        margin-top: 0.2rem;
    }

    /* ── grid shell ── */
    .db-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 960px) {
        .db-grid { grid-template-columns: 1fr; }
    }

    /* ── card base ── */
    .db-card {
        background: #fff;
        border: 1px solid #D8D4C8;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .db-card-header {
        padding: 0.75rem 1.15rem;
        border-bottom: 1px solid #E2DDD3;
        background: #F7F5EE;
        display: flex; align-items: center; justify-content: space-between;
    }
    .db-card-title {
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: #1a1207;
    }

    /* ── profile views card ── */
    .db-views-body {
        padding: 1.5rem 1.25rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .db-views-stat { text-align: center; }
    .db-views-stat .num {
        font-family: 'Outfit', sans-serif;
        font-size: 3rem; font-weight: 800;
        color: #1a1207; line-height: 1;
    }
    .db-views-stat .label {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: #9B9589;
        margin-top: 0.4rem;
    }
    .db-views-divider {
        position: absolute; top: 15%; bottom: 15%;
        left: 50%; width: 1px;
        background: #E2DDD3;
    }

    /* ── bottom stats row ── */
    .db-stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }
    .db-stat-mini {
        background: #fff; border: 1px solid #D8D4C8;
        border-radius: 1rem; padding: 1.1rem 1.15rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
    }
    .db-stat-mini-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: #1a1207; margin-bottom: 0.75rem;
    }
    .db-stat-mini .num {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem; font-weight: 800;
        color: #1a1207; line-height: 1;
    }
    .db-stat-mini .sub {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem; color: #9B9589;
        text-transform: uppercase; letter-spacing: 0.08em;
        margin-top: auto; padding-top: 0.5rem;
    }
    .db-see-details {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.35rem 0.85rem;
        background: #F7F5EE; border: 1px solid #D8D4C8;
        border-radius: 100px;
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #5A5248; text-decoration: none;
        transition: all 0.15s; cursor: pointer;
        margin-top: auto;
    }
    .db-see-details:hover {
        background: #EEE6FF; border-color: #C4A8F0; color: #6829AA;
    }

    /* ── inbox card ── */
    .db-inbox-counts {
        display: flex; align-items: center; gap: 0.75rem;
    }
    .db-inbox-pill {
        font-family: 'Space Mono', monospace;
        font-size: 0.56rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.07em;
    }
    .db-inbox-pill .val { color: #1a1207; }
    .db-inbox-pill .lbl { color: #9B9589; }

    /* message rows */
    .db-msg-row {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 1.15rem;
        border-bottom: 1px solid #F0EDE6;
        transition: background 0.12s;
    }
    .db-msg-row:last-child { border-bottom: none; }
    .db-msg-row:hover { background: #F7F5EE; }
    .db-msg-row.unread {
        background: #FDFBFF;
    }
    .db-msg-row.unread:hover { background: #F5EEFF; }
    .db-msg-sender {
        font-size: 0.78rem; font-weight: 600; color: #1a1207;
        white-space: nowrap; flex-shrink: 0;
        max-width: 120px; overflow: hidden; text-overflow: ellipsis;
    }
    .db-msg-sep {
        color: #D8D4C8; font-size: 0.7rem; flex-shrink: 0;
    }
    .db-msg-preview {
        font-size: 0.72rem; color: #9B9589;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        flex: 1; min-width: 0;
    }
    .db-msg-time {
        font-family: 'Space Mono', monospace;
        font-size: 0.56rem; color: #B0A99F;
        white-space: nowrap; flex-shrink: 0;
    }

    /* see more button */
    .db-see-more {
        display: flex; align-items: center; justify-content: center;
        padding: 0.65rem;
        background: linear-gradient(180deg, #F7F5EE 0%, #E8E4D8 100%);
        border-top: 1px solid #E2DDD3;
        text-decoration: none;
        transition: background 0.15s;
    }
    .db-see-more:hover { background: #EEE6FF; }
    .db-see-more span {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: #6829AA;
        padding: 0.3rem 1.25rem;
        background: #fff; border: 1px solid #D8D4C8;
        border-radius: 100px;
        transition: all 0.15s;
    }
    .db-see-more:hover span {
        background: #6829AA; color: #fff; border-color: #6829AA;
    }

    /* unread dot */
    .db-unread-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #6829AA; flex-shrink: 0;
    }
</style>

{{-- ═══ Title ═══ --}}
<h1 class="db-title">Dashboard</h1>

{{-- ═══ Hero Banner ═══ --}}
<div class="db-hero">
    {{-- Cover area --}}
    <div class="db-hero-cover-ph"></div>

    {{-- Avatar + Name --}}
    <div class="db-hero-info">
        <div class="db-avatar">
            @if($profile && $profile->avatar_path)
                <img src="{{ Str::startsWith($profile->avatar_path, 'http') ? $profile->avatar_path : ((Str::startsWith($profile->avatar_path, 'images/') || Str::startsWith($profile->avatar_path, 'videos/')) ? asset($profile->avatar_path) : Storage::url($profile->avatar_path)) }}" alt="Avatar">
            @else
                <img src="{{ asset('images/intro/profile.png') }}" alt="Avatar">
            @endif
        </div>
        <div>
            <p class="db-hero-name">{{ $profile->hero_title ?? 'Your Name' }}</p>
            <p class="db-hero-sub">{{ $profile->hero_subtitle ?? 'Set your subtitle in Hero settings' }}</p>
        </div>
    </div>
</div>

{{-- ═══ Main Grid ═══ --}}
<div class="db-grid">

    {{-- ── LEFT Column ── --}}
    <div>
        {{-- Total Profile Views --}}
        <div class="db-card">
            <div class="db-card-header">
                <span class="db-card-title">Total Profile Views</span>
            </div>
            <div class="db-views-body" style="position:relative;">
                <div class="db-views-stat">
                    <p class="num">0</p>
                    <p class="label">This Week</p>
                </div>
                <div class="db-views-divider"></div>
                <div class="db-views-stat">
                    <p class="num">{{ $totalMessagesCount + $projectsCount }}</p>
                    <p class="label">Overall</p>
                </div>
            </div>
        </div>

        {{-- Storage consumption indicator --}}
        <div class="db-card" style="margin-top: 1.25rem;">
            <div class="db-card-header">
                <span class="db-card-title">Storage Consumption</span>
                <span class="db-inbox-pill">
                    <span class="lbl">Limit:</span>
                    <span class="val">1.0 GB</span>
                </span>
            </div>
            <div style="padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-family:'Space Mono', monospace; font-size: 0.65rem; font-weight: 700; color: #1a1207; text-transform: uppercase;">
                        Total Used: {{ number_format($totalSizeBytes / (1024 * 1024), 2) }} MB
                    </span>
                    <span style="font-family:'Space Mono', monospace; font-size: 0.65rem; font-weight: 700; color: #6829AA;">
                        {{ $usagePercent }}%
                    </span>
                </div>
                
                {{-- Segmented Progress Bar --}}
                <div style="width: 100%; height: 10px; background: #EEE6FF; border-radius: 100px; overflow: hidden; margin-bottom: 1.25rem; display: flex;">
                    @if($totalSizeBytes > 0)
                        @if($dbPercent > 0)<div style="width: {{ $dbPercent }}%; background: #6829AA;" title="Database"></div>@endif
                        @if($imgPercent > 0)<div style="width: {{ $imgPercent }}%; background: #FF851B;" title="Images"></div>@endif
                        @if($vidPercent > 0)<div style="width: {{ $vidPercent }}%; background: #4dd9f0;" title="Videos"></div>@endif
                        @if($docPercent > 0)<div style="width: {{ $docPercent }}%; background: #F43F5E;" title="Documents"></div>@endif
                        @if($othPercent > 0)<div style="width: {{ $othPercent }}%; background: #9B9589;" title="Other"></div>@endif
                    @endif
                </div>

                {{-- Granulated Details List --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem; border-t: 1px solid #F0EDE6; padding-top: 1rem;">
                    
                    {{-- Database --}}
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #6829AA;"></span>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 600; color: #1a1207;">Database (SQLite)</span>
                        </div>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.7rem; color: #5A5248;">{{ number_format($dbSizeBytes / (1024 * 1024), 2) }} MB</span>
                    </div>

                    {{-- Images --}}
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #FF851B;"></span>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 600; color: #1a1207;">Images</span>
                        </div>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.7rem; color: #5A5248;">{{ number_format($mediaBreakdown['images'] / (1024 * 1024), 2) }} MB</span>
                    </div>

                    {{-- Videos --}}
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #4dd9f0;"></span>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 600; color: #1a1207;">Videos</span>
                        </div>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.7rem; color: #5A5248;">{{ number_format($mediaBreakdown['videos'] / (1024 * 1024), 2) }} MB</span>
                    </div>

                    {{-- Documents --}}
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #F43F5E;"></span>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 600; color: #1a1207;">Documents & CVs</span>
                        </div>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.7rem; color: #5A5248;">{{ number_format($mediaBreakdown['documents'] / (1024 * 1024), 2) }} MB</span>
                    </div>

                    {{-- Other --}}
                    @if($mediaBreakdown['other'] > 0)
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #9B9589;"></span>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 600; color: #1a1207;">Other Files</span>
                        </div>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.7rem; color: #5A5248;">{{ number_format($mediaBreakdown['other'] / (1024 * 1024), 2) }} MB</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bottom mini-stats row --}}
        <div class="db-stats-row">
            {{-- CV Downloads --}}
            <div class="db-stat-mini">
                <p class="db-stat-mini-label">CV Downloads</p>
                <p class="num">0</p>
                <p class="sub">
                    @if($profile && $profile->cv_path)
                        CV uploaded
                    @else
                        No CV uploaded
                    @endif
                </p>
            </div>

            {{-- Most Viewed Project --}}
            <div class="db-stat-mini">
                <p class="db-stat-mini-label">Most Viewed Project</p>
                @if($mostViewedProject)
                    <p style="font-size:0.82rem;font-weight:600;color:#1a1207;margin-bottom:0.35rem;line-height:1.3;">
                        {{ Str::limit($mostViewedProject->title, 28) }}
                    </p>
                @else
                    <p style="font-size:0.82rem;color:#B0A99F;font-style:italic;margin-bottom:0.35rem;">No Data yet</p>
                @endif
                <a href="{{ route('admin.projects.index') }}" class="db-see-details">
                    See Details
                    <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ── RIGHT Column: Inbox ── --}}
    <div class="db-card" style="display:flex;flex-direction:column;">
        {{-- Header --}}
        <div class="db-card-header">
            <span class="db-card-title">Inbox</span>
            <div class="db-inbox-counts">
                <span class="db-inbox-pill">
                    <span class="lbl">Unread:</span>
                    <span class="val">{{ $unreadMessagesCount }}</span>
                </span>
                <span class="db-inbox-pill">
                    <span class="lbl">Read:</span>
                    <span class="val">{{ $readMessagesCount }}</span>
                </span>
            </div>
        </div>

        {{-- Message rows --}}
        <div style="flex:1;overflow-y:auto;">
            @forelse($recentMessages as $msg)
                <a href="{{ route('admin.messages.show', $msg->id) }}" class="db-msg-row {{ !$msg->is_read ? 'unread' : '' }}" style="text-decoration:none;">
                    @if(!$msg->is_read)
                        <span class="db-unread-dot"></span>
                    @endif
                    <span class="db-msg-sender">{{ $msg->name }}</span>
                    <span class="db-msg-sep">—</span>
                    <span class="db-msg-preview">{{ $msg->subject ?? Str::limit($msg->message, 40) }}</span>
                    <span class="db-msg-time">{{ $msg->created_at->format('g:iA') }}</span>
                </a>
            @empty
                <div style="padding:2.5rem 1rem;text-align:center;">
                    <svg style="width:2rem;height:2rem;color:#D8D4C8;margin:0 auto 0.6rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p style="font-family:'Space Mono',monospace;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No messages yet</p>
                </div>
            @endforelse
        </div>

        {{-- See More footer --}}
        <a href="{{ route('admin.messages.index') }}" class="db-see-more">
            <span>See More</span>
        </a>
    </div>

</div>

@endsection
