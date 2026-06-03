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
        display:flex; align-items:center; gap:0.6rem;
    }
    .lt-card-title {
        font-family:'Outfit',sans-serif; font-size:0.875rem;
        font-weight:700; color:#1a1207;
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
    /* experience list row */
    .exp-row {
        display:flex; align-items:flex-start; gap:1rem;
        padding:0.9rem 1.25rem;
        border-bottom:1px solid #F0EDE6;
        transition:background 0.12s;
        cursor:grab;
    }
    .exp-row:last-child { border-bottom:none; }
    .exp-row:hover { background:#F7F5EE; }
    .lt-drag-handle { color:#C4BDB2; cursor:grab; flex-shrink:0; padding-top:0.25rem; }
    .lt-drag-handle:hover { color:#9B9589; }
    .lt-thumb {
        width:52px; height:40px; object-fit:cover;
        border-radius:0.4rem; border:1px solid #E2DDD3; flex-shrink:0;
    }
    .lt-thumb-ph {
        width:52px; height:40px; border-radius:0.4rem;
        background:#F0EDE6; border:1px solid #E2DDD3;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0; color:#C8C3BA;
    }
    .badge-duration {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#E6F7FA; color:#0A7A8C; border:1px solid #A3DFE8;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Work Experience</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Timeline entries — drag to reorder</p>
    </div>
    <button onclick="document.getElementById('add-form').classList.toggle('hidden')" class="lt-btn-primary">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Add Experience
    </button>
</div>

{{-- Add form --}}
<div id="add-form" class="{{ $errors->any() ? '' : 'hidden' }} lt-form-card">
    <p style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:#1a1207;margin-bottom:1rem;">➕ New Work Experience</p>
    <form action="{{ route('admin.experiences.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
            <div>
                <label class="lt-label">Company / Organization</label>
                <input type="text" name="company" value="{{ old('company') }}" required class="lt-input" placeholder="e.g. School Organization">
                @error('company')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lt-label">Role / Position</label>
                <input type="text" name="role" value="{{ old('role') }}" required class="lt-input" placeholder="e.g. Multimedia Lead">
            </div>
            <div>
                <label class="lt-label">Duration (Year Range)</label>
                <input type="text" name="duration" value="{{ old('duration') }}" required class="lt-input" placeholder="e.g. 2022 – 2025">
            </div>
            <div>
                <label class="lt-label">Cover Image (optional)</label>
                <input type="file" name="image" accept="image/*"
                       style="display:block;width:100%;font-size:0.8rem;color:#5A5248;cursor:pointer;">
            </div>
            <div style="grid-column:1/-1;">
                <label class="lt-label">Description</label>
                <textarea name="description" rows="3" class="lt-input" placeholder="What did you do in this role?">{{ old('description') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;justify-content:flex-end;gap:0.65rem;">
            <button type="button" onclick="document.getElementById('add-form').classList.add('hidden')" class="lt-btn-secondary">Cancel</button>
            <button type="submit" class="lt-btn-primary">Save</button>
        </div>
    </form>
</div>

{{-- Experience list --}}
<div class="lt-card">
    <div class="lt-card-header">
        <h2 class="lt-card-title">Timeline Entries</h2>
        <span class="lt-count-badge">{{ $experiences->count() }}</span>
        <span style="font-family:'Space Mono',monospace;font-size:0.6rem;color:#9B9589;margin-left:auto;">↕ Drag to reorder</span>
    </div>

    @if($experiences->isEmpty())
        <div style="padding:3rem;text-align:center;">
            <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No experience entries yet.</p>
        </div>
    @else
        <ul id="experience-list" style="list-style:none;padding:0;margin:0;">
            @foreach($experiences as $exp)
                <li data-id="{{ $exp->id }}" x-data="{ editing: false, menuOpen: false }" @click.outside="menuOpen = false">
                    <div class="exp-row">

                        {{-- Drag handle --}}
                        <div class="lt-drag-handle drag-handle" title="Drag to reorder">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                        </div>

                        {{-- Thumb --}}
                        @if($exp->image_path)
                            <img src="{{ asset('storage/'.$exp->image_path) }}" class="lt-thumb">
                        @else
                            <div class="lt-thumb-ph">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        {{-- Info (view mode) --}}
                        <div style="flex:1;min-width:0;" x-show="!editing">
                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.2rem;">
                                <span style="font-weight:700;color:#1a1207;font-size:0.875rem;">{{ $exp->company }}</span>
                                <span class="badge-duration">{{ $exp->duration }}</span>
                            </div>
                            <p style="font-size:0.78rem;color:#7A7267;">{{ $exp->role }}</p>
                            @if($exp->description)
                                <p style="font-size:0.72rem;color:#B0A99F;margin-top:0.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:520px;">
                                    {{ Str::limit($exp->description, 85) }}
                                </p>
                            @endif
                        </div>

                        {{-- Inline edit --}}
                        <div x-show="editing" style="display:none;flex:1;">
                            <form action="{{ route('admin.experiences.update', $exp->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.65rem;">
                                    <div>
                                        <label class="lt-label">Company</label>
                                        <input type="text" name="company" value="{{ $exp->company }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                    </div>
                                    <div>
                                        <label class="lt-label">Role</label>
                                        <input type="text" name="role" value="{{ $exp->role }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                    </div>
                                    <div>
                                        <label class="lt-label">Duration</label>
                                        <input type="text" name="duration" value="{{ $exp->duration }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                    </div>
                                    <div>
                                        <label class="lt-label">Replace Image</label>
                                        <input type="file" name="image" accept="image/*" style="font-size:0.78rem;color:#5A5248;cursor:pointer;">
                                    </div>
                                    <div style="grid-column:1/-1;">
                                        <label class="lt-label">Description</label>
                                        <textarea name="description" rows="2" class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">{{ $exp->description }}</textarea>
                                    </div>
                                </div>
                                <div style="display:flex;gap:0.5rem;margin-top:0.65rem;">
                                    <button type="submit" class="lt-btn-primary" style="padding:0.38rem 0.9rem;font-size:0.73rem;">Update</button>
                                    <button type="button" @click="editing=false" class="lt-btn-secondary" style="padding:0.38rem 0.7rem;font-size:0.73rem;">Cancel</button>
                                </div>
                            </form>
                        </div>

                        {{-- Actions --}}
                        <div style="flex-shrink:0; position:relative; z-index:20;" x-show="!editing">
                            <div class="cms-dots-wrap">
                                <button class="cms-dots-btn"
                                        :class="menuOpen ? 'open' : ''"
                                        @click="menuOpen = !menuOpen"
                                        title="Actions">
                                    <svg style="width:15px;height:15px;" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                                    </svg>
                                </button>
                                <div class="cms-dropdown"
                                     x-show="menuOpen"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     @click.stop>
                                    <button @click="editing = true; menuOpen = false">
                                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    <div class="cms-dd-divider"></div>
                                    <form action="{{ route('admin.experiences.delete', $exp->id) }}" method="POST"
                                          @submit.prevent="if(confirm('Delete this experience?')) $el.submit()">
                                        @csrf
                                        <button type="submit">
                                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<script>
    const list = document.getElementById('experience-list');
    if (list) {
        Sortable.create(list, {
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'opacity-30',
            onEnd: function () {
                const order = [...list.querySelectorAll('[data-id]')].map(el => parseInt(el.dataset.id));
                fetch('{{ route('admin.experiences.reorder') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ order })
                });
            }
        });
    }
</script>

@endsection
