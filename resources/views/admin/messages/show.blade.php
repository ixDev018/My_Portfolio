@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }
</style>

{{-- Back link --}}
<div style="margin-bottom:0.75rem;">
    <a href="{{ route('admin.messages.index') }}"
       style="display:inline-flex;align-items:center;gap:0.35rem;font-family:'Space Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#9B9589;text-decoration:none;transition:color .15s;"
       onmouseover="this.style.color='#6829AA'" onmouseout="this.style.color='#9B9589'">
        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Inbox
    </a>
</div>

{{-- Page header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Message Details</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Inquiry from {{ $message->name }}</p>
    </div>
    <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST"
          onsubmit="return confirm('Permanently delete this message?')">
        @csrf
        <button type="submit"
                style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.5rem 1rem;background:#FFF1F1;border:1px solid #FECACA;border-radius:0.55rem;color:#dc2626;font-size:0.78rem;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FFF1F1'">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete Message
        </button>
    </form>
</div>

{{-- Message card --}}
<div style="background:#fff;border:1px solid #D8D4C8;border-radius:1rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">

    {{-- Sender meta strip --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;padding:1.1rem 1.5rem;background:#F7F5EE;border-bottom:1px solid #E2DDD3;">
        <div>
            <p style="font-family:'Space Mono',monospace;font-size:0.58rem;text-transform:uppercase;letter-spacing:0.1em;color:#9B9589;margin-bottom:0.3rem;">From Sender</p>
            <p style="font-size:0.9rem;font-weight:700;color:#1a1207;">{{ $message->name }}</p>
            <a href="mailto:{{ $message->email }}"
               style="font-family:'Space Mono',monospace;font-size:0.65rem;color:#6829AA;text-decoration:none;display:block;margin-top:0.2rem;"
               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                {{ $message->email }}
            </a>
        </div>
        <div style="text-align:right;">
            <p style="font-family:'Space Mono',monospace;font-size:0.58rem;text-transform:uppercase;letter-spacing:0.1em;color:#9B9589;margin-bottom:0.3rem;">Time Received</p>
            <p style="font-size:0.875rem;font-weight:600;color:#1a1207;">{{ $message->created_at->format('M d, Y \a\t h:i A') }}</p>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;color:#B0A99F;margin-top:0.15rem;">{{ $message->created_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Message body --}}
    <div style="padding:1.5rem;">
        <p style="font-family:'Space Mono',monospace;font-size:0.58rem;text-transform:uppercase;letter-spacing:0.1em;color:#9B9589;margin-bottom:0.4rem;">Subject / Title</p>
        <h2 style="font-family:'Outfit',sans-serif;font-size:1.15rem;font-weight:700;color:#1a1207;margin-bottom:1.5rem;">
            {{ $message->subject ?? '(No Subject Specified)' }}
        </h2>

        <p style="font-family:'Space Mono',monospace;font-size:0.58rem;text-transform:uppercase;letter-spacing:0.1em;color:#9B9589;margin-bottom:0.4rem;">Message Body</p>
        <div style="padding:1.25rem;border-radius:0.75rem;background:#F7F5EE;border:1px solid #E2DDD3;color:#2c2826;font-size:0.875rem;line-height:1.75;white-space:pre-wrap;font-family:'Inter',sans-serif;">{{ $message->message }}</div>
    </div>

    {{-- Footer reply CTA --}}
    <div style="padding:1rem 1.5rem;background:#F7F5EE;border-top:1px solid #E2DDD3;display:flex;justify-content:flex-end;">
        <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject ?? 'Your portfolio inquiry') }}"
           style="display:inline-flex;align-items:center;gap:0.45rem;padding:0.6rem 1.25rem;background:#6829AA;color:#fff;border-radius:0.65rem;font-size:0.82rem;font-weight:700;font-family:'Outfit',sans-serif;text-decoration:none;box-shadow:0 4px 14px rgba(104,41,170,0.28);transition:all .15s;"
           onmouseover="this.style.background='#5720A0'" onmouseout="this.style.background='#6829AA'">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Reply via Email
        </a>
    </div>

</div>

@endsection
