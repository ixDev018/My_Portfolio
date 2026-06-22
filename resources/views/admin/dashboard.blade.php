@extends('admin.layout')

@section('admin_content')

<style>
    /* ─── Dashboard-only overrides ──────────────────────────── */
    .cms-main {
        background: transparent !important;
        color: #1a1207 !important;
        padding: 1.5rem 2.5rem !important;
        max-height: 100vh !important;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
    }

    /* ─── Typography helpers ─────────────────────────────────── */
    .db-mono {
        font-family: 'Space Mono', monospace;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .db-outfit { font-family: 'Outfit', sans-serif; }

    /* ─── Layout wrappers ────────────────────────────────────── */
    .db-header-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .db-bento {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        grid-template-rows: auto auto 1fr;
        gap: 1rem;
        margin-top: 1rem;
        flex: 1;
        min-height: 0; /* allows flex children to shrink */
    }

    /* ─── Base glass card (Khaki Light Theme) ────────────────── */
    .db-card {
        background: rgba(255,255,255,0.4);
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 1rem;
        backdrop-filter: blur(16px);
        position: relative;
        overflow: hidden;
        transition: border-color 0.25s, box-shadow 0.25s;
        display: flex;
        flex-direction: column;
    }
    .db-card:hover {
        border-color: rgba(104,41,170,0.25);
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .db-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.6) 0%, transparent 100%);
        pointer-events: none;
    }

    /* ─── Card header ────────────────────────────────────────── */
    .db-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 1.25rem 0;
        flex-shrink: 0;
        z-index: 2;
    }
    .db-card-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(26,18,7,0.5);
    }
    .db-live-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #6829AA;
        box-shadow: 0 0 6px #6829AA;
        animation: db-pulse 2s ease-in-out infinite;
    }
    @keyframes db-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.6; transform: scale(0.85); }
    }

    /* ─── Stat number ─────────────────────────────────────────── */
    .db-stat-num {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        line-height: 1;
        color: #1a1207;
        letter-spacing: -0.03em;
        z-index: 2; position: relative;
    }
    .db-stat-num.xl  { font-size: clamp(2.5rem, 4vw, 4rem); }
    .db-stat-num.lg  { font-size: clamp(2rem, 3vw, 2.8rem); }
    .db-stat-num.md  { font-size: clamp(1.5rem, 2.5vw, 2.2rem); }
    
    .db-stat-sub {
        font-family: 'Space Mono', monospace;
        font-size: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(26,18,7,0.45);
        margin-top: 0.2rem;
        z-index: 2; position: relative;
    }

    /* ─── Hero banner ─────────────────────────────────────────── */
    .db-hero-card {
        grid-column: span 12;
        background: linear-gradient(135deg, #512b81 0%, #6829AA 100%);
        border: 1px solid rgba(104,41,170,0.4);
        border-radius: 1.2rem;
        padding: 2.5rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }
    .db-hero-card::after {
        content: '';
        position: absolute;
        top: -80px; right: -40px;
        width: 250px; height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,133,27,0.2) 0%, transparent 65%);
        pointer-events: none;
    }
    .db-hero-avatar {
        width: 72px; height: 72px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.3);
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        flex-shrink: 0;
    }
    .db-hero-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    .db-hero-sub {
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-top: 0.4rem;
    }
    .db-hero-stat-box {
        display: flex; align-items: center;
        background: rgba(0,0,0,0.15);
        border-radius: 1rem;
        padding: 0.8rem 0.6rem;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .db-hero-stat {
        text-align: center;
        padding: 0 1.5rem;
        border-left: 1px solid rgba(255,255,255,0.1);
    }
    .db-hero-stat:first-child { border-left: none; }
    .db-hero-stat .n {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
    }
    .db-hero-stat .l {
        font-family: 'Space Mono', monospace;
        font-size: 0.55rem;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    /* ─── Grid slot helpers ───────────────────────────────────── */
    .col-3  { grid-column: span 3; }
    .col-4  { grid-column: span 4; }
    .col-5  { grid-column: span 5; }
    .col-6  { grid-column: span 6; }
    .col-7  { grid-column: span 7; }
    .col-8  { grid-column: span 8; }
    .col-9  { grid-column: span 9; }
    .col-12 { grid-column: span 12; }

    /* ─── Stat card inner padding ─────────────────────────────── */
    .db-inner { padding: 0.8rem 1.25rem 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: center; z-index: 2; position: relative;}

    /* ─── Segmented bar ───────────────────────────────────────── */
    .db-seg-bar {
        width: 100%;
        height: 6px;
        border-radius: 100px;
        overflow: hidden;
        display: flex;
        margin: 0.5rem 0;
    }

    /* ─── Inbox rows ──────────────────────────────────────────── */
    .db-inbox-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        text-decoration: none;
        transition: background 0.15s;
        cursor: pointer;
    }
    .db-inbox-row:last-child { border-bottom: none; }
    .db-inbox-row:hover { background: rgba(104,41,170,0.04); }
    .db-inbox-row.unread { background: rgba(104,41,170,0.03); }
    
    .db-inbox-sender {
        font-size: 0.75rem;
        font-weight: 700;
        color: #1a1207;
        white-space: nowrap;
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 0;
    }
    .db-inbox-preview {
        font-size: 0.7rem;
        color: rgba(26,18,7,0.5);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }
    .db-inbox-time {
        font-family: 'Space Mono', monospace;
        font-size: 0.5rem;
        color: rgba(26,18,7,0.4);
        white-space: nowrap;
        flex-shrink: 0;
    }
    .db-unread-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #ff851b;
        flex-shrink: 0;
    }

    /* ─── Quick-action buttons ────────────────────────────────── */
    .db-quick-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.6rem;
        background: rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        color: rgba(26,18,7,0.8);
        text-decoration: none;
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }
    .db-quick-btn:hover {
        background: rgba(104,41,170,0.08);
        border-color: rgba(104,41,170,0.2);
        transform: translateX(3px);
    }
    .db-quick-btn-icon {
        width: 24px; height: 24px;
        border-radius: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ─── Chart container ─────────────────────────────────────── */
    .db-chart-wrap {
        padding: 0 1.25rem 0.75rem;
        flex: 1;
        position: relative;
        min-height: 0;
    }
</style>

{{-- ═══ Page Title ═══ --}}
<div class="db-header-wrap">
    <div>
        <h1 style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:900; color:#1a1207; letter-spacing:-0.02em; margin:0;">Dashboard</h1>
        <p class="db-mono" style="font-size:0.55rem; color:rgba(26,18,7,0.5); margin-top:0.15rem;">IX-Media Portfolio Manager</p>
    </div>
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <span class="db-live-dot"></span>
        <span class="db-mono" style="font-size:0.55rem; color:rgba(26,18,7,0.5);">Live Data</span>
    </div>
</div>

<div class="db-bento">

    {{-- ══ ROW 1: HERO BANNER ══ --}}
    <div class="db-hero-card">
        <div style="display:flex; align-items:center; gap:1.1rem; flex-shrink:0;">
            @if($profile && $profile->avatar_path)
                <img src="{{ Str::startsWith($profile->avatar_path, 'http') ? $profile->avatar_path : asset($profile->avatar_path) }}" alt="Avatar" class="db-hero-avatar">
            @else
                <div class="db-hero-avatar" style="background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center;">
                    <svg width="24" height="24" fill="none" stroke="rgba(255,255,255,0.5)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            @endif
            <div>
                <div class="db-hero-name">Brix Jorie F. Cura</div>
                <div class="db-hero-sub">System Administrator</div>
            </div>
        </div>

        <div class="db-hero-stat-box">
            <div class="db-hero-stat">
                <div class="n">{{ $projectsCount }}</div>
                <div class="l">Projects</div>
            </div>
            <div class="db-hero-stat">
                <div class="n">{{ $skillsCount }}</div>
                <div class="l">Skills</div>
            </div>
            <div class="db-hero-stat">
                <div class="n">{{ $socialClickCount }}</div>
                <div class="l">Soc Clicks</div>
            </div>
            <div class="db-hero-stat">
                <div class="n" style="color:{{ $unreadMessagesCount > 0 ? '#ff851b' : '#fff' }};">{{ $unreadMessagesCount }}</div>
                <div class="l">Unread</div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 2: MINI STATS ══ --}}
    {{-- Page Views --}}
    <div class="db-card col-3">
        <div class="db-card-header">
            <span class="db-card-label">Total Visits</span>
            <span class="db-live-dot" style="background:#10b981; box-shadow:0 0 6px #10b981;"></span>
        </div>
        <div class="db-inner">
            <p class="db-stat-num xl">{{ number_format($pageViewsCount) }}</p>
            @php $todayViews = \App\Models\Interaction::where('type','page_view')->whereDate('created_at', today())->count(); @endphp
            <div style="margin-top:0.5rem; display:flex; align-items:center; gap:0.4rem;">
                <span class="db-stat-sub" style="margin:0;">Today:</span>
                <span style="font-family:'Outfit',sans-serif; font-size:0.8rem; font-weight:800; color:#10b981;">+{{ $todayViews }}</span>
            </div>
        </div>
    </div>

    {{-- CV Downloads --}}
    <div class="db-card col-3">
        <div class="db-card-header">
            <span class="db-card-label">CV Downloads</span>
            <svg width="12" height="12" fill="none" stroke="#ff851b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="db-inner">
            <p class="db-stat-num xl" style="color:#ff851b;">{{ number_format($cvDownloadsCount) }}</p>
            <div style="margin-top:0.5rem;">
                <a href="{{ asset('Cura_BrixJorie_CV.pdf') }}" target="_blank"
                   style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#ff851b; text-decoration:none; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">
                    View CV →
                </a>
            </div>
        </div>
    </div>

    {{-- Project Opens --}}
    <div class="db-card col-3">
        <div class="db-card-header">
            <span class="db-card-label">Project Opens</span>
            <svg width="12" height="12" fill="none" stroke="#0a7fce" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <div class="db-inner">
            <p class="db-stat-num xl" style="color:#0a7fce;">{{ number_format($projectViewCount) }}</p>
            <div style="margin-top:0.3rem;">
                 @if($mostViewedProjectTitle)
                    <p style="font-family:'Space Mono',monospace; font-size:0.5rem; color:rgba(26,18,7,0.4); text-transform:uppercase; letter-spacing:0.08em;">Top: <strong style="color:#0a7fce;font-family:'Outfit',sans-serif;font-size:0.7rem;">{{ Str::limit($mostViewedProjectTitle, 15) }}</strong></p>
                 @else
                    <p class="db-stat-sub" style="margin:0;">previews opened</p>
                 @endif
            </div>
        </div>
    </div>

    {{-- Storage --}}
    <div class="db-card col-3">
        <div class="db-card-header">
            <span class="db-card-label">Storage Use</span>
            <span style="font-family:'Space Mono',monospace; font-size:0.55rem; font-weight:700; color:{{ $usagePercent > 80 ? '#ef4444' : '#6829AA' }};">{{ $usagePercent }}%</span>
        </div>
        <div class="db-inner">
            <p style="font-family:'Outfit',sans-serif; font-size:1.6rem; font-weight:800; color:#1a1207; margin:0;">
                {{ number_format($totalSizeBytes / (1024*1024), 1) }} <span style="font-size:0.8rem; font-weight:600; color:rgba(26,18,7,0.5);">MB</span>
            </p>
            <div class="db-seg-bar">
                @if($totalSizeBytes > 0)
                    @if($dbPercent > 0)<div style="width:{{ $dbPercent }}%; background:#6829AA;"></div>@endif
                    @if($imgPercent > 0)<div style="width:{{ $imgPercent }}%; background:#FF851B;"></div>@endif
                    @if($vidPercent > 0)<div style="width:{{ $vidPercent }}%; background:#0a7fce;"></div>@endif
                    @if($docPercent > 0)<div style="width:{{ $docPercent }}%; background:#F43F5E;"></div>@endif
                    @if($othPercent > 0)<div style="width:{{ $othPercent }}%; background:#9B9589;"></div>@endif
                @else
                    <div style="width:100%; background:rgba(0,0,0,0.06);"></div>
                @endif
            </div>
            <p style="font-family:'Space Mono',monospace; font-size:0.5rem; color:rgba(26,18,7,0.4); text-transform:uppercase;">/ 5 GB Limit</p>
        </div>
    </div>

    {{-- ══ ROW 3: CHART, QUICK ACTIONS, INBOX ══ --}}
    
    {{-- Chart --}}
    <div class="db-card col-6">
        <div class="db-card-header" style="padding-bottom:0.5rem;">
            <span class="db-card-label">Traffic — Last 30 Days</span>
        </div>
        <div class="db-chart-wrap">
            <canvas id="pageViewsChart"></canvas>
        </div>
    </div>

    {{-- Middle Column: Quick Actions + Engagement --}}
    <div class="col-3" style="display:flex; flex-direction:column; gap:1rem; min-height:0;">
        {{-- Quick Actions --}}
        <div class="db-card" style="flex:1; min-height:0;">
            <div class="db-card-header">
                <span class="db-card-label">Quick Actions</span>
            </div>
            <div class="db-inner" style="padding:0.75rem; display:flex; flex-direction:column; gap:0.4rem; justify-content: flex-start; overflow-y:auto;">
                @php
                    $actions = [
                        [route('admin.projects.create'), '#6829AA', 'M12 4v16m8-8H4', 'New Project'],
                        [route('admin.profile'),         '#ff851b', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'Edit Hero'],
                        [route('admin.skills.index'),    '#0a7fce', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'Skills & Tools'],
                        [route('admin.messages.index'),  '#F43F5E', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Visitor Inbox'],
                        [route('admin.achievements.index'), '#10b981', 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'Achievements'],
                        [route('admin.experiences.index'), '#a78bfa', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Experience'],
                    ];
                @endphp
                @foreach($actions as [$href, $color, $iconPath, $title])
                <a href="{{ $href }}" class="db-quick-btn">
                    <span class="db-quick-btn-icon" style="background:{{ $color }}22; border:1px solid {{ $color }}33;">
                        <svg width="12" height="12" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $iconPath }}"/></svg>
                    </span>
                    <span style="font-size:0.75rem; color:#1a1207;">{{ $title }}</span>
                    <svg style="margin-left:auto; flex-shrink:0; opacity:0.25; color:#1a1207;" width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Engagement Rate --}}
        <div class="db-card" style="flex-shrink:0;">
            <div class="db-card-header">
                <span class="db-card-label">Engagement Rate</span>
                <svg width="12" height="12" fill="none" stroke="#6829AA" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div class="db-inner">
                <div style="display:flex; align-items:baseline; gap:0.25rem;">
                    <p class="db-stat-num lg" style="color:#6829AA;">{{ number_format($engagementRate, 1) }}</p>
                    <span style="font-family:'Outfit',sans-serif; font-size:1.2rem; font-weight:800; color:#6829AA;">%</span>
                </div>
                <p class="db-stat-sub" style="margin-top:0.2rem;">of visitors interacted</p>
            </div>
        </div>
    </div>

    {{-- Inbox --}}
    <div class="db-card col-3">
        <div class="db-card-header">
            <span class="db-card-label">Visitor Inbox</span>
            <a href="{{ route('admin.messages.index') }}" style="font-family:'Space Mono',monospace; font-size:0.5rem; color:#6829AA; font-weight:700; text-decoration:none;">SEE ALL →</a>
        </div>
        <div style="flex:1; overflow-y:auto; margin-top:0.5rem; padding: 0 0.5rem 0.5rem;">
            @forelse($recentMessages->take(4) as $msg)
            <a href="{{ route('admin.messages.show', $msg->id) }}" class="db-inbox-row {{ !$msg->is_read ? 'unread' : '' }}">
                @if(!$msg->is_read)<span class="db-unread-dot"></span>@endif
                <div style="display:flex; flex-direction:column; overflow:hidden;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span class="db-inbox-sender">{{ $msg->name }}</span>
                        <span class="db-inbox-time">{{ $msg->created_at->format('M d') }}</span>
                    </div>
                    <span class="db-inbox-preview">{{ $msg->subject ?? Str::limit($msg->message, 30) }}</span>
                </div>
            </a>
            @empty
            <div style="padding:2rem; text-align:center;">
                <p class="db-mono" style="font-size:0.55rem; color:rgba(26,18,7,0.3);">No messages yet</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ═══ Chart.js ═══ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('pageViewsChart').getContext('2d');
        const labels = {!! $chartLabels !!};
        const data   = {!! $chartValues !!};

        // Subtle purple gradient for light theme
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0,   'rgba(104, 41, 170, 0.25)');
        gradient.addColorStop(1,   'rgba(104, 41, 170, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Page Views',
                    data,
                    borderColor: '#6829AA',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6829AA',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#6829AA',
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1207',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        titleFont: { family: "'Space Mono', monospace", size: 10 },
                        bodyFont:  { family: "'Outfit', sans-serif", size: 14, weight: '700' },
                        titleColor: 'rgba(255,255,255,0.6)',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' views'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { family: "'Space Mono', monospace", size: 9 },
                            color: 'rgba(26,18,7,0.4)',
                            maxTicksLimit: 12,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            font: { family: "'Space Mono', monospace", size: 9 },
                            color: 'rgba(26,18,7,0.4)',
                            precision: 0,
                            maxTicksLimit: 6,
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
