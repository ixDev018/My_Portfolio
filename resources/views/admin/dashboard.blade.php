@extends('admin.layout')

@section('admin_content')

    <!-- Dashboard Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Overview Dashboard</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Quick statistics and inbox activity at a glance</p>
    </div>

    <!-- Statistics Panel Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        <!-- Projects Count Box -->
        <div class="bg-slate-900 border border-slate-850 p-6 rounded-2xl flex items-center gap-5 shadow-lg relative overflow-hidden group">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center shadow-lg shadow-cyan-950/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-mono uppercase tracking-wider">Total Projects</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $projectsCount }}</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="absolute inset-0 bg-transparent" title="Manage Projects"></a>
        </div>

        <!-- Skills Count Box -->
        <div class="bg-slate-900 border border-slate-850 p-6 rounded-2xl flex items-center gap-5 shadow-lg relative overflow-hidden group">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shadow-lg shadow-indigo-950/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-mono uppercase tracking-wider">Total Skills</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $skillsCount }}</p>
            </div>
            <a href="{{ route('admin.skills.index') }}" class="absolute inset-0 bg-transparent" title="Manage Skills"></a>
        </div>

        <!-- Unread Messages Box -->
        <div class="bg-slate-900 border border-slate-850 p-6 rounded-2xl flex items-center gap-5 shadow-lg relative overflow-hidden group">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shadow-lg shadow-amber-950/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-mono uppercase tracking-wider">Unread Inbox</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $unreadMessagesCount }}</p>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="absolute inset-0 bg-transparent" title="Manage Messages"></a>
        </div>
    </div>

    <!-- Recent Messages Table/Inbox Panel -->
    <div class="bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-6 py-5 border-b border-slate-850 flex justify-between items-center bg-slate-900/60">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Recent Visitor Messages
            </h2>
            <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 transition-colors">
                View All Inbox &rarr;
            </a>
        </div>

        <div class="divide-y divide-slate-850/60">
            @forelse($recentMessages as $msg)
                <div class="px-6 py-4 hover:bg-slate-850/20 transition-colors duration-150 flex items-center justify-between gap-4">
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-200 truncate">{{ $msg->name }}</span>
                            <span class="text-[10px] text-slate-500 font-mono font-medium">{{ $msg->created_at->diffForHumans() }}</span>
                            
                            @if(!$msg->is_read)
                                <span class="px-2 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-bold uppercase tracking-wider">New</span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-xs truncate max-w-lg">{{ $msg->subject ?? '(No Subject Specified)' }} &ndash; {{ $msg->message }}</p>
                    </div>
                    
                    <div>
                        <a href="{{ route('admin.messages.show', $msg->id) }}" class="text-xs font-semibold px-4 py-2 bg-slate-950 border border-slate-800 text-slate-300 rounded-lg hover:border-slate-700 hover:text-white transition-all">
                            View details
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    <svg class="w-10 h-10 text-slate-650 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-sm font-medium">Your contact inbox is currently empty.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
