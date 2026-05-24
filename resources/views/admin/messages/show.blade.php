@extends('admin.layout')

@section('admin_content')

    <!-- Navigation Back Link -->
    <div class="mb-4">
        <a href="{{ route('admin.messages.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
            &larr; Back to Inbox
        </a>
    </div>

    <!-- Header -->
    <div class="mb-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Message Details</h1>
            <p class="text-sm text-slate-400 font-mono mt-1">Inspecting inquiry from {{ $message->name }}</p>
        </div>
        
        <!-- Delete Action -->
        <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this message?');">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 font-semibold rounded-xl transition-all">
                Delete Message
            </button>
        </form>
    </div>

    <!-- Message Card Layout -->
    <div class="bg-slate-900 border border-slate-850 rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Sender Header info panel -->
        <div class="px-8 py-6 bg-slate-950/60 border-b border-slate-850 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-500 font-mono uppercase tracking-wider mb-1">From Sender</p>
                <p class="text-base font-bold text-slate-200">{{ $message->name }}</p>
                <a href="mailto:{{ $message->email }}" class="text-xs text-cyan-400 hover:underline font-mono mt-0.5 block">{{ $message->email }}</a>
            </div>
            
            <div class="sm:text-right">
                <p class="text-xs text-slate-500 font-mono uppercase tracking-wider mb-1">Time Received</p>
                <p class="text-sm font-semibold text-slate-300">{{ $message->created_at->format('M d, Y \a\t h:i A') }}</p>
                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $message->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- Message Body -->
        <div class="p-8">
            <p class="text-xs text-slate-500 font-mono uppercase tracking-wider mb-3">Subject / Title</p>
            <h2 class="text-lg font-bold text-white mb-6">{{ $message->subject ?? '(No Subject Specified)' }}</h2>

            <p class="text-xs text-slate-500 font-mono uppercase tracking-wider mb-3">Message Body</p>
            <div class="p-6 rounded-xl bg-slate-950/60 border border-slate-850 text-slate-300 text-sm leading-relaxed whitespace-pre-wrap font-sans">
                {{ $message->message }}
            </div>
        </div>

        <!-- Footer quick reply action -->
        <div class="px-8 py-5 bg-slate-950/20 border-t border-slate-850 flex justify-end">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject ?? 'Your portfolio inquiry') }}" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-slate-950 font-bold rounded-xl shadow shadow-cyan-500/10 active:scale-95 transition-all text-xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                Reply via Email
            </a>
        </div>

    </div>

@endsection
