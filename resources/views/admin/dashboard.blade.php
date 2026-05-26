@extends('admin.layout')

@section('admin_content')

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="cms-page-title">Dashboard</h1>
        <p class="cms-page-subtitle">Site overview & visitor activity at a glance</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

        <!-- Projects -->
        <a href="{{ route('admin.projects.index') }}" class="cms-card p-5 flex items-center gap-4 hover:border-[rgba(77,217,240,0.3)] transition-all duration-200 group" style="text-decoration:none; display:flex;">
            <div style="width:48px;height:48px;border-radius:0.75rem;background:rgba(77,217,240,0.1);border:1px solid rgba(77,217,240,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:22px;height:22px;color:#4dd9f0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">Outputs / Projects</p>
                <p style="font-size:1.75rem;font-weight:800;color:#fff;font-family:'Outfit',sans-serif;line-height:1;">{{ $projectsCount }}</p>
            </div>
            <svg style="width:14px;height:14px;color:rgba(255,255,255,0.2);margin-left:auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <!-- Skills -->
        <a href="{{ route('admin.skills.index') }}" class="cms-card p-5 flex items-center gap-4 hover:border-[rgba(77,217,240,0.3)] transition-all duration-200 group" style="text-decoration:none; display:flex;">
            <div style="width:48px;height:48px;border-radius:0.75rem;background:rgba(255,107,0,0.1);border:1px solid rgba(255,107,0,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:22px;height:22px;color:#ff6b00;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </div>
            <div>
                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">Skills</p>
                <p style="font-size:1.75rem;font-weight:800;color:#fff;font-family:'Outfit',sans-serif;line-height:1;">{{ $skillsCount }}</p>
            </div>
            <svg style="width:14px;height:14px;color:rgba(255,255,255,0.2);margin-left:auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <!-- Unread Messages -->
        <a href="{{ route('admin.messages.index') }}" class="cms-card p-5 flex items-center gap-4 hover:border-[rgba(77,217,240,0.3)] transition-all duration-200 group" style="text-decoration:none; display:flex;">
            <div style="width:48px;height:48px;border-radius:0.75rem;background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:22px;height:22px;color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">Unread Messages</p>
                <p style="font-size:1.75rem;font-weight:800;color:#fff;font-family:'Outfit',sans-serif;line-height:1;">{{ $unreadMessagesCount }}</p>
            </div>
            @if($unreadMessagesCount > 0)
                <span class="cms-badge cms-badge-orange" style="margin-left:auto;">New</span>
            @else
                <svg style="width:14px;height:14px;color:rgba(255,255,255,0.2);margin-left:auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
        </a>
    </div>

    <!-- Quick Links Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-8">
        @php
        $quickLinks = [
            ['label' => 'Hero & Profile', 'route' => 'admin.profile', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'badge' => null],
            ['label' => 'Intro Slides', 'route' => 'admin.intro_slides.index', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'badge' => null],
            ['label' => 'Skills & Tools', 'route' => 'admin.skills.index', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'badge' => null],
            ['label' => 'Achievements', 'route' => 'admin.achievements.index', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'badge' => null],
            ['label' => 'Work Experience', 'route' => 'admin.experiences.index', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'badge' => null],
            ['label' => 'Visitor Inbox', 'route' => 'admin.messages.index', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'badge' => $unreadMessagesCount > 0 ? $unreadMessagesCount : null],
        ];
        @endphp
        @foreach($quickLinks as $link)
            <a href="{{ route($link['route']) }}"
               style="text-decoration:none; display:flex; flex-direction:column; gap:0.75rem; padding:1rem; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); border-radius:0.875rem; transition:all 0.2s ease; position:relative;"
               onmouseover="this.style.background='rgba(77,217,240,0.06)'; this.style.borderColor='rgba(77,217,240,0.2)';"
               onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='rgba(255,255,255,0.07)';">
                <svg style="width:18px;height:18px;color:rgba(77,217,240,0.7);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                </svg>
                <span style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;color:rgba(255,255,255,0.65);">{{ $link['label'] }}</span>
                @if($link['badge'])
                    <span class="cms-badge cms-badge-orange" style="position:absolute;top:0.6rem;right:0.75rem;">{{ $link['badge'] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <!-- Recent Messages -->
    <div class="cms-card overflow-hidden">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:space-between;">
            <h2 style="font-family:'Outfit',sans-serif; font-size:0.9rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#4dd9f0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Recent Visitor Messages
            </h2>
            <a href="{{ route('admin.messages.index') }}" style="font-size:0.7rem;font-family:'Space Mono',monospace;color:#4dd9f0;text-decoration:none;text-transform:uppercase;letter-spacing:0.08em;">
                View All &rarr;
            </a>
        </div>

        @forelse($recentMessages as $msg)
            <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.04); display:flex; align-items:center; justify-content:space-between; gap:1rem; transition:background 0.15s ease;"
                 onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                 onmouseout="this.style.background='transparent'">
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                        <span style="font-size:0.875rem; font-weight:600; color:rgba(255,255,255,0.85); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">{{ $msg->name }}</span>
                        <span style="font-size:0.65rem; color:rgba(255,255,255,0.3); font-family:'Space Mono',monospace; white-space:nowrap;">{{ $msg->created_at->diffForHumans() }}</span>
                        @if(!$msg->is_read)
                            <span class="cms-badge cms-badge-orange">New</span>
                        @endif
                    </div>
                    <p style="font-size:0.75rem; color:rgba(255,255,255,0.35); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:400px;">
                        {{ $msg->subject ?? '(No Subject)' }} — {{ Str::limit($msg->message, 60) }}
                    </p>
                </div>
                <a href="{{ route('admin.messages.show', $msg->id) }}" class="cms-btn-secondary" style="white-space:nowrap; padding:0.45rem 0.875rem; font-size:0.7rem;">
                    View
                </a>
            </div>
        @empty
            <div style="padding:3rem; text-align:center;">
                <svg style="width:36px;height:36px;color:rgba(255,255,255,0.1);margin:0 auto 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p style="font-size:0.8rem; color:rgba(255,255,255,0.25); font-family:'Space Mono',monospace;">No messages yet.</p>
            </div>
        @endforelse
    </div>

@endsection
