@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; }
    /* shared light-theme tokens */
    .lt-card { background:#fff; border:1px solid #D8D4C8; border-radius:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
    .lt-strip { background:#F7F5EE; }
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
        text-decoration:none;
    }
    .lt-btn-primary:hover { background:#5720A0; }
    .lt-btn-secondary {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 0.9rem; background:#fff;
        border:1px solid #D8D4C8; border-radius:0.5rem;
        color:#5A5248; font-size:0.78rem; font-weight:600;
        font-family:'Outfit',sans-serif; cursor:pointer; transition:all .15s;
        text-decoration:none;
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

    /* card header strip */
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
        font-weight:700; text-transform:uppercase;
        background:#EEE6FF; color:#6829AA; border:1px solid #D8C0F8;
    }

    /* list rows */
    .lt-list-row {
        display:flex; align-items:flex-start; gap:1rem;
        padding:0.9rem 1.25rem;
        border-bottom:1px solid #F0EDE6;
        transition:background 0.12s;
    }
    .lt-list-row:last-child { border-bottom:none; }
    .lt-list-row:hover { background:#F7F5EE; }

    /* drag handle */
    .lt-drag-handle { color:#C4BDB2; cursor:grab; flex-shrink:0; padding-top:0.2rem; }
    .lt-drag-handle:hover { color:#9B9589; }

    /* thumb */
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

    /* inline edit reveal */
    .lt-inline-form { flex:1; }
    .lt-info-title { font-weight:700; color:#1a1207; font-size:0.875rem; }
    .lt-info-sub { font-size:0.78rem; color:#7A7267; margin-top:0.15rem; }
    .lt-info-desc { font-size:0.72rem; color:#B0A99F; margin-top:0.2rem;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:500px; }
    .lt-badge-cyan {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#E6F7FA; color:#0A7A8C; border:1px solid #A3DFE8;
    }
    .lt-badge-locked {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem; font-weight:700;
        background:#FFF4E5; color:#C2480A; border:1px solid #FDDAAA;
    }

    /* add form card */
    .lt-form-card {
        background:#fff; border:1px solid #D8D4C8;
        border-radius:1rem; padding:1.25rem;
        margin-bottom:1rem;
        box-shadow:0 1px 3px rgba(0,0,0,0.05);
    }
    .lt-form-title {
        font-family:'Outfit',sans-serif; font-size:0.9rem;
        font-weight:700; color:#1a1207; margin-bottom:1rem;
    }
    .lt-page-header {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:0.75rem; margin-bottom:0.85rem;
    }
</style>

{{-- ─── SortableJS ─── --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

{{-- Page header --}}
<div class="lt-page-header">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Intro Slides</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Introduction chapters — drag to reorder</p>
    </div>
    <button onclick="document.getElementById('add-form').classList.toggle('hidden')" class="lt-btn-primary">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Add Slide
    </button>
</div>

{{-- Add form --}}
<div id="add-form" class="{{ $errors->any() ? '' : 'hidden' }} lt-form-card">
    <p class="lt-form-title">➕ New Slide</p>
    <form action="{{ route('admin.intro_slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
            <div>
                <label class="lt-label">Chapter Label</label>
                <input type="text" name="chapter_label" value="{{ old('chapter_label', 'Chapter') }}" required class="lt-input" placeholder="e.g. Chapter 2">
                @error('chapter_label')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lt-label">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="lt-input" placeholder="e.g. Visual Arts & Design">
                @error('title')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <div style="grid-column:1/-1;">
                <label class="lt-label">Subtitle / Roles</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="lt-input" placeholder="e.g. Illustrator • UI Designer">
            </div>
            <div style="grid-column:1/-1;">
                <label class="lt-label">Image</label>
                <input type="file" name="image" accept="image/*"
                       style="display:block;width:100%;font-size:0.8rem;color:#5A5248;cursor:pointer;">
            </div>
            <div style="grid-column:1/-1;">
                <label class="lt-label">Description Paragraphs</label>
                <textarea name="description" rows="4" class="lt-input" placeholder="Write paragraphs... Double enter for new paragraph.">{{ old('description') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;justify-content:flex-end;gap:0.65rem;">
            <button type="button" onclick="document.getElementById('add-form').classList.add('hidden')" class="lt-btn-secondary">Cancel</button>
            <button type="submit" class="lt-btn-primary">Save Slide</button>
        </div>
    </form>
</div>

{{-- Slides list --}}
<div class="lt-card" style="overflow:hidden;">
    <div class="lt-card-header">
        <h2 class="lt-card-title">
            All Slides
            <span class="lt-count-badge">{{ $slides->count() }}</span>
        </h2>
        <span style="font-family:'Space Mono',monospace;font-size:0.6rem;color:#9B9589;">↕ Drag to reorder (Slide 1 locked)</span>
    </div>

    <ul id="slide-list" style="list-style:none;padding:0;margin:0;">
        @foreach($slides as $slide)
            <li data-id="{{ $slide->id }}"
                class="{{ $slide->is_locked ? 'locked-slide' : '' }}"
                x-data="{ editing: false }"
                style="{{ $slide->is_locked ? 'background:#FFFBF0;' : '' }}">
                <div class="lt-list-row" style="{{ $slide->is_locked ? 'background:inherit;' : '' }}">

                    {{-- Drag handle --}}
                    @if($slide->is_locked)
                        <div style="padding-top:0.2rem;flex-shrink:0;">
                            <svg style="width:16px;height:16px;color:#F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    @else
                        <div class="lt-drag-handle drag-handle" title="Drag to reorder">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                        </div>
                    @endif

                    {{-- Thumb --}}
                    @if($slide->image_path)
                        <img src="{{ asset('storage/'.$slide->image_path) }}" class="lt-thumb">
                    @else
                        <div class="lt-thumb-ph">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;" x-show="!editing">
                        <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.2rem;">
                            <span class="lt-info-title">{{ $slide->title }}</span>
                            <span class="lt-badge-cyan">{{ $slide->chapter_label }}</span>
                            @if($slide->is_locked)
                                <span class="lt-badge-locked">Template Slide</span>
                            @endif
                        </div>
                        <p class="lt-info-sub">{{ $slide->subtitle }}</p>
                    </div>

                    {{-- Inline Edit --}}
                    <div x-show="editing" style="display:none;flex:1;" class="lt-inline-form">
                        <form action="{{ route('admin.intro_slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.65rem;">
                                <div>
                                    <label class="lt-label">Chapter Label</label>
                                    <input type="text" name="chapter_label" value="{{ $slide->chapter_label }}" required class="lt-input" style="padding:0.38rem 0.7rem;font-size:0.78rem;">
                                </div>
                                <div>
                                    <label class="lt-label">Title</label>
                                    <input type="text" name="title" value="{{ $slide->title }}" required class="lt-input" style="padding:0.38rem 0.7rem;font-size:0.78rem;">
                                </div>
                                <div style="grid-column:1/-1;">
                                    <label class="lt-label">Subtitle / Roles</label>
                                    <input type="text" name="subtitle" value="{{ $slide->subtitle }}" class="lt-input" style="padding:0.38rem 0.7rem;font-size:0.78rem;">
                                </div>
                                <div style="grid-column:1/-1;">
                                    <label class="lt-label">Replace Image</label>
                                    <input type="file" name="image" accept="image/*" style="font-size:0.78rem;color:#5A5248;cursor:pointer;">
                                </div>
                                <div style="grid-column:1/-1;">
                                    <label class="lt-label">Description</label>
                                    <textarea name="description" rows="3" class="lt-input" style="padding:0.38rem 0.7rem;font-size:0.78rem;">{{ $slide->description }}</textarea>
                                </div>
                            </div>
                            <div style="display:flex;gap:0.5rem;margin-top:0.65rem;">
                                <button type="submit" class="lt-btn-primary" style="padding:0.4rem 0.9rem;font-size:0.75rem;">Update</button>
                                <button type="button" @click="editing=false" class="lt-btn-secondary" style="padding:0.4rem 0.75rem;font-size:0.75rem;">Cancel</button>
                            </div>
                        </form>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:0.4rem;flex-shrink:0;" x-show="!editing">
                        <button @click="editing=true" class="lt-btn-secondary" style="padding:0.38rem 0.7rem;font-size:0.72rem;">Edit</button>
                        @if(!$slide->is_locked)
                            <form action="{{ route('admin.intro_slides.delete', $slide->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this slide?')">
                                @csrf
                                <button type="submit" class="lt-btn-danger" style="padding:0.38rem 0.7rem;font-size:0.72rem;">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<script>
    const list = document.getElementById('slide-list');
    if (list) {
        Sortable.create(list, {
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'opacity-30',
            filter: '.locked-slide',
            preventOnFilter: false,
            onEnd: function () {
                const order = [...list.querySelectorAll('[data-id]')].map(el => parseInt(el.dataset.id));
                fetch('{{ route('admin.intro_slides.reorder') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ order })
                });
            }
        });
    }
</script>

@endsection
