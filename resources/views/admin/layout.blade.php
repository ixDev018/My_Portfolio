<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Panel | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --brand-purple:   #512b81;
            --brand-cyan:     #4dd9f0;
            --brand-orange:   #ff6b00;
            --brand-cream:    #FAF7E6;
            --sidebar-bg:     #2a1550;
            --sidebar-dark:   #1d0e3a;
            --body-bg:        #0f0a1a;
            --card-bg:        rgba(255,255,255,0.04);
            --border-color:   rgba(255,255,255,0.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #e2e8f0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar */
        .cms-sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(77, 217, 240, 0.1);
            width: 260px;
            min-height: 100vh;
            flex-shrink: 0;
        }

        .cms-sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(77,217,240,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .cms-sidebar-brand .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--brand-cyan);
            box-shadow: 0 0 10px var(--brand-cyan), 0 0 20px rgba(77,217,240,0.4);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.85); }
        }

        .cms-sidebar-brand .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.04em;
            color: #fff;
        }

        .cms-sidebar-brand .brand-sub {
            font-family: 'Space Mono', monospace;
            font-size: 9px;
            color: var(--brand-cyan);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-top: 1px;
        }

        /* Nav links */
        .cms-nav-section {
            padding: 0.5rem 0;
        }

        .cms-nav-label {
            font-family: 'Space Mono', monospace;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: rgba(77,217,240,0.5);
            padding: 1rem 1.25rem 0.5rem;
        }

        .cms-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            margin: 0.1rem 0.75rem;
            border-radius: 0.6rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }

        .cms-nav-link:hover {
            color: #fff;
            background: rgba(77,217,240,0.08);
        }

        .cms-nav-link.active {
            color: var(--brand-cyan);
            background: rgba(77,217,240,0.12);
        }

        .cms-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 3px;
            background: var(--brand-cyan);
            border-radius: 0 2px 2px 0;
        }

        .cms-nav-link svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .cms-nav-link.active svg,
        .cms-nav-link:hover svg {
            opacity: 1;
        }

        /* Sidebar footer */
        .cms-sidebar-footer {
            padding: 1rem 0.75rem 1.5rem;
            border-top: 1px solid rgba(77,217,240,0.1);
        }

        .cms-btn-view {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6rem 1rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
            font-family: 'Space Mono', monospace;
            letter-spacing: 0.05em;
        }

        .cms-btn-view:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .cms-btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6rem 1rem;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 0.5rem;
            color: #f87171;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Space Mono', monospace;
            letter-spacing: 0.05em;
        }

        .cms-btn-logout:hover {
            background: rgba(239,68,68,0.15);
            color: #fca5a5;
        }

        /* Main content area */
        .cms-main {
            flex: 1;
            overflow-y: auto;
            max-height: 100vh;
            padding: 2rem 2.5rem;
        }

        /* Card styling */
        .cms-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            backdrop-filter: blur(10px);
        }

        /* Input styling */
        .cms-input {
            width: 100%;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.6rem;
            padding: 0.65rem 1rem;
            color: #e2e8f0;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .cms-input:focus {
            border-color: var(--brand-cyan);
            box-shadow: 0 0 0 3px rgba(77,217,240,0.12);
        }

        .cms-input::placeholder { color: rgba(255,255,255,0.2); }

        /* Label */
        .cms-label {
            display: block;
            font-size: 0.7rem;
            font-family: 'Space Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.4);
            margin-bottom: 0.4rem;
        }

        /* Primary button */
        .cms-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: var(--brand-orange);
            color: #fff;
            border: none;
            border-radius: 0.6rem;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.04em;
            text-decoration: none;
        }

        .cms-btn-primary:hover {
            background: #e55e00;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(255,107,0,0.35);
        }

        .cms-btn-primary:active { transform: translateY(0); }

        /* Secondary button */
        .cms-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.6rem;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
        }

        .cms-btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Danger button */
        .cms-btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Outfit', sans-serif;
        }

        .cms-btn-danger:hover {
            background: rgba(239,68,68,0.2);
            color: #fca5a5;
        }

        /* Section header */
        .cms-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .cms-page-subtitle {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: rgba(77,217,240,0.6);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0.25rem;
        }

        /* Toast */
        .cms-toast-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            color: #6ee7b7;
            border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .cms-toast-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #fca5a5;
            border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        /* Table */
        .cms-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cms-table th {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.35);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        .cms-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.875rem;
            color: rgba(255,255,255,0.75);
            vertical-align: middle;
        }

        .cms-table tr:last-child td { border-bottom: none; }

        .cms-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        /* Divider */
        .cms-divider {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 1.5rem 0;
        }

        /* Badge */
        .cms-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 100px;
            font-size: 0.65rem;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .cms-badge-cyan {
            background: rgba(77,217,240,0.12);
            color: var(--brand-cyan);
            border: 1px solid rgba(77,217,240,0.25);
        }

        .cms-badge-orange {
            background: rgba(255,107,0,0.12);
            color: var(--brand-orange);
            border: 1px solid rgba(255,107,0,0.25);
        }

        .cms-badge-purple {
            background: rgba(81,43,129,0.3);
            color: #c4b5fd;
            border: 1px solid rgba(81,43,129,0.5);
        }

        .cms-badge-green {
            background: rgba(16,185,129,0.12);
            color: #6ee7b7;
            border: 1px solid rgba(16,185,129,0.25);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(77,217,240,0.2); border-radius: 2px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(77,217,240,0.4); }

        /* Mobile sidebar overlay */
        .cms-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 39;
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <header class="md:hidden w-full px-5 py-4 flex justify-between items-center border-b z-30 sticky top-0"
            style="background: var(--sidebar-bg); border-color: rgba(77,217,240,0.1);">
        <div class="flex items-center gap-2">
            <div class="dot w-2 h-2 rounded-full" style="background: var(--brand-cyan); box-shadow: 0 0 8px var(--brand-cyan);"></div>
            <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1rem; color: #fff;">CMS Panel</span>
        </div>
        <button @click="sidebarOpen = true" class="text-white/60 hover:text-white p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </header>

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="cms-overlay md:hidden" style="display:none;"></div>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="cms-sidebar fixed md:static inset-y-0 left-0 z-40 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Brand -->
            <div class="cms-sidebar-brand">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="dot"></span>
                        <span class="brand-name">Admin CMS</span>
                    </div>
                    <span class="brand-sub">Portfolio Manager</span>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden ml-auto text-white/40 hover:text-white p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-grow overflow-y-auto py-2">

                <div class="cms-nav-section">
                    <div class="cms-nav-label">Overview</div>

                    <a href="{{ route('admin.dashboard') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="cms-nav-section">
                    <div class="cms-nav-label">Content Sections</div>

                    <a href="{{ route('admin.profile') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Hero & Profile
                    </a>

                    <a href="{{ route('admin.intro_slides.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.intro_slides.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Intro Slides
                    </a>

                    <a href="{{ route('admin.skills.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.skills.*') ? 'bg-indigo-500/10 text-indigo-400 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Manage Skills
                    </a>

                    <!-- Marquee Tools -->
                    <a href="{{ route('admin.tools.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tools.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                        Tools & Marquee
                    </a>

                    <a href="{{ route('admin.projects.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Outputs / Projects
                    </a>

                    <a href="{{ route('admin.achievements.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.achievements.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Achievements
                    </a>

                    <a href="{{ route('admin.experiences.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Work Experience
                    </a>
                </div>

                <div class="cms-nav-section">
                    <div class="cms-nav-label">Communication</div>

                    <a href="{{ route('admin.messages.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Visitor Inbox
                        @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                        @if($unread > 0)
                            <span class="ml-auto cms-badge cms-badge-orange">{{ $unread }}</span>
                        @endif
                    </a>
                </div>

            </nav>

            <!-- Footer -->
            <div class="cms-sidebar-footer">
                <a href="{{ route('portfolio.index') }}" target="_blank" class="cms-btn-view">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View Live Site
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="cms-btn-logout w-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <main class="cms-main flex-1">

            <!-- Success toast -->
            @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4500)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="cms-toast-success">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="opacity-60 hover:opacity-100 text-lg leading-none">&times;</button>
                </div>
            @endif

            <!-- Error toast -->
            @if(session('error'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition
                     class="cms-toast-error">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="opacity-60 hover:opacity-100 text-lg">&times;</button>
                </div>
            @endif

            @yield('admin_content')
        </main>

    </div>

</body>
</html>
