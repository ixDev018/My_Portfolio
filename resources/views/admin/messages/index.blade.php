@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }

    /* table */
    .inbox-table { width:100%; border-collapse:collapse; }
    .inbox-table thead tr {
        background:#F7F5EE; border-bottom:1px solid #E2DDD3;
        position:sticky; top:0; z-index:1;
    }
    .inbox-table th {
        font-family:'Space Mono',monospace; font-size:0.58rem;
        text-transform:uppercase; letter-spacing:0.12em; color:#9B9589;
        padding:0.65rem 1rem; text-align:left; white-space:nowrap;
    }
    .inbox-table td {
        padding:0.75rem 1rem; border-bottom:1px solid #F0EDE6;
        font-size:0.82rem; color:#2c2826; vertical-align:middle;
    }
    .inbox-table tr:last-child td { border-bottom:none; }
    .inbox-table tbody tr { transition:background 0.12s; }
    .inbox-table tbody tr:hover td { background:#F7F5EE; }
    .inbox-table tbody tr.unread td { background:#FDFBFF; }
    .inbox-table tbody tr.unread:hover td { background:#F5EEFF; }
    .inbox-table tbody tr.unread td:first-child {
        box-shadow:inset 3px 0 0 #6829AA;
    }

    /* badges */
    .badge-unread {
        display:inline-flex; align-items:center; gap:0.25rem;
        padding:0.18rem 0.6rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        text-transform:uppercase; letter-spacing:0.07em;
        background:#FFF4E5; color:#C2480A; border:1px solid #FDDAAA;
    }
    .badge-unread::before {
        content:''; width:6px; height:6px; border-radius:50%; background:#C2480A;
    }
    .badge-read {
        font-family:'Space Mono',monospace; font-size:0.58rem;
        color:#C4BDB2; text-transform:uppercase; letter-spacing:0.07em;
    }

    /* action btns */
    .btn-open {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.35rem 0.8rem; background:#fff; border:1px solid #D8D4C8;
        border-radius:0.45rem; color:#5A5248; font-size:0.72rem; font-weight:600;
        font-family:'Outfit',sans-serif; text-decoration:none; transition:all .15s;
    }
    .btn-open:hover { background:#EEE6FF; border-color:#C4A8F0; color:#6829AA; }
    .btn-del-icon {
        background:transparent; border:none; cursor:pointer;
        color:#C4BDB2; padding:0.3rem; border-radius:0.35rem; transition:all .15s;
    }
    .btn-del-icon:hover { color:#dc2626; background:#FFF1F1; }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Visitor Inbox</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Read and manage inquiries from your contact form</p>
    </div>
    {{-- unread count badge --}}
    @php $unread = $messages->where('is_read', false)->count(); @endphp
    @if($unread > 0)
        <span style="padding:0.25rem 0.75rem;border-radius:100px;font-family:'Space Mono',monospace;font-size:0.6rem;font-weight:700;background:#FFF4E5;color:#C2480A;border:1px solid #FDDAAA;">{{ $unread }} unread</span>
    @endif
</div>

<div style="background:#fff;border:1px solid #D8D4C8;border-radius:1rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <table class="inbox-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Sender</th>
                <th>Subject &amp; Preview</th>
                <th>Received</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $msg)
                <tr class="{{ !$msg->is_read ? 'unread' : '' }}">

                    {{-- Status --}}
                    <td>
                        @if(!$msg->is_read)
                            <span class="badge-unread">Unread</span>
                        @else
                            <span class="badge-read">Read</span>
                        @endif
                    </td>

                    {{-- Sender --}}
                    <td>
                        <p style="font-weight:600;color:#1a1207;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">{{ $msg->name }}</p>
                        <p style="font-family:'Space Mono',monospace;font-size:0.6rem;color:#9B9589;margin-top:0.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">{{ $msg->email }}</p>
                    </td>

                    {{-- Subject --}}
                    <td style="min-width:220px;">
                        <p style="font-weight:{{ !$msg->is_read ? '600' : '400' }};color:#2c2826;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px;">
                            {{ $msg->subject ?? '(No Subject)' }}
                        </p>
                        <p style="font-size:0.72rem;color:#9B9589;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px;margin-top:0.1rem;">
                            {{ Str::limit($msg->message, 60) }}
                        </p>
                    </td>

                    {{-- Date --}}
                    <td style="white-space:nowrap;">
                        <span style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#B0A99F;">{{ $msg->created_at->diffForHumans() }}</span>
                    </td>

                    {{-- Actions --}}
                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.5rem;">
                            <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn-open">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Open
                            </a>
                            <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST"
                                  onsubmit="return confirm('Permanently delete this message?')">
                                @csrf
                                <button type="submit" class="btn-del-icon" title="Delete">
                                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:3.5rem 1rem;">
                        <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">Your contact inbox is currently empty.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
