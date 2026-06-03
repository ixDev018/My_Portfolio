<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Panel | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Single&family=Jaro:opsz@6..72&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        :root {
            --brand-purple:   #512b81;
            --brand-cyan:     #4dd9f0;
            --brand-orange:   #ff6b00;
            --brand-cream:    #FAF7E6;
            --sidebar-bg:     #2a1550;
            --sidebar-dark:   #1d0e3a;
            --body-bg:        #0f0a1a; /* Can change this later to cream if needed based on dashboard mockup */
            --card-bg:        rgba(255,255,255,0.04);
            --border-color:   rgba(255,255,255,0.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F4F1E1; /* From dashboard reference mockup */
            color: #1a1a1a;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar */
        .cms-sidebar {
            background: linear-gradient(270deg, #57347D 0%, #6829AA 50.48%, #57347D 100%);
            border: 1px solid #000000;
            border-radius: 16px;
            width: 260px;
            height: calc(100vh - 2rem);
            margin: 1rem;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .cms-sidebar-brand {
            padding: 3rem 2.5rem 2.5rem; /* Match left padding with nav links */
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Left align everything */
            text-align: left;
        }

        .cms-sidebar-brand .brand-name {
            font-family: 'Jaro', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 34px;
            line-height: 37px;
            color: #FF851B;
            -webkit-text-stroke: 0.5px #FFFFFF;
            text-shadow: -2px 3px 0px #000000;
            white-space: nowrap;
            margin-bottom: 0.2rem;
            text-transform: uppercase;
            text-align: left;
            letter-spacing: 0.08em;
        }

        .cms-sidebar-brand .brand-sub {
            font-family: 'Bitcount', 'Space Mono', monospace;
            font-size: 9px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 700;
            margin-left: 2px; /* tiny adjustment to visually align with the slanted 'I' in Jaro */
        }

        /* Nav links */
        .cms-nav-section {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        nav > .cms-nav-section:first-child {
            margin-top: 3.5rem; /* pushes the nav group down from the logo */
        }

        .cms-nav-link {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.8rem; /* Inactive size */
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            transition: all 0.2s ease;
            text-transform: uppercase;
            padding: 0 2.5rem;
            display: flex;
            align-items: center;
            transform-origin: left;
        }

        .cms-nav-link:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .cms-nav-link.active {
            color: #79ECFF;
            font-size: 1.05rem; /* Noticeably enlarged active size */
            animation: growActiveTab 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes growActiveTab {
            from {
                font-size: 0.8rem;
                color: rgba(255, 255, 255, 0.4);
            }
            to {
                font-size: 1.05rem;
                color: #79ECFF;
            }
        }

        /* Sidebar footer */
        .cms-sidebar-bottom {
            margin-top: auto;
            padding: 0 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cms-btn-logout-new {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.6rem 1rem;
            background: transparent;
            border: 1px solid #ffffff;
            border-radius: 100px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .cms-btn-logout-new:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Main content area */
        .cms-main {
            flex: 1;
            overflow-y: auto;
            max-height: 100vh;
            padding: 2rem 2.5rem;
        }

        /* Temporary override for content area to be dark again until you rebuild the main dashboard layout */
        .cms-main-inner-bg {
            background: var(--body-bg);
            border-radius: 1rem;
            padding: 2rem;
            min-height: 100%;
            color: #e2e8f0;
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
            color: inherit;
            letter-spacing: -0.02em;
        }

        .cms-page-subtitle {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: #000000;
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

        /* ─── 3-dot dropdown (Global Admin) ──────────────────────── */
        .cms-dots-wrap { position: relative; display: inline-block; }
        .cms-dots-btn {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 0.4rem;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            color: #9B9589;
            transition: all 0.15s;
        }
        .cms-dots-btn:hover {
            background: #F0EDE6;
            border-color: #D8D4C8;
            color: #2c2826;
        }
        .cms-dots-btn.open {
            background: #EEE6FF;
            border-color: #C4A8F0;
            color: #6829AA;
        }
        .cms-dropdown {
            position: absolute;
            right: 0; top: calc(100% + 4px);
            z-index: 50;
            background: #fff;
            border: 1px solid #D8D4C8;
            border-radius: 0.6rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 140px;
            overflow: hidden;
        }
        .cms-dropdown a, .cms-dropdown button {
            display: flex; align-items: center; gap: 0.5rem;
            width: 100%; text-align: left;
            padding: 0.55rem 0.85rem;
            font-size: 0.8rem; font-weight: 600;
            font-family: 'Outfit', sans-serif;
            color: #2c2826; background: transparent;
            border: none; cursor: pointer;
            text-decoration: none;
            transition: background 0.12s;
        }
        .cms-dropdown a:hover { background: #F7F5EE; }
        .cms-dropdown button:hover { background: #FFF1F1; color: #dc2626; }
        .cms-dropdown .cms-dd-divider {
            height: 1px; background: #F0EDE6; margin: 0.25rem 0;
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

        /* ── Success Modal ── */
        @keyframes successModalIn {
            0%   { opacity: 0; transform: scale(0.7) translateY(30px); }
            65%  { opacity: 1; transform: scale(1.04) translateY(-4px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes successModalOut {
            0%   { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.85) translateY(20px); }
        }
        @keyframes checkDraw {
            0%   { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes circlePop {
            0%   { transform: scale(0); opacity: 0; }
            60%  { transform: scale(1.12); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes progressShrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
        @keyframes rippleOut {
            0%   { transform: scale(1); opacity: 0.35; }
            100% { transform: scale(2.4); opacity: 0; }
        }
        .success-modal-card {
            animation: successModalIn 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .success-modal-card.hiding {
            animation: successModalOut 0.3s ease forwards;
        }
        .success-check-circle {
            animation: circlePop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
        }
        .success-check-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkDraw 0.45s ease 0.4s forwards;
        }
        .success-ripple {
            animation: rippleOut 1s ease 0.3s forwards;
        }
        .success-progress-bar {
            animation: progressShrink linear forwards;
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <header class="md:hidden w-full px-5 py-4 flex justify-between items-center z-30 sticky top-0"
            style="background: #F4F1E1;">
        <div class="flex items-center gap-2">
            <span style="font-family: 'Jaro', sans-serif; font-size: 1.5rem; color: #FF9E2C; -webkit-text-stroke: 1px #fff;">IX-MEDIA</span>
        </div>
        <button @click="sidebarOpen = true" class="text-gray-800 hover:text-black p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="brand-name">IX-MEDIA</div>
                <div class="brand-sub">Portfolio Manager</div>
                <button @click="sidebarOpen = false" class="md:hidden absolute top-4 right-4 text-white/60 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-grow overflow-y-auto py-2 flex flex-col">
                <div class="cms-nav-section">
                    <a href="{{ route('admin.dashboard') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.profile') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        Hero
                    </a>

                    <a href="{{ route('admin.intro_slides.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.intro_slides.*') ? 'active' : '' }}">
                        Introduction
                    </a>

                    <a href="{{ route('admin.skills.index') }}" 
                       class="cms-nav-link {{ request()->routeIs('admin.skills.*', 'admin.tools.*') ? 'active' : '' }}">
                        Skills & Tools
                    </a>

                    <a href="{{ route('admin.projects.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        Outputs
                    </a>

                    <a href="{{ route('admin.achievements.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.achievements.*') ? 'active' : '' }}">
                        Achievements
                    </a>

                    <a href="{{ route('admin.experiences.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                        Experience
                    </a>
                </div>

                <div class="cms-nav-section mt-auto">
                    <a href="#" class="cms-nav-link">
                        Activity Logs
                    </a>

                    <a href="{{ route('admin.profile_settings') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.profile_settings') ? 'active' : '' }}">
                        Profile Settings
                    </a>

                    <a href="{{ route('admin.messages.index') }}"
                       class="cms-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        Visitor Inbox
                    </a>
                </div>
            </nav>

            <!-- Footer -->
            <div class="cms-sidebar-bottom">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="cms-btn-logout-new">
                        Log-Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <main class="cms-main flex-1">

            {{-- ── Success / Error Popup Modal ── --}}
            @if(session('success') || session('error'))
                @php
                    $isSuccess = session('success');
                    $msg = session('success') ?? session('error');
                @endphp
                <div
                    id="cms-success-modal"
                    x-data="{
                        show: true,
                        hiding: false,
                        dismiss() {
                            this.hiding = true;
                            setTimeout(() => this.show = false, 300);
                        }
                    }"
                    x-show="show"
                    x-init="setTimeout(() => dismiss(), 3500)"
                    style="display:none;"
                    class="fixed inset-0 z-[999] flex items-center justify-center"
                    @click.self="dismiss()"
                >
                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

                    {{-- Modal Card --}}
                    <div class="success-modal-card relative z-10 flex flex-col items-center text-center"
                         :class="hiding ? 'hiding' : ''"
                         style="
                            background: #ffffff;
                            border-radius: 24px;
                            padding: 3rem 3.5rem 2.5rem;
                            min-width: 320px;
                            max-width: 400px;
                            box-shadow: 0 32px 80px rgba(0,0,0,0.22), 0 0 0 1px rgba(255,255,255,0.12);
                         "
                    >
                        {{-- Animated Check / Error Icon --}}
                        <div class="relative mb-6" style="width:88px; height:88px;">
                            {{-- Ripple ring --}}
                            <div class="success-ripple absolute inset-0 rounded-full" style="background: {{ $isSuccess ? 'rgba(16,185,129,0.18)' : 'rgba(239,68,68,0.18)' }};"></div>

                            {{-- Circle --}}
                            <div class="success-check-circle absolute inset-0 rounded-full flex items-center justify-center"
                                 style="background: {{ $isSuccess ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }}; box-shadow: 0 8px 24px {{ $isSuccess ? 'rgba(16,185,129,0.4)' : 'rgba(239,68,68,0.4)' }};">
                                @if($isSuccess)
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path class="success-check-path" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path class="success-check-path" d="M18 6L6 18M6 6l12 12"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        {{-- Title --}}
                        <h2 style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:800; color:#111827; margin-bottom:0.5rem; letter-spacing:-0.02em;">
                            {{ $isSuccess ? 'Done!' : 'Oops!' }}
                        </h2>

                        {{-- Message --}}
                        <p style="font-family:'Inter',sans-serif; font-size:0.9rem; color:#6b7280; line-height:1.6; margin-bottom:2rem;">
                            {{ $msg }}
                        </p>

                        {{-- Dismiss button --}}
                        <button
                            @click="dismiss()"
                            style="
                                font-family:'Outfit',sans-serif;
                                font-weight:700;
                                font-size:0.8rem;
                                letter-spacing:0.06em;
                                text-transform:uppercase;
                                padding:0.65rem 2.5rem;
                                border-radius:100px;
                                border:none;
                                cursor:pointer;
                                transition: all 0.2s ease;
                                background: {{ $isSuccess ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }};
                                color: white;
                                box-shadow: 0 4px 14px {{ $isSuccess ? 'rgba(16,185,129,0.35)' : 'rgba(239,68,68,0.35)' }};
                            "
                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px {{ $isSuccess ? 'rgba(16,185,129,0.5)' : 'rgba(239,68,68,0.5)' }}'"
                            onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px {{ $isSuccess ? 'rgba(16,185,129,0.35)' : 'rgba(239,68,68,0.35)' }}'"
                        >
                            Got it
                        </button>

                        {{-- Auto-dismiss progress bar --}}
                        <div style="position:absolute; bottom:0; left:0; right:0; height:3px; background:rgba(0,0,0,0.06); border-radius:0 0 24px 24px; overflow:hidden;">
                            <div
                                class="success-progress-bar"
                                style="
                                    height:100%;
                                    background: {{ $isSuccess ? 'linear-gradient(90deg,#10b981,#34d399)' : 'linear-gradient(90deg,#ef4444,#f87171)' }};
                                    animation-duration: 3.5s;
                                    border-radius:0 0 24px 24px;
                                "
                            ></div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('admin_content')
        </main>

    </div>

</body>
</html>
