@extends('admin.layout')

@section('admin_content')

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="cms-page-title">Intro Slides</h1>
            <p class="cms-page-subtitle">Introduction chapters — drag to reorder</p>
        </div>
        <button onclick="document.getElementById('add-form').classList.toggle('hidden')" class="cms-btn-primary" style="padding:0.6rem 1.25rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Slide
        </button>
    </div>

    {{-- ADD FORM --}}
    <div id="add-form" class="{{ $errors->any() ? '' : 'hidden' }} cms-card p-6 mb-6">
        <h2 style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:1.25rem;">➕ New Slide</h2>
        <form action="{{ route('admin.intro_slides.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="cms-label">Chapter Label</label>
                    <input type="text" name="chapter_label" value="{{ old('chapter_label', 'Chapter') }}" required class="cms-input" placeholder="e.g. Chapter 2">
                    @error('chapter_label')<p style="color:#f87171;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="cms-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="cms-input" placeholder="e.g. Visual Arts & Design">
                    @error('title')<p style="color:#f87171;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="cms-label">Subtitle / Roles</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="cms-input" placeholder="e.g. Illustrator • UI Designer">
                </div>
                <div class="sm:col-span-2">
                    <label class="cms-label">Image</label>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-white/60 hover:file:bg-white/10 cursor-pointer">
                </div>
                <div class="sm:col-span-2">
                    <label class="cms-label">Description Paragraphs</label>
                    <textarea name="description" rows="4" class="cms-input" style="resize:vertical;" placeholder="Write paragraphs... Double enter for new paragraph.">{{ old('description') }}</textarea>
                </div>
            </div>
            <div style="margin-top:1rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" onclick="document.getElementById('add-form').classList.add('hidden')" class="cms-btn-secondary">Cancel</button>
                <button type="submit" class="cms-btn-primary">Save Slide</button>
            </div>
        </form>
    </div>

    {{-- DRAGGABLE LIST --}}
    <div class="cms-card overflow-hidden">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); display:flex; align-items:center; gap:0.75rem;">
            <h2 style="font-family:'Outfit',sans-serif;font-size:0.875rem;font-weight:700;color:#fff;">All Slides</h2>
            <span class="cms-badge cms-badge-cyan">{{ $slides->count() }}</span>
            <span style="font-family:'Space Mono',monospace;font-size:0.65rem;color:rgba(255,255,255,0.3);margin-left:auto;">↕ Drag to reorder (Slide 1 is locked)</span>
        </div>

        <ul id="slide-list" style="list-style:none;padding:0;margin:0;">
            @foreach($slides as $slide)
                <li data-id="{{ $slide->id }}"
                    class="{{ $slide->is_locked ? 'locked-slide bg-slate-900/50' : '' }}"
                    x-data="{ editing: false }"
                    style="display:flex;align-items:flex-start;gap:1rem;padding:1rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.04);transition:background 0.15s ease;"
                    onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                    onmouseout="this.style.background='transparent'">

                    {{-- Drag Handle (Disabled if locked) --}}
                    @if($slide->is_locked)
                        <div style="padding-top:0.35rem;color:rgba(255,255,255,0.1);flex-shrink:0;" title="Locked">
                            <svg style="width:16px;height:16px;color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    @else
                        <div class="drag-handle" style="padding-top:0.35rem;color:rgba(255,255,255,0.2);cursor:grab;flex-shrink:0;" title="Drag to reorder">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                        </div>
                    @endif

                    {{-- Cover image thumb --}}
                    @if($slide->image_path)
                        <img src="{{ asset('storage/' . $slide->image_path) }}"
                             style="width:52px;height:40px;object-fit:cover;border-radius:0.4rem;border:1px solid rgba(255,255,255,0.1);flex-shrink:0;">
                    @else
                        <div style="width:52px;height:40px;background:rgba(77,217,240,0.08);border:1px solid rgba(77,217,240,0.15);border-radius:0.4rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:16px;height:16px;color:rgba(77,217,240,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;" x-show="!editing">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;flex-wrap:wrap;">
                            <span style="font-weight:700;color:#fff;font-size:0.875rem;">{{ $slide->title }}</span>
                            <span class="cms-badge cms-badge-cyan">{{ $slide->chapter_label }}</span>
                            @if($slide->is_locked)
                                <span class="cms-badge cms-badge-orange">Template Slide</span>
                            @endif
                        </div>
                        <p style="font-size:0.78rem;color:rgba(255,255,255,0.5);">{{ $slide->subtitle }}</p>
                    </div>

                    {{-- Inline Edit Form --}}
                    <div x-show="editing" style="display:none;flex:1;">
                        <form action="{{ route('admin.intro_slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="cms-label">Chapter Label</label>
                                    <input type="text" name="chapter_label" value="{{ $slide->chapter_label }}" required class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                </div>
                                <div>
                                    <label class="cms-label">Title</label>
                                    <input type="text" name="title" value="{{ $slide->title }}" required class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="cms-label">Subtitle / Roles</label>
                                    <input type="text" name="subtitle" value="{{ $slide->subtitle }}" class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="cms-label">Replace Image</label>
                                    <input type="file" name="image" accept="image/*"
                                           class="block w-full text-xs file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-white/60 cursor-pointer">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="cms-label">Description Paragraphs</label>
                                    <textarea name="description" rows="3" class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;resize:vertical;">{{ $slide->description }}</textarea>
                                </div>
                            </div>
                            <div style="display:flex;gap:0.5rem;margin-top:0.75rem;">
                                <button type="submit" class="cms-btn-primary" style="padding:0.4rem 0.875rem;font-size:0.75rem;">Update</button>
                                <button type="button" @click="editing=false" class="cms-btn-secondary" style="padding:0.4rem 0.75rem;font-size:0.75rem;">Cancel</button>
                            </div>
                        </form>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:0.4rem;flex-shrink:0;" x-show="!editing">
                        <button @click="editing=true" class="cms-btn-secondary" style="padding:0.4rem 0.75rem;font-size:0.72rem;">Edit</button>
                        @if(!$slide->is_locked)
                            <form action="{{ route('admin.intro_slides.delete', $slide->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this slide?')">
                                @csrf
                                <button type="submit" class="cms-btn-danger" style="padding:0.4rem 0.75rem;font-size:0.72rem;">Delete</button>
                            </form>
                        @endif
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
                filter: '.locked-slide', // prevent dragging the locked slide
                preventOnFilter: false,
                onEnd: function () {
                    const order = [...list.querySelectorAll('[data-id]')].map(el => parseInt(el.dataset.id));
                    fetch('{{ route('admin.intro_slides.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order })
                    });
                }
            });
        }
    </script>

@endsection
