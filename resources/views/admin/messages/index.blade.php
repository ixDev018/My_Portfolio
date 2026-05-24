@extends('admin.layout')

@section('admin_content')

    <!-- Messages Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Visitor Inbox</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Read and manage inquiries received from your website contact form</p>
    </div>

    <!-- Inbox Listings Table Card -->
    <div class="bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-850 text-slate-400 text-xs uppercase tracking-wider font-mono">
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Sender</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-850/10 transition-colors duration-150 {{ !$msg->is_read ? 'font-semibold bg-cyan-950/5' : '' }}">
                            <!-- Status Indicator -->
                            <td class="px-6 py-4">
                                @if(!$msg->is_read)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] font-bold uppercase tracking-wider">
                                        Unread
                                    </span>
                                @else
                                    <span class="text-slate-500 text-xs">Read</span>
                                @endif
                            </td>

                            <!-- Sender Info -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-200 truncate max-w-[150px]">{{ $msg->name }}</p>
                                <p class="text-xs text-slate-500 truncate max-w-[150px] font-mono mt-0.5">{{ $msg->email }}</p>
                            </td>

                            <!-- Subject/Message snippet -->
                            <td class="px-6 py-4 min-w-[200px]">
                                <p class="text-sm text-slate-300 truncate max-w-[250px]">{{ $msg->subject ?? '(No Subject)' }}</p>
                                <p class="text-xs text-slate-500 truncate max-w-[250px] font-medium mt-0.5">{{ $msg->message }}</p>
                            </td>

                            <!-- Date Received -->
                            <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                                {{ $msg->created_at->diffForHumans() }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Read Details Button -->
                                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-950 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-lg hover:text-white transition-all">
                                        Open
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST" onsubmit="return confirm('Permanently delete this message?');">
                                        @csrf
                                        <button type="submit" class="text-slate-500 hover:text-rose-400 p-1.5 transition-colors" title="Delete Message">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 text-slate-650 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-sm font-medium">Your contact inbox is currently empty.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
