@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }
    .lt-label {
        display:block; font-family:'Space Mono',monospace;
        font-size:0.58rem; text-transform:uppercase;
        letter-spacing:0.1em; color:#9B9589; margin-bottom:0.35rem;
    }
    .lt-input {
        width:100%; background:#fff; border:1px solid #D8D4C8;
        border-radius:0.5rem; padding:0.45rem 0.8rem;
        color:#1a1207; font-size:0.8125rem;
        font-family:'Inter',sans-serif; outline:none;
        transition:border-color 0.18s,box-shadow 0.18s;
    }
    .lt-input:focus { border-color:#6829AA; box-shadow:0 0 0 3px rgba(104,41,170,0.1); }
    .lt-input::placeholder { color:#B0A99F; }
    select.lt-input { cursor:pointer; }
    textarea.lt-input { resize:vertical; }
    .lt-btn-primary {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.55rem 1.1rem; background:#6829AA; color:#fff;
        border:none; border-radius:0.55rem; font-size:0.8rem;
        font-weight:700; font-family:'Outfit',sans-serif; cursor:pointer;
        box-shadow:0 3px 10px rgba(104,41,170,0.25); transition:all .15s;
    }
    .lt-btn-primary:hover { background:#5720A0; }
    .lt-btn-secondary {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 0.9rem; background:#fff;
        border:1px solid #D8D4C8; border-radius:0.5rem;
        color:#5A5248; font-size:0.78rem; font-weight:600;
        font-family:'Outfit',sans-serif; cursor:pointer; transition:all .15s;
    }
    .lt-btn-secondary:hover { background:#F7F5EE; border-color:#C4BDB2; color:#1a1207; }
    .lt-btn-danger {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 0.9rem; background:#FFF1F1;
        border:1px solid #FECACA; border-radius:0.5rem;
        color:#dc2626; font-size:0.78rem; font-weight:600;
        font-family:'Outfit',sans-serif; cursor:pointer; transition:all .15s;
    }
    .lt-btn-danger:hover { background:#FEE2E2; }
    .lt-err { color:#dc2626; font-size:0.72rem; margin-top:0.25rem; }
    .lt-card {
        background:#fff; border:1px solid #D8D4C8;
        border-radius:1rem; overflow:hidden;
        box-shadow:0 1px 3px rgba(0,0,0,0.05);
    }
    .lt-card-header {
        padding:0.85rem 1.25rem; border-bottom:1px solid #E2DDD3;
        background:#F7F5EE;
        display:flex; align-items:center; justify-content:space-between;
    }
    .lt-card-title {
        font-family:'Outfit',sans-serif; font-size:0.875rem;
        font-weight:700; color:#1a1207;
        display:flex; align-items:center; gap:0.5rem;
    }
    .lt-count-badge {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem;
        font-weight:700; background:#EEE6FF; color:#6829AA;
        border:1px solid #D8C0F8;
    }
    .lt-form-card {
        background:#fff; border:1px solid #D8D4C8;
        border-radius:1rem; padding:1.25rem;
        margin-bottom:1rem;
        box-shadow:0 1px 3px rgba(0,0,0,0.05);
    }
    /* achievement table */
    .ach-table { width:100%; border-collapse:collapse; }
    .ach-table thead tr {
        background:#F7F5EE; border-bottom:1px solid #E2DDD3; position:sticky; top:0;
    }
    .ach-table th {
        font-family:'Space Mono',monospace; font-size:0.58rem;
        text-transform:uppercase; letter-spacing:0.12em; color:#9B9589;
        padding:0.6rem 1rem; text-align:left; white-space:nowrap;
    }
    .ach-table td {
        padding:0.7rem 1rem; border-bottom:1px solid #F0EDE6;
        font-size:0.82rem; color:#2c2826; vertical-align:middle;
    }
    .ach-table tr:last-child td { border-bottom:none; }
    .ach-table tbody tr { transition:background 0.12s; }
    .ach-table tbody tr:hover td { background:#F7F5EE; }

    /* badges */
    .badge-year {
        padding:0.18rem 0.6rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#E6F7FA; color:#0A7A8C; border:1px solid #A3DFE8;
    }
    .badge-award {
        padding:0.18rem 0.6rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#FFF4E5; color:#C2480A; border:1px solid #FDDAAA;
    }
    .badge-cert {
        padding:0.18rem 0.6rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#EEE6FF; color:#6829AA; border:1px solid #D8C0F8;
    }
    .badge-count {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#EEE6FF; color:#6829AA; border:1px solid #D8C0F8;
    }
</style>

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Achievements</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Awards &amp; Certificates — displayed in the Achievements section</p>
    </div>
    <button onclick="document.getElementById('add-form').classList.toggle('hidden')" class="lt-btn-primary">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Add Achievement
    </button>
</div>

{{-- Add form --}}
<div id="add-form" class="{{ old('_action') === 'add' ? '' : 'hidden' }} lt-form-card">
    <p style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:#1a1207;margin-bottom:1rem;">➕ New Achievement</p>
    <form action="{{ route('admin.achievements.store') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
            <div>
                <label class="lt-label">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="lt-input" placeholder="e.g. Best UI Design Award">
                @error('title')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lt-label">Issuer / Organization</label>
                <input type="text" name="issuer" value="{{ old('issuer') }}" required class="lt-input" placeholder="e.g. Google, Adobe, etc.">
            </div>
            <div>
                <label class="lt-label">Year</label>
                <input type="text" name="year" value="{{ old('year') }}" required class="lt-input" placeholder="e.g. 2024">
            </div>
            <div>
                <label class="lt-label">Type</label>
                <select name="type" class="lt-input">
                    <option value="award" {{ old('type') === 'award' ? 'selected' : '' }}>🏆 Award</option>
                    <option value="certificate" {{ old('type') === 'certificate' ? 'selected' : '' }}>📜 Certificate</option>
                </select>
            </div>
            <div style="grid-column:1/-1;">
                <label class="lt-label">Description (optional)</label>
                <textarea name="description" rows="2" class="lt-input" placeholder="Brief description…">{{ old('description') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;justify-content:flex-end;gap:0.65rem;">
            <button type="button" onclick="document.getElementById('add-form').classList.add('hidden')" class="lt-btn-secondary">Cancel</button>
            <button type="submit" class="lt-btn-primary">Save Achievement</button>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="lt-card">
    <div class="lt-card-header">
        <h2 class="lt-card-title">
            All Achievements
            <span class="lt-count-badge">{{ $achievements->count() }}</span>
        </h2>
        <div style="display:flex;gap:0.5rem;">
            <span class="badge-award">{{ $achievements->where('type','award')->count() }} Awards</span>
            <span class="badge-cert">{{ $achievements->where('type','certificate')->count() }} Certs</span>
        </div>
    </div>

    @if($achievements->isEmpty())
        <div style="padding:3rem;text-align:center;">
            <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No achievements yet. Add one above.</p>
        </div>
    @else
        <table class="ach-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Issuer</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th style="width:140px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($achievements as $item)
                    <tr x-data="{ editing: false }">
                        <td>
                            <span x-show="!editing" style="font-weight:600;color:#1a1207;">{{ $item->title }}</span>
                            {{-- Inline edit --}}
                            <div x-show="editing" style="display:none;">
                                <form action="{{ route('admin.achievements.update', $item->id) }}" method="POST">
                                    @csrf
                                    <div style="display:grid;gap:0.5rem;min-width:240px;">
                                        <input type="text" name="title" value="{{ $item->title }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                        <input type="text" name="issuer" value="{{ $item->issuer }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                        <div style="display:flex;gap:0.5rem;">
                                            <input type="text" name="year" value="{{ $item->year }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;width:80px;">
                                            <select name="type" class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                                <option value="award" {{ $item->type === 'award' ? 'selected' : '' }}>Award</option>
                                                <option value="certificate" {{ $item->type === 'certificate' ? 'selected' : '' }}>Certificate</option>
                                            </select>
                                        </div>
                                        <textarea name="description" rows="2" class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">{{ $item->description }}</textarea>
                                        <div style="display:flex;gap:0.5rem;">
                                            <button type="submit" class="lt-btn-primary" style="padding:0.38rem 0.85rem;font-size:0.73rem;">Save</button>
                                            <button type="button" @click="editing=false" class="lt-btn-secondary" style="padding:0.38rem 0.7rem;font-size:0.73rem;">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td x-show="!editing" style="color:#7A7267;">{{ $item->issuer }}</td>
                        <td x-show="!editing"><span class="badge-year">{{ $item->year }}</span></td>
                        <td x-show="!editing">
                            @if($item->type === 'award')
                                <span class="badge-award">🏆 Award</span>
                            @else
                                <span class="badge-cert">📜 Certificate</span>
                            @endif
                        </td>
                        <td x-show="!editing">
                            <div style="display:flex;gap:0.4rem;justify-content:flex-end;">
                                <button @click="editing=true" class="lt-btn-secondary" style="padding:0.35rem 0.7rem;font-size:0.7rem;">Edit</button>
                                <form action="{{ route('admin.achievements.delete', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this achievement?')">
                                    @csrf
                                    <button type="submit" class="lt-btn-danger" style="padding:0.35rem 0.7rem;font-size:0.7rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
