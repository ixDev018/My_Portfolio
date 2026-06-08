@extends('admin.layout')

@section('admin_content')

<!-- NoUiSlider for Video Trimming -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- CropperJS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    .cms-main { background: #EDEAE0; }
    
    .slide-item .slide-actions { opacity: 0; transition: opacity 0.2s; pointer-events: none; }
    .slide-item:hover .slide-actions { opacity: 1; pointer-events: auto; }

    /* ─── Page shell ─── */
    .pe-back {
        display:inline-flex; align-items:center; gap:0.35rem;
        font-family:'Space Mono',monospace; font-size:0.62rem;
        text-transform:uppercase; letter-spacing:0.1em;
        color:#9B9589; text-decoration:none;
        transition:color 0.15s; margin-bottom:0.6rem;
    }
    .pe-back:hover { color:#6829AA; }
    .pe-back svg { width:12px; height:12px; }

    .pe-shell {
        display:flex; gap:1.25rem;
        height:calc(100vh - 8.5rem);
        min-height:0; overflow:hidden;
    }

    /* ─── LEFT: Editor ─── */
    .pe-editor-col {
        flex:1 1 0; min-width:0;
        display:flex; flex-direction:column;
        background:#ffffff; border:1px solid #D8D4C8;
        border-radius:1rem; overflow:hidden;
        box-shadow:0 1px 4px rgba(0,0,0,0.06);
    }

    .pe-editor-header {
        padding:1.25rem 1.5rem 0;
        flex-shrink:0;
    }
    .pe-title-input {
        width:100%; border:none; outline:none;
        font-family:'Outfit',sans-serif; font-size:1.65rem;
        font-weight:800; color:#1a1207;
        letter-spacing:-0.02em; background:transparent;
        padding:0; margin-bottom:0.25rem;
    }
    .pe-title-input::placeholder { color:#D8D4C8; }
    .pe-subtitle-input {
        width:100%; border:none; outline:none;
        font-family:'Inter',sans-serif; font-size:0.88rem;
        color:#7A7267; background:transparent;
        padding:0; margin-bottom:0.75rem;
    }
    .pe-subtitle-input::placeholder { color:#C4BDB2; }
    .pe-desc-input {
        width:100%; border:none; outline:none;
        font-family:'Inter',sans-serif; font-size:0.8rem;
        color:#5A5248; background:transparent;
        padding:0; margin-bottom:0.5rem;
        resize:none; line-height:1.5;
    }
    .pe-desc-input::placeholder { color:#C4BDB2; }

    .pe-editor-divider {
        height:1px; background:#E2DDD3;
        margin:0 1.5rem;
    }

    /* ─── Block Editor Area ─── */
    .pe-blocks-scroll {
        flex:1; overflow-y:auto;
        padding:1rem 0.5rem 2rem;
    }

    .pe-block {
        position:relative;
        display:flex;
        align-items:flex-start;
        gap:0;
        padding:0.12rem 0;
        border-radius:0.4rem;
        transition:background 0.12s;
        margin:0 0.5rem;
    }
    .pe-block:hover { background:rgba(0,0,0,0.015); }
    .pe-block:hover .pe-block-handle { opacity:1; }

    .pe-block-handle {
        opacity:0;
        display:flex; align-items:center; justify-content:center;
        width:24px; height:24px;
        flex-shrink:0; cursor:grab;
        color:#C4BDB2; border-radius:0.25rem;
        margin-top:0.15rem; margin-right:0.15rem;
        transition:all 0.12s;
    }
    .pe-block-handle:hover { color:#6829AA; background:#F3ECFF; }
    .pe-block-handle:active { cursor:grabbing; }
    .pe-block-handle svg { width:14px; height:14px; }

    .pe-block-content {
        flex:1; min-width:0;
        outline:none;
        font-family:'Inter',sans-serif;
        font-size:0.875rem;
        color:#2c2826;
        line-height:1.65;
        padding:0.2rem 0.5rem;
        border-radius:0.25rem;
        word-break:break-word;
    }
    .pe-block-content:focus {
        background:rgba(104,41,170,0.02);
    }
    .pe-block-content:empty::before {
        content:attr(data-placeholder);
        color:#C4BDB2;
        pointer-events:none;
    }
    .pe-block-content[data-type="heading2"] {
        font-family:'Outfit',sans-serif;
        font-size:1.25rem; font-weight:700;
        color:#1a1207;
    }
    .pe-block-content[data-type="heading3"] {
        font-family:'Outfit',sans-serif;
        font-size:1.05rem; font-weight:700;
        color:#1a1207;
    }
    .pe-block-content[data-type="quote"] {
        border-left:3px solid #6829AA;
        padding-left:0.85rem;
        color:#5A5248;
        font-style:italic;
    }
    .pe-block-content[data-type="code"] {
        font-family:'Space Mono',monospace;
        font-size:0.78rem;
        background:#F7F5EE;
        border:1px solid #E2DDD3;
        border-radius:0.4rem;
        padding:0.6rem 0.8rem;
        white-space:pre-wrap;
        color:#5A5248;
    }
    .pe-block-content[data-type="bullet"] {
        padding-left:0.2rem;
    }
    .pe-block-content[data-type="bullet"]::before {
        content:'' !important;
    }
    .pe-block.bullet-block .pe-block-bullet {
        display:flex; align-items:center;
        width:20px; height:24px;
        flex-shrink:0; color:#9B9589;
        font-size:1.2rem; line-height:1;
        margin-top:0.15rem;
    }
    .pe-block.numbered-block .pe-block-number {
        display:flex; align-items:center;
        min-width:20px; height:24px;
        flex-shrink:0; color:#9B9589;
        font-family:'Space Mono',monospace;
        font-size:0.72rem; font-weight:700;
        margin-top:0.15rem;
    }
    .pe-block-content[data-type="divider"] {
        height:1px; background:#E2DDD3;
        margin:0.5rem 0;
        pointer-events:none;
        padding:0;
    }
    .pe-block.image-block .pe-block-image-wrap {
        width:100%;
        display:flex; flex-direction:column; gap:0.4rem;
    }
    .pe-block-image-upload {
        width:100%; min-height:100px;
        background:#F7F5EE; border:2px dashed #D8D4C8;
        border-radius:0.5rem;
        display:flex; align-items:center; justify-content:center;
        flex-direction:column; gap:0.35rem;
        cursor:pointer; transition:all 0.15s;
        padding:1rem;
    }
    .pe-block-image-upload:hover { border-color:#6829AA; background:#F3ECFF; }
    .pe-block-image-upload svg { width:1.5rem; height:1.5rem; color:#9B9589; }
    .pe-block-image-upload span {
        font-size:0.68rem; color:#9B9589;
        font-family:'Space Mono',monospace;
    }
    .pe-block-image-preview {
        width:100%; border-radius:0.5rem;
        border:1px solid #E2DDD3;
        overflow:hidden;
    }
    .pe-block-image-preview img {
        width:100%; display:block;
    }
    .pe-block-image-caption {
        width:100%; border:none; outline:none;
        font-size:0.72rem; color:#9B9589;
        font-style:italic; background:transparent;
        text-align:center; padding:0.2rem;
    }
    .pe-block-image-caption::placeholder { color:#C4BDB2; }

    /* Slash command menu */
    .pe-slash-menu {
        position:fixed;
        z-index:100;
        background:#fff;
        border:1px solid #D8D4C8;
        border-radius:0.6rem;
        box-shadow:0 8px 32px rgba(0,0,0,0.12);
        min-width:220px; max-height:320px;
        overflow-y:auto; padding:0.35rem;
    }
    .pe-slash-item {
        display:flex; align-items:center; gap:0.6rem;
        padding:0.45rem 0.65rem;
        border-radius:0.4rem;
        cursor:pointer; transition:background 0.1s;
        font-family:'Inter',sans-serif;
    }
    .pe-slash-item:hover, .pe-slash-item.active {
        background:#F3ECFF;
    }
    .pe-slash-icon {
        width:28px; height:28px;
        display:flex; align-items:center; justify-content:center;
        background:#F7F5EE; border:1px solid #E2DDD3;
        border-radius:0.35rem; flex-shrink:0;
        color:#7A7267; font-size:0.72rem;
    }
    .pe-slash-label {
        font-size:0.8rem; font-weight:600; color:#1a1207;
    }
    .pe-slash-desc {
        font-size:0.6rem; color:#9B9589;
    }
    .pe-slash-header {
        font-family:'Space Mono',monospace;
        font-size:0.52rem; text-transform:uppercase;
        letter-spacing:0.12em; color:#B0A99F;
        padding:0.5rem 0.65rem 0.25rem;
    }

    /* Inline formatting toolbar */
    .pe-fmt-toolbar {
        position:fixed; z-index:99;
        display:flex; align-items:center; gap:0.15rem;
        background:#1a1207; padding:0.25rem 0.35rem;
        border-radius:0.4rem;
        box-shadow:0 4px 16px rgba(0,0,0,0.2);
    }
    .pe-fmt-btn {
        width:28px; height:26px;
        display:flex; align-items:center; justify-content:center;
        background:transparent; border:none;
        color:rgba(255,255,255,0.65);
        border-radius:0.25rem; cursor:pointer;
        transition:all 0.1s;
    }
    .pe-fmt-btn:hover { color:#fff; background:rgba(255,255,255,0.1); }
    .pe-fmt-btn.active { color:#79ECFF; }
    .pe-fmt-btn svg { width:14px; height:14px; }
    .pe-fmt-divider { width:1px; height:18px; background:rgba(255,255,255,0.15); margin:0 0.15rem; }

    /* ─── RIGHT: Sidebar ─── */
    .pe-sidebar {
        width:300px; flex-shrink:0;
        display:flex; flex-direction:column;
        background:#ffffff; border:1px solid #D8D4C8;
        border-radius:1rem; overflow:hidden;
        box-shadow:0 1px 4px rgba(0,0,0,0.06);
    }
    .pe-sidebar-header {
        display:flex; align-items:center; justify-content:space-between;
        padding:0.85rem 1.25rem 0.55rem;
        flex-shrink:0;
    }
    .pe-sidebar-header-label {
        font-family:'Space Mono',monospace;
        font-size:0.58rem; text-transform:uppercase;
        letter-spacing:0.12em; color:#9B9589;
    }
    .pe-sidebar-scroll {
        flex:1; overflow-y:auto;
        padding:0.75rem 1.25rem 1.25rem;
        display:flex; flex-direction:column; gap:0.75rem;
    }

    /* Sidebar fields */
    .pe-field-label {
        display:block; font-family:'Space Mono',monospace;
        font-size:0.6rem; text-transform:uppercase;
        letter-spacing:0.08em; color:#9B9589;
        margin-bottom:0.35rem;
    }
    .pe-field-input {
        width:100%; background:#fff;
        border:1px solid #D8D4C8; border-radius:0.45rem;
        padding:0.5rem 0.7rem;
        color:#1a1207; font-size:0.8rem;
        font-family:'Inter',sans-serif; outline:none;
        transition:border-color 0.18s, box-shadow 0.18s;
    }
    .pe-field-input:focus {
        border-color:#6829AA;
        box-shadow:0 0 0 3px rgba(104,41,170,0.1);
    }
    .pe-field-input::placeholder { color:#B0A99F; }
    .pe-field-select {
        width:100%; background:#fff;
        border:1px solid #D8D4C8; border-radius:0.45rem;
        padding:0.5rem 0.7rem;
        color:#1a1207; font-size:0.8rem;
        font-family:'Inter',sans-serif; outline:none;
        cursor:pointer;
        transition:border-color 0.18s, box-shadow 0.18s;
    }
    .pe-field-select:focus {
        border-color:#6829AA;
        box-shadow:0 0 0 3px rgba(104,41,170,0.1);
    }
    .pe-field-row {
        display:flex; flex-direction:column;
        gap:0.75rem;
    }
    .pe-field-err {
        color:#dc2626; font-size:0.68rem; margin-top:0.2rem;
    }
    .pe-section-divider {
        height:1px; background:#E2DDD3; margin:0.35rem 0;
    }
    .pe-section-label {
        font-family:'Outfit',sans-serif; font-size:0.8rem;
        font-weight:700; color:#1a1207;
        margin-bottom:0.15rem;
    }
    .pe-featured-row {
        display:flex; align-items:center; gap:0.55rem;
    }
    .pe-featured-toggle {
        position:relative; width:36px; height:20px;
        background:#E2DDD3; border-radius:100px;
        cursor:pointer; transition:background 0.2s;
        flex-shrink:0;
    }
    .pe-featured-toggle.on { background:#6829AA; }
    .pe-featured-toggle::after {
        content:''; position:absolute;
        top:2px; left:2px;
        width:16px; height:16px;
        background:#fff; border-radius:50%;
        transition:transform 0.2s;
        box-shadow:0 1px 3px rgba(0,0,0,0.15);
    }
    .pe-featured-toggle.on::after {
        transform:translateX(16px);
    }
    .pe-featured-label {
        font-size:0.78rem; font-weight:600; color:#5A5248;
    }

    /* ─── Footer bar ─── */
    .pe-footer {
        display:flex; align-items:center; justify-content:space-between;
        padding:0.65rem 1rem;
        border-top:1px solid #E2DDD3;
        background:#F7F5EE;
        flex-shrink:0;
    }
    .pe-btn-save {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 1.1rem; background:#6829AA; color:#fff;
        border:none; border-radius:0.55rem; font-size:0.8rem;
        font-weight:700; font-family:'Outfit',sans-serif;
        cursor:pointer; box-shadow:0 3px 10px rgba(104,41,170,0.25);
        transition:all .15s;
    }
    .pe-btn-save:hover { background:#5720A0; }
    .pe-btn-save:disabled { opacity:0.6; cursor:not-allowed; }
    .pe-btn-save svg { width:14px; height:14px; }
    .pe-btn-cancel {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 0.9rem; background:#fff;
        border:1px solid #D8D4C8; border-radius:0.5rem;
        color:#5A5248; font-size:0.78rem; font-weight:600;
        font-family:'Outfit',sans-serif; cursor:pointer;
        text-decoration:none; transition:all .15s;
    }
    .pe-btn-cancel:hover { background:#F7F5EE; border-color:#C4BDB2; color:#1a1207; }

    /* Media Upload Styles */
    .mu-preview-card { background:#fff; border:1px solid #E2DDD3; border-radius:0.65rem; padding:0.75rem; overflow:hidden; }
    .mu-preview-label { font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; color:#9B9589; margin-bottom:0.4rem; display:block; }
    .mu-dropzone { position:relative; background:#F7F5EE; border:2px dashed #D8D4C8; border-radius:0.75rem; padding:1.5rem; text-align:center; transition:all 0.18s; cursor:pointer; }
    .mu-dropzone.dragging { border-color:#6829AA; background:#F3ECFF; }
    .mu-dropzone:hover { border-color:#C4BDB2; }
    .mu-dropzone-icon { width:2.5rem; height:2.5rem; border-radius:50%; background:#EEE6FF; display:flex; align-items:center; justify-content:center; margin:0 auto 0.6rem; color:#6829AA; }
    .mu-dropzone-title { font-family:'Outfit',sans-serif; font-size:0.85rem; font-weight:700; color:#1a1207; margin-bottom:0.15rem; }
    .mu-dropzone-sub { font-size:0.72rem; color:#9B9589; }

    .media-overlay-actions { position:absolute; top:0.5rem; right:0.5rem; display:flex; gap:0.25rem; opacity:0; transition:opacity 0.2s; z-index:10; }
    .media-preview-container { position:relative; }
    .media-preview-container:hover .media-overlay-actions { opacity:1; }
    .media-action-btn { background:rgba(255,255,255,0.9); color:#6829AA; border:none; padding:0.4rem; border-radius:0.35rem; cursor:pointer; box-shadow:0 2px 4px rgba(0,0,0,0.1); transition:all 0.15s; display:flex; align-items:center; justify-content:center; }
    .media-action-btn:hover { background:#fff; transform:translateY(-1px); box-shadow:0 4px 6px rgba(0,0,0,0.1); }
    .media-action-btn.delete { color:#DC2626; }

    /* Responsive */
    @media (max-width:900px) {
        .pe-shell { flex-direction:column; height:auto; overflow:visible; }
        .pe-sidebar { width:100%; }
        .pe-editor-col { min-height:60vh; }
    }

    .pe-block.dragging { opacity:0.4; }
    .pe-block.drag-over { border-top:2px solid #6829AA; }

    [x-cloak] { display:none !important; }
</style>

{{-- Back link --}}
<a href="{{ route('admin.experiences.index') }}" class="pe-back">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Experiences
</a>

<form action="{{ route('admin.experiences.store') }}" method="POST" enctype="multipart/form-data"
      id="experience-form"
      x-data="notionEditorCreate()"
      @submit.prevent="submitForm($event)">
    @csrf

    <div class="pe-shell">

        {{-- ═══ LEFT: Editor Column ═══ --}}
        <div class="pe-editor-col">

            {{-- Experience Info --}}
            <div class="pe-editor-header">
                <input type="text" name="role" class="pe-title-input"
                       placeholder="Job Title"
                       value="{{ old('role') }}" required>
                @error('role') <p class="pe-field-err">{{ $message }}</p> @enderror

                <input type="text" name="company" class="pe-subtitle-input"
                       placeholder="Company Name"
                       value="{{ old('company') }}" required>
                @error('company') <p class="pe-field-err">{{ $message }}</p> @enderror

                <input type="text" name="duration" class="pe-subtitle-input"
                       placeholder="Duration (e.g. 2022 - Present)"
                       value="{{ old('duration') }}" required>
                @error('duration') <p class="pe-field-err">{{ $message }}</p> @enderror
            </div>

            <div class="pe-editor-divider"></div>

            {{-- Notion Block Editor --}}
            <div class="pe-blocks-scroll" id="blocks-container">
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div class="pe-block"
                         :class="{
                             'bullet-block': block.type === 'bullet',
                             'numbered-block': block.type === 'numbered',
                             'image-block': block.type === 'image',
                             'dragging': dragId === block.id,
                             'drag-over': dragOverId === block.id
                         }"
                         :data-block-id="block.id"
                         draggable="true"
                         @dragstart="startDrag(block.id, $event)"
                         @dragend="endDrag()"
                         @dragover.prevent="dragOverId = block.id"
                         @dragleave="if(dragOverId === block.id) dragOverId = null"
                         @drop.prevent="dropBlock(block.id)">

                        {{-- Drag handle --}}
                        <div class="pe-block-handle"
                             @click.stop="openBlockMenu(block.id, $event)"
                             title="Drag to reorder · Click for options">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                        </div>

                        {{-- Bullet marker --}}
                        <template x-if="block.type === 'bullet'">
                            <div class="pe-block-bullet">•</div>
                        </template>

                        {{-- Numbered marker --}}
                        <template x-if="block.type === 'numbered'">
                            <div class="pe-block-number" x-text="getNumberIndex(index) + '.'"></div>
                        </template>

                        {{-- Divider --}}
                        <template x-if="block.type === 'divider'">
                            <div class="pe-block-content" data-type="divider"></div>
                        </template>

                        {{-- Image block --}}
                        <template x-if="block.type === 'image'">
                            <div class="pe-block-image-wrap">
                                <template x-if="!block.src">
                                    <div class="pe-block-image-upload"
                                         @click="triggerImageUpload(block.id)">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Click to upload image</span>
                                    </div>
                                </template>
                                <template x-if="block.src">
                                    <div style="position:relative;">
                                        <div x-data="{ dragging: false, startX: 0, startY: 0, posX: block.posX || 50, posY: block.posY || 50 }"
                                             class="pe-block-image-preview" 
                                             @mousedown="if(block.ratio && block.ratio !== 'auto') { dragging = true; startX = $event.clientX; startY = $event.clientY; }"
                                             @mousemove.window="if(dragging) { 
                                                 let dx = $event.clientX - startX; 
                                                 let dy = $event.clientY - startY; 
                                                 posX = Math.max(0, Math.min(100, posX - (dx * 0.3))); 
                                                 posY = Math.max(0, Math.min(100, posY - (dy * 0.3))); 
                                                 startX = $event.clientX; startY = $event.clientY; 
                                                 block.posX = Math.round(posX); block.posY = Math.round(posY); 
                                             }"
                                             @mouseup.window="dragging = false"
                                             :style="(block.ratio === '3:4' ? 'aspect-ratio: 3/4;' : (block.ratio === '16:9' ? 'aspect-ratio: 16/9;' : '')) + (block.ratio && block.ratio !== 'auto' ? ' cursor:grab;' : '')">
                                            <img :src="block.src" :alt="block.caption || 'Image'" 
                                                 :style="(block.ratio && block.ratio !== 'auto') ? ('width: 100%; height: 100%; object-fit: cover; object-position: ' + (block.posX || 50) + '% ' + (block.posY || 50) + '%; pointer-events:none;') : ''">
                                            <div x-show="block.ratio && block.ratio !== 'auto'" 
                                                 style="position:absolute; bottom:0.5rem; left:0.5rem; background:rgba(0,0,0,0.5); color:#fff; font-size:0.6rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; pointer-events:none; backdrop-filter:blur(4px);">
                                                <span x-text="'Drag to pan (' + (block.posX || 50) + '% ' + (block.posY || 50) + '%)'"></span>
                                            </div>
                                        </div>
                                        <div style="position:absolute; top:0.5rem; right:0.5rem; display:flex; gap:0.25rem; background:rgba(0,0,0,0.5); padding:0.25rem; border-radius:0.5rem; backdrop-filter:blur(4px);">
                                            <button type="button" @click="block.ratio = 'auto'" :style="(!block.ratio || block.ratio === 'auto') ? 'background:#fff; color:#000;' : 'color:#fff;'" style="font-size:0.65rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; font-weight:bold;">AUTO</button>
                                            <button type="button" @click="block.ratio = '16:9'" :style="block.ratio === '16:9' ? 'background:#fff; color:#000;' : 'color:#fff;'" style="font-size:0.65rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; font-weight:bold;">16:9</button>
                                            <button type="button" @click="block.ratio = '3:4'" :style="block.ratio === '3:4' ? 'background:#fff; color:#000;' : 'color:#fff;'" style="font-size:0.65rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; font-weight:bold;">3:4</button>
                                        </div>
                                        <input type="text" class="pe-block-image-caption"
                                               placeholder="Add a caption…"
                                               :value="block.caption || ''"
                                               @input="block.caption = $event.target.value">
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Video block (local upload or embed) --}}
                        <template x-if="block.type === 'video'">
                            <div style="width:100%;">
                                <template x-if="block.src">
                                    <div style="position:relative;">
                                        <div style="position:relative;width:100%;border-radius:0.5rem;overflow:hidden;border:1px solid #E2DDD3;" 
                                             :style="(block.ratio === '3:4') ? 'aspect-ratio: 3/4;' : 'aspect-ratio: 16/9;'">
                                            <template x-if="isEmbedUrl(block.src)">
                                                <iframe :src="getEmbedUrl(block.src)" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
                                            </template>
                                            <template x-if="!isEmbedUrl(block.src)">
                                                <video :src="block.src" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;" controls playsinline></video>
                                            </template>
                                        </div>
                                        <div style="position:absolute; top:0.5rem; right:0.5rem; display:flex; gap:0.25rem; background:rgba(0,0,0,0.5); padding:0.25rem; border-radius:0.5rem; backdrop-filter:blur(4px);">
                                            <button type="button" @click="block.ratio = '16:9'" :style="(!block.ratio || block.ratio === '16:9') ? 'background:#fff; color:#000;' : 'color:#fff;'" style="font-size:0.65rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; font-weight:bold;">16:9</button>
                                            <button type="button" @click="block.ratio = '3:4'" :style="block.ratio === '3:4' ? 'background:#fff; color:#000;' : 'color:#fff;'" style="font-size:0.65rem; padding:0.2rem 0.4rem; border-radius:0.25rem; font-family:'Space Mono',monospace; font-weight:bold;">3:4</button>
                                        </div>
                                        <input type="text" class="pe-block-image-caption"
                                               placeholder="Add a caption…"
                                               :value="block.caption || ''"
                                               @input="block.caption = $event.target.value">
                                    </div>
                                </template>
                                <template x-if="!block.src">
                                    <div style="display:flex; flex-direction:column; gap:0.35rem;">
                                        <div class="pe-block-image-upload"
                                             @click="triggerVideoUpload(block.id)">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Upload your own video file</span>
                                        </div>
                                        <div class="pe-block-image-upload" style="min-height:60px;"
                                             @click="promptVideoUrl(block.id)">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            <span>Or paste YouTube / Vimeo URL</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Text-based blocks --}}
                        <template x-if="['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(block.type)">
                            <div class="pe-block-content"
                                 contenteditable="true"
                                 :data-type="block.type"
                                 :data-placeholder="getPlaceholder(block.type, index)"
                                 :data-block-id="block.id"
                                 @input="handleInput(block, $event)"
                                 @keydown="handleKeydown(block, index, $event)"
                                 @focus="activeBlockId = block.id"
                                 @blur="handleBlur(block, $event)"
                                 @paste="handlePaste(block, $event)"
                                 x-effect="if(block._needsFocus) { $nextTick(() => { $el.focus(); block._needsFocus = false; }) }"
                                 x-init="$el.innerHTML = block.content || ''">
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Click area below blocks --}}
                <div style="min-height:120px; cursor:text; padding:0 1rem;"
                     @click="addBlockAtEnd()"></div>
            </div>

            {{-- Editor footer --}}
            <div class="pe-footer">
                <a href="{{ route('admin.experiences.index') }}" class="pe-btn-cancel">Cancel</a>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <span style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#9B9589; text-transform:uppercase; letter-spacing:0.1em;"
                          x-text="blocks.length + ' blocks'"></span>
                    <button type="submit" class="pe-btn-save" :disabled="isSubmitting">
                        <svg x-show="isSubmitting" class="animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                            <circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Publishing…' : 'Publish Experience'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT: Sidebar ═══ --}}
        <div class="pe-sidebar" x-data="{ tab: 'media', is_active: false, bg_media_type: 'image' }">
            <div class="pe-sidebar-header">
                <span style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; color:#9B9589;">Experience Details</span>
                <span style="font-family:'Space Mono',monospace; font-size:0.58rem; color:#0A8C5E; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;">New</span>
            </div>

            <div class="pe-sidebar-scroll">

                {{-- Toggles --}}
                <div class="flex gap-2" style="margin-bottom: 0.65rem;">
                    <!-- Active Toggle -->
                    <div class="pe-featured-row">
                        <input type="hidden" name="is_active" :value="is_active ? '1' : '0'">
                        <button type="button" @click="is_active = !is_active" 
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-full border transition-all duration-200"
                                :class="is_active ? 'bg-[#F3ECFF] border-[#6829AA] text-[#6829AA]' : 'bg-[#E2DDD3] border-transparent text-[#9B9589] hover:bg-[#D5D0C6]'">
                            <span class="font-sans text-[10px] font-bold uppercase tracking-widest" x-text="is_active ? 'Active (Present)' : 'Past Experience'"></span>
                        </button>
                    </div>
                </div>
                
                {{-- Tabs Header --}}
                <div style="display:flex; justify-content:space-between; align-items:flex-end; border-bottom: 1px solid #D8D4C8; margin-bottom: 0.85rem; position:relative;">
                    <div style="display:flex; gap:1.25rem;">
                        <button type="button" @click="tab = 'media'" 
                                style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:0.5rem; background:transparent; border:none; cursor:pointer; position:relative; transition:color 0.2s;"
                                :style="{ color: tab === 'media' ? '#1a1207' : '#B0A99F', fontWeight: tab === 'media' ? '700' : '600' }">
                            Timeline Image
                            <div x-show="tab === 'media'" style="position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#1a1207; border-radius:1px;"></div>
                        </button>
                        <button type="button" @click="tab = 'bg'" 
                                style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:0.5rem; background:transparent; border:none; cursor:pointer; position:relative; transition:color 0.2s;"
                                :style="{ color: tab === 'bg' ? '#1a1207' : '#B0A99F', fontWeight: tab === 'bg' ? '700' : '600' }">
                            Background Media
                            <div x-show="tab === 'bg'" style="position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#1a1207; border-radius:1px;"></div>
                        </button>
                    </div>
                </div>

                {{-- MEDIA TAB --}}
                <div x-show="tab === 'media'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display:none;">
                    <div class="pe-field">
                        <div class="mu-preview-card" style="margin-top:0.5rem; position: relative;"
                             :style="isDraggingThumb ? 'border: 2px dashed #6829AA; background: #F3ECFF;' : ''"
                             @dragover.prevent="isDraggingThumb = true" @dragleave.prevent="isDraggingThumb = false"
                             @drop.prevent="isDraggingThumb = false; if($event.dataTransfer.files.length) { let f = $event.dataTransfer.files[0]; if(f.type.startsWith('image/')){ document.getElementById('timeline_image_upload').files = $event.dataTransfer.files; thumbPreview = URL.createObjectURL(f); } }">
                             
                            <input type="hidden" name="image_base64" :value="thumbPreview && thumbPreview.startsWith('data:') ? thumbPreview : ''">
                            <input type="file" name="image" id="timeline_image_upload" accept="image/*" style="display:none;" @change="if($event.target.files.length) thumbPreview = URL.createObjectURL($event.target.files[0])">
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:0.6rem;">
                                <span class="mu-preview-label" style="margin: 0; color:#B0A99F;">Timeline Thumbnail (Image)</span>
                                <div style="display:flex; gap:0.5rem;">
                                    <button type="button" @click="document.getElementById('timeline_image_upload').click()" style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:#6829AA; background:transparent; border:none; cursor:pointer; font-weight:700;" x-show="thumbPreview">
                                        <svg style="width:11px; height:11px; display:inline; margin-bottom:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> CHANGE IMAGE
                                    </button>
                                </div>
                            </div>

                            <div x-show="!thumbPreview" class="mu-dropzone" style="margin-top: 0; border: 2px dashed #D8D4C8; border-radius:0.5rem; background:#F7F5EE; padding:2rem 1.5rem; text-align:center; cursor:pointer; min-height: 140px;" @click="document.getElementById('timeline_image_upload').click()">
                                <div style="pointer-events:none;">
                                    <div class="mu-dropzone-icon" style="width:2.5rem; height:2.5rem; border-radius:50%; background:#EEE6FF; display:flex; align-items:center; justify-content:center; margin:0 auto 0.6rem; color:#6829AA;"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg></div>
                                    <p class="mu-dropzone-title" style="font-family:'Outfit',sans-serif; font-size:0.85rem; font-weight:700; color:#1a1207; margin-bottom:0.15rem;">Upload Thumbnail</p>
                                    <p class="mu-dropzone-sub" style="font-size:0.72rem; color:#9B9589;">Drop image here</p>
                                </div>
                            </div>

                            <div x-show="thumbPreview" style="display:none;" class="media-preview-container">
                                <div style="position:relative; border-radius:0.5rem; border:1px solid #E2DDD3; overflow:hidden;">
                                    <img :src="thumbPreview" style="width:100%;max-height:200px;object-fit:contain;display:block;">
                                    <div class="media-overlay-actions">
                                        <button class="media-action-btn" type="button" @click="openCrop('thumb')" title="Crop Image">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p style="font-size:0.52rem; color:#9B9589; margin-top:0.3rem; font-family:'Space Mono',monospace;">Appears as the small thumbnail on the timeline card.</p>
                    </div>
                </div>

                {{-- BACKGROUND MEDIA TAB --}}
                <div x-show="tab === 'bg'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display:none;">
                    
                    <div class="pe-field-row">
                        <div class="pe-field">
                            <label class="pe-field-label">Background Type</label>
                            <select name="bg_media_type" x-model="bg_media_type" class="pe-field-select">
                                <option value="image">Single Image</option>
                                <option value="video">MP4 Video</option>
                                <option value="slideshow">Image Slideshow</option>
                            </select>
                        </div>
                    </div>

                    <div class="pe-field" x-show="bg_media_type === 'image' || bg_media_type === 'video'" style="margin-top:0.75rem;" x-effect="if(!bgPreviewUrl) bgPreviewType = bg_media_type">
                        <div class="mu-preview-card" style="position: relative;"
                             :style="isDraggingBg ? 'border: 2px dashed #6829AA; background: #F3ECFF;' : ''"
                             @dragover.prevent="isDraggingBg = true" @dragleave.prevent="isDraggingBg = false"
                             @drop.prevent="isDraggingBg = false; if($event.dataTransfer.files.length) { let f = $event.dataTransfer.files[0]; if((bg_media_type === 'video' && f.type.startsWith('video/')) || (bg_media_type === 'image' && f.type.startsWith('image/'))){ document.getElementById('bg_media_upload').files = $event.dataTransfer.files; bgPreviewUrl = URL.createObjectURL(f); bgPreviewType = bg_media_type; } }">
                             
                            <input type="hidden" name="bg_media_base64" :value="bgPreviewUrl && bgPreviewUrl.startsWith('data:') ? bgPreviewUrl : ''">
                            <input type="file" name="bg_media_file" id="bg_media_upload" style="display:none;" :accept="bg_media_type === 'video' ? 'video/mp4,video/webm' : 'image/*'" @change="if($event.target.files.length) { bgPreviewUrl = URL.createObjectURL($event.target.files[0]); bgPreviewType = bg_media_type; }">
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:0.6rem;">
                                <span class="mu-preview-label" style="margin: 0; color:#B0A99F;" x-text="bg_media_type === 'video' ? 'Background Video' : 'Background Image'"></span>
                                <div style="display:flex; gap:0.5rem;">
                                    <button type="button" @click="document.getElementById('bg_media_upload').click()" style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:#6829AA; background:transparent; border:none; cursor:pointer; font-weight:700;" x-show="bgPreviewUrl">
                                        <svg style="width:11px; height:11px; display:inline; margin-bottom:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> CHANGE MEDIA
                                    </button>
                                </div>
                            </div>

                            <div x-show="!bgPreviewUrl || bgPreviewType !== bg_media_type" class="mu-dropzone" style="margin-top: 0; border: 2px dashed #D8D4C8; border-radius:0.5rem; background:#F7F5EE; padding:2rem 1.5rem; text-align:center; cursor:pointer; min-height: 140px;" @click="document.getElementById('bg_media_upload').click()">
                                <div style="pointer-events:none;">
                                    <div class="mu-dropzone-icon" style="width:2.5rem; height:2.5rem; border-radius:50%; background:#EEE6FF; display:flex; align-items:center; justify-content:center; margin:0 auto 0.6rem; color:#6829AA;"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg></div>
                                    <p class="mu-dropzone-title" style="font-family:'Outfit',sans-serif; font-size:0.85rem; font-weight:700; color:#1a1207; margin-bottom:0.15rem;" x-text="bg_media_type === 'video' ? 'Upload MP4 Video' : 'Upload Background Image'"></p>
                                    <p class="mu-dropzone-sub" style="font-size:0.72rem; color:#9B9589;" x-text="bg_media_type === 'video' ? 'Drop video file here' : 'Drop image here'"></p>
                                </div>
                            </div>

                            <div x-show="bgPreviewUrl && bgPreviewType === bg_media_type" style="display:none;" class="media-preview-container">
                                <div style="position:relative; border-radius:0.5rem; border:1px solid #E2DDD3; overflow:hidden;">
                                    <template x-if="bg_media_type === 'image'">
                                        <img :src="bgPreviewUrl" style="width:100%;max-height:200px;object-fit:contain;display:block;">
                                    </template>
                                    <template x-if="bg_media_type === 'video'">
                                        <video :src="bgPreviewUrl" controls style="width:100%;max-height:200px;object-fit:contain;background:#000;display:block;"></video>
                                    </template>
                                    
                                    <div class="media-overlay-actions" x-show="bg_media_type === 'image'">
                                        <button class="media-action-btn" type="button" @click="openCrop('bg')" title="Crop Image">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pe-field" x-show="bg_media_type === 'slideshow'" style="margin-top:0.75rem;">
                        <input type="hidden" name="reordered_bg_gallery" id="reordered_bg_gallery">
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                            <label class="pe-field-label" style="margin:0;">Slideshow Images</label>
                            <button type="button" @click="triggerSlideshowUpload" class="pe-btn-cancel" style="padding:0.3rem 0.6rem; font-size:0.65rem;" x-show="slides.length > 0">
                                + Add Image
                            </button>
                        </div>

                        <div id="slideshow-sortable" style="display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1rem;" x-show="slides.length > 0">
                            <template x-for="(slide, index) in slides" :key="slide.id">
                                <div class="slide-item" style="display:flex; align-items:center; gap:0.75rem; background:#fff; padding:0.4rem; border-radius:0.4rem; border:1px solid #D8D4C8; box-shadow:0 1px 2px rgba(0,0,0,0.02);">
                                    <div class="slide-drag-handle" style="cursor:grab; padding:0.2rem; color:#C4BDB2;" title="Drag to reorder">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                                    </div>
                                    <div style="font-family:'Space Mono', monospace; font-size:0.65rem; font-weight:bold; color:#9B9589; width:1.2rem; text-align:center;">
                                        <span x-text="'#' + (index + 1)"></span>
                        <div id="slideshow-sortable" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap:0.5rem; margin-bottom:1rem;" x-show="slides.length > 0">
                            <template x-for="(slide, index) in slides" :key="index">
                                <div style="position:relative; border-radius:0.5rem; border:1px solid #E2DDD3; overflow:hidden; background:#F7F5EE; display:flex; flex-direction:column;" class="media-preview-container">
                                    <div style="height:100px; display:flex; align-items:center; justify-content:center; background:#000;">
                                        <img :src="slide.croppedBase64 || slide.url" style="max-width:100%; max-height:100px; object-fit:contain;">
                                    </div>
                                    <div style="padding:0.5rem; background:#fff; display:flex; justify-content:space-between; align-items:center; flex:1;">
                                        <div style="flex:1; overflow:hidden;">
                                            <p style="font-size:0.6rem; font-family:'Space Mono', monospace; color:#6829AA; margin:0; font-weight:600;" x-text="slide.type === 'new' ? 'New Upload' : 'Saved Image'"></p>
                                        </div>
                                    </div>
                                    <div class="media-overlay-actions">
                                        <button type="button" class="media-action-btn" @click="openCrop('slide', index)" title="Crop Image">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg>
                                        </button>
                                        <button type="button" class="media-action-btn delete" @click="removeSlide(index)" title="Delete Image">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="slides.length === 0" class="pe-block-image-upload" @click="triggerSlideshowUpload" style="margin-bottom:1rem;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span style="font-size:0.7rem;">Click to add images to the slideshow</span>
                        </div>

                        <input type="file" id="slideshow-upload-input" accept="image/*" multiple @change="handleSlideshowFile" style="display:none;">
                    </div>

                </div>

            </div>
        </div>

    </div>{{-- /pe-shell --}}

    {{-- Hidden fields --}}
    <input type="hidden" name="body_content" id="body_content">
    @error('body_content') <p class="pe-field-err">{{ $message }}</p> @enderror
    {{-- Slash command menu --}}
    <div class="pe-slash-menu" id="slash-menu" x-show="slashMenuOpen" :style="`left: ${slashLeft}px; top: ${slashTop}px;`" @mousedown.prevent @click.stop x-cloak>
        <div class="pe-slash-header">Blocks</div>
        <template x-for="(item, i) in filteredSlashItems()" :key="item.type">
            <div class="pe-slash-item" :class="i === slashActiveIndex ? 'active' : ''"
                 @mouseenter="slashActiveIndex = i"
                 @click="selectSlashItem(item.type)">
                <div class="pe-slash-icon" x-html="item.icon"></div>
                <div>
                    <div class="pe-slash-label" x-text="item.label"></div>
                    <div class="pe-slash-desc" x-text="item.desc"></div>
                </div>
            </div>
        </template>
        <template x-if="filteredSlashItems().length === 0">
            <div style="padding:0.75rem;text-align:center;font-size:0.72rem;color:#9B9589;">No blocks found</div>
        </template>
    </div>

</form>

    {{-- Cropper Modal --}}
    <div x-show="cropModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);" x-cloak>
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-4xl flex flex-col" @click.stop style="background:#fff; border-radius:1rem; width:100%; max-width:800px;">
            <div style="padding:1.5rem; border-bottom:1px solid #E2DDD3; display:flex; justify-content:space-between; align-items:center; background:#F7F5EE;">
                <h3 style="font-family:'Outfit',sans-serif; font-size:1.25rem; font-weight:800; color:#1a1207; margin:0;">Crop Image</h3>
                <button type="button" @click="closeCrop()" style="background:none; border:none; color:#9B9589; cursor:pointer;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div style="padding:1.5rem; background:#111; height:450px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                <div style="width:100%; height:100%; max-height:100%;">
                    <img id="cropper-image" src="" style="max-width:100%; max-height:100%; display:block; margin:0 auto;">
                </div>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid #E2DDD3; background:#F7F5EE; display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" @click="closeCrop()" class="pe-btn-cancel">Cancel</button>
                <button type="button" @click="saveCrop()" class="pe-btn-save">Apply Crop</button>
            </div>
        </div>
    </div>

{{-- Inline formatting toolbar --}}
<div class="pe-fmt-toolbar" id="fmt-toolbar" style="display:none;" x-cloak>
    <button type="button" class="pe-fmt-btn" data-fmt="bold" title="Bold (Ctrl+B)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path></svg>
    </button>
    <button type="button" class="pe-fmt-btn" data-fmt="italic" title="Italic (Ctrl+I)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2 0l-4 16m-2 0h4m6-16h-4"/></svg>
    </button>
    <button type="button" class="pe-fmt-btn" data-fmt="strikethrough" title="Strikethrough">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4H9a3 3 0 000 6h6a3 3 0 010 6H8m8-12V4M8 18v2m-4-8h16"/></svg>
    </button>
    <div class="pe-fmt-divider"></div>
    <button type="button" class="pe-fmt-btn" data-fmt="link" title="Add Link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
    </button>
</div>

{{-- Hidden file input for image blocks --}}
<input type="file" id="block-image-upload" accept="image/*" style="display:none;">
{{-- Hidden file input for video blocks --}}
<input type="file" id="block-video-upload" accept="video/mp4,video/webm,video/mov,video/quicktime" style="display:none;">

<script>
function notionEditorCreate() {
    return {
        blocks: [{ id: 'b_initial', type: 'paragraph', content: '', _needsFocus: true }],
        activeBlockId: null,
        isSubmitting: false,
        slashMenuOpen: false,
        slashQuery: '',
        slashBlockId: null,
        slashActiveIndex: 0,
        slashLeft: 0,
        slashTop: 0,
        dragId: null,
        dragOverId: null,
        fmtVisible: false,
        _isTransforming: false,

        // Media variables
        isDraggingThumb: false,
        thumbPreview: null,
        isDraggingBg: false,
        bgPreviewUrl: null,
        bgPreviewType: null,

        // Slideshow & cropper variables
        slides: [],
        cropper: null,
        cropModalOpen: false,
        cropTarget: null, // 'thumb', 'bg', 'slide'
        currentCropIndex: null,

        blockTypes: [
            { type:'paragraph', label:'Text', desc:'Plain text block', icon:'Aa' },
            { type:'heading2', label:'Heading 2', desc:'Large section heading', icon:'H2' },
            { type:'heading3', label:'Heading 3', desc:'Small section heading', icon:'H3' },
            { type:'bullet', label:'Bullet List', desc:'Unordered list item', icon:'•' },
            { type:'numbered', label:'Numbered List', desc:'Ordered list item', icon:'1.' },
            { type:'quote', label:'Quote', desc:'Blockquote callout', icon:'❝' },
            { type:'code', label:'Code', desc:'Code snippet block', icon:'<>' },
            { type:'image', label:'Image', desc:'Upload or embed image', icon:'🖼' },
            { type:'video', label:'Video', desc:'Upload video or embed URL', icon:'▶' },
            { type:'divider', label:'Divider', desc:'Horizontal separator', icon:'—' },
        ],

        init() {
            this.setupSlashMenu();
            this.setupFmtToolbar();
            this.initSlideshow();
            document.getElementById('block-image-upload').addEventListener('change', (e) => this.handleImageFile(e));
            document.getElementById('block-video-upload').addEventListener('change', (e) => this.handleVideoFile(e));
        },

        generateId() {
            return 'b_' + Math.random().toString(36).substr(2, 9);
        },

        getPlaceholder(type, index) {
            if (index === 0 && this.blocks.length === 1 && type === 'paragraph') {
                return "Type '/' for commands, or start writing your case study...";
            }
            const map = {
                paragraph: "Type '/' for commands...",
                heading2: 'Heading 2',
                heading3: 'Heading 3',
                quote: 'Write a quote...',
                code: 'Paste code...',
                bullet: 'List item',
                numbered: 'List item',
            };
            return map[type] || '';
        },

        getNumberIndex(blockIndex) {
            let count = 0;
            for (let i = 0; i <= blockIndex; i++) {
                if (this.blocks[i].type === 'numbered') count++;
            }
            return count;
        },

        handleInput(block, event) {
            if (this._isTransforming) return;
            block.content = event.target.innerHTML;
            
            let text = this.getTextContent(event.target);
            if (text.startsWith('/')) {
                let query = text.substring(1);
                if (this.slashQuery !== query) {
                    this.slashActiveIndex = 0;
                }
                this.slashQuery = query;
                this.slashBlockId = block.id;
                this.openSlashMenu(event.target);
            } else if (this.slashMenuOpen) {
                this.closeSlashMenu();
            }
        },

        handleBlur(block, event) {
            if (this._isTransforming) return;
            // Don't save if the block is no longer in the array (was replaced)
            if (!this.blocks.find(b => b.id === block.id)) return;
            block.content = event.target.innerHTML;
        },

        handleKeydown(block, index, event) {
            if (this.slashMenuOpen) {
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    this.slashActiveIndex = Math.min(this.slashActiveIndex + 1, this.filteredSlashItems().length - 1);
                    return;
                }
                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.slashActiveIndex = Math.max(this.slashActiveIndex - 1, 0);
                    return;
                }
                if (event.key === 'Enter') {
                    event.preventDefault();
                    let items = this.filteredSlashItems();
                    if (items[this.slashActiveIndex]) {
                        this.selectSlashItem(items[this.slashActiveIndex].type);
                        return;
                    }
                    this.closeSlashMenu();
                }
                if (event.key === 'Escape') {
                    this.closeSlashMenu();
                    return;
                }
            }

            if (event.key === 'ArrowUp' && !this.slashMenuOpen) {
                let sel = window.getSelection();
                if (sel.rangeCount > 0) {
                    let rects = sel.getRangeAt(0).getClientRects();
                    let cursorTop = rects.length > 0 ? rects[0].top : event.target.getBoundingClientRect().top;
                    if (cursorTop - event.target.getBoundingClientRect().top < 25) {
                        event.preventDefault();
                        if (index > 0) {
                            let prevEl = document.querySelector(`[data-block-id="${this.blocks[index-1].id}"]`);
                            if (prevEl && prevEl.isContentEditable) {
                                prevEl.focus();
                                this.setCursorEnd(prevEl);
                            }
                        }
                    }
                }
                return;
            }

            if (event.key === 'ArrowDown' && !this.slashMenuOpen) {
                let sel = window.getSelection();
                if (sel.rangeCount > 0) {
                    let rects = sel.getRangeAt(0).getClientRects();
                    let cursorBottom = rects.length > 0 ? rects[rects.length-1].bottom : event.target.getBoundingClientRect().bottom;
                    if (event.target.getBoundingClientRect().bottom - cursorBottom < 25) {
                        event.preventDefault();
                        if (index < this.blocks.length - 1) {
                            let nextEl = document.querySelector(`[data-block-id="${this.blocks[index+1].id}"]`);
                            if (nextEl && nextEl.isContentEditable) {
                                nextEl.focus();
                                let newRange = document.createRange();
                                newRange.selectNodeContents(nextEl);
                                newRange.collapse(true);
                                let newSel = window.getSelection();
                                newSel.removeAllRanges();
                                newSel.addRange(newRange);
                            }
                        }
                    }
                }
                return;
            }

            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                let sel = window.getSelection();
                let leftHtml = event.target.innerHTML;
                let rightHtml = '';

                if (sel.rangeCount > 0) {
                    let range = sel.getRangeAt(0);
                    let postCaretRange = range.cloneRange();
                    postCaretRange.selectNodeContents(event.target);
                    postCaretRange.setStart(range.endContainer, range.endOffset);
                    let postFrag = postCaretRange.extractContents();
                    let tmp = document.createElement('div');
                    tmp.appendChild(postFrag);
                    rightHtml = tmp.innerHTML;
                    leftHtml = event.target.innerHTML;
                }

                block.content = leftHtml;
                event.target.innerHTML = leftHtml;
                
                let newBlock = { id: this.generateId(), type: 'paragraph', content: rightHtml, _needsFocus: true };
                this.blocks.splice(index + 1, 0, newBlock);
                return;
            }

            if (event.key === 'Backspace' && this.blocks.length > 1) {
                let sel = window.getSelection();
                let isAtStart = false;
                if (sel.rangeCount > 0) {
                    let range = sel.getRangeAt(0);
                    let preCaretRange = range.cloneRange();
                    preCaretRange.selectNodeContents(event.target);
                    preCaretRange.setEnd(range.startContainer, range.startOffset);
                    isAtStart = preCaretRange.toString().length === 0;
                }

                if (isAtStart) {
                    event.preventDefault();
                    if (this.slashMenuOpen) this.closeSlashMenu();

                    if (index > 0) {
                        let prevBlock = this.blocks[index-1];
                        let currentHtml = event.target.innerHTML;
                        let textTypes = ['paragraph','heading2','heading3','quote','code','bullet','numbered'];
                        
                        if (textTypes.includes(prevBlock.type)) {
                            this.$nextTick(() => {
                                let prevEl = document.querySelector(`[data-block-id="${prevBlock.id}"]`);
                                if (prevEl) {
                                    prevEl.focus();
                                    let range = document.createRange();
                                    range.selectNodeContents(prevEl);
                                    range.collapse(false);
                                    let sel = window.getSelection();
                                    sel.removeAllRanges();
                                    sel.addRange(range);

                                    if (currentHtml !== '' && currentHtml !== '<br>') {
                                        let tmp = document.createElement('div');
                                        tmp.innerHTML = currentHtml;
                                        let frag = document.createDocumentFragment();
                                        while(tmp.firstChild) frag.appendChild(tmp.firstChild);
                                        range.insertNode(frag);
                                        prevBlock.content = prevEl.innerHTML;
                                    }
                                }
                            });
                            this.blocks.splice(index, 1);
                        } else {
                            if (this.getTextContent(event.target) === '') {
                                this.blocks.splice(index, 1);
                            }
                        }
                    }
                    return;
                }
            }

            if (event.key === ' ') {
                let text = this.getTextContent(event.target);
                const shortcuts = {
                    '##': 'heading2',
                    '###': 'heading3',
                    '-': 'bullet',
                    '*': 'bullet',
                    '1.': 'numbered',
                    '>': 'quote',
                    '```': 'code',
                    '---': 'divider',
                };
                if (shortcuts[text]) {
                    event.preventDefault();
                    block.type = shortcuts[text];
                    block.content = '';
                    this.$nextTick(() => {
                        let el = document.querySelector(`[data-block-id="${block.id}"]`);
                        if (el) {
                            el.innerHTML = '';
                            el.focus();
                        }
                    });
                    return;
                }
            }
        },

        handlePaste(block, event) {
            event.preventDefault();
            let text = event.clipboardData.getData('text/plain');
            if (!text) return;
            
            let lines = text.split(/\r?\n/).filter(line => line.trim() !== '');
            if (lines.length === 0) return;
            
            document.execCommand('insertText', false, lines[0]);
            
            if (lines.length > 1) {
                let idx = this.blocks.findIndex(b => b.id === block.id);
                let newBlocks = [];
                for (let i = 1; i < lines.length; i++) {
                    newBlocks.push({ id: this.generateId(), type: 'paragraph', content: lines[i], _needsFocus: i === lines.length - 1 });
                }
                this.blocks.splice(idx + 1, 0, ...newBlocks);
            }
        },

        getTextContent(el) {
            return (el.textContent || el.innerText || '').trim();
        },

        setCursorEnd(el) {
            let range = document.createRange();
            let sel = window.getSelection();
            range.selectNodeContents(el);
            range.collapse(false);
            sel.removeAllRanges();
            sel.addRange(range);
        },

        addBlockAtEnd() {
            let newBlock = { id: this.generateId(), type: 'paragraph', content: '', _needsFocus: true };
            this.blocks.push(newBlock);
        },

        setupSlashMenu() {
            document.addEventListener('click', (e) => {
                if (this.slashMenuOpen && !e.target.closest('#slash-menu')) {
                    this.closeSlashMenu();
                }
            });
        },

        filteredSlashItems() {
            if (!this.slashQuery) return this.blockTypes;
            let q = this.slashQuery.toLowerCase();
            return this.blockTypes.filter(t =>
                t.label.toLowerCase().includes(q) ||
                t.desc.toLowerCase().includes(q) ||
                t.type.toLowerCase().includes(q)
            );
        },

        openSlashMenu(targetEl) {
            this.slashMenuOpen = true;
            let rect = targetEl.getBoundingClientRect();
            this.slashLeft = rect.left;
            this.slashTop = rect.bottom + 4;
        },

        selectSlashItem(type) {
            let idx = this.blocks.findIndex(b => b.id === this.slashBlockId);
            if (idx === -1) { this.closeSlashMenu(); return; }

            this._isTransforming = true;

            let block = this.blocks[idx];
            let el = document.querySelector(`[data-block-id="${block.id}"]`);
            let remainingText = '';
            if (el) {
                let rawText = this.getTextContent(el);
                let commandStr = '/' + this.slashQuery;
                if (rawText.startsWith(commandStr)) {
                    remainingText = rawText.substring(commandStr.length).trim();
                }
            }

            this.closeSlashMenu();
            this.slashBlockId = null;
            this.slashQuery = '';

            // Create a brand new block — NO _needsFocus to avoid x-effect re-render conflicts
            let newBlock = {
                id: this.generateId(),
                type: type,
                content: remainingText,
            };
            if (['image', 'video'].includes(type)) {
                newBlock.content = '';
                newBlock.src = '';
                newBlock.caption = '';
            }

            // Replace old block
            this.blocks.splice(idx, 1, newBlock);

            // Use setTimeout to let Alpine fully commit the DOM before we touch it
            setTimeout(() => {
                if (['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(type)) {
                    let newEl = document.querySelector(`[data-block-id="${newBlock.id}"]`);
                    if (newEl) {
                        newEl.focus();
                        if (newBlock.content) this.setCursorEnd(newEl);
                    }
                }
                if (type === 'image') {
                    this.triggerImageUpload(newBlock.id);
                }
                if (type === 'video') {
                    this.promptVideoUrl(newBlock.id);
                }
                // Clear transform flag after a brief delay to let all browser events settle
                setTimeout(() => { this._isTransforming = false; }, 50);
            }, 20);
        },

        closeSlashMenu() {
            this.slashMenuOpen = false;
        },

        openBlockMenu(blockId, event) {
            let idx = this.blocks.findIndex(b => b.id === blockId);
            if (idx === -1) return;
            let choice = prompt('Block actions:\n1. Delete\n2. Duplicate\n3. → Text\n4. → Heading 2\n5. → Heading 3\n6. → Quote\n7. → Bullet\n\nEnter number:');
            if (!choice) return;
            let n = parseInt(choice);
            if (n === 1) { this.blocks.splice(idx, 1); if(this.blocks.length === 0) this.addBlockAtEnd(); }
            else if (n === 2) { this.blocks.splice(idx + 1, 0, { ...JSON.parse(JSON.stringify(this.blocks[idx])), id: this.generateId() }); }
            else if (n === 3) this.blocks[idx].type = 'paragraph';
            else if (n === 4) this.blocks[idx].type = 'heading2';
            else if (n === 5) this.blocks[idx].type = 'heading3';
            else if (n === 6) this.blocks[idx].type = 'quote';
            else if (n === 7) this.blocks[idx].type = 'bullet';
        },

        startDrag(id, event) {
            this.dragId = id;
            event.dataTransfer.effectAllowed = 'move';
        },
        endDrag() {
            this.dragId = null;
            this.dragOverId = null;
        },
        dropBlock(targetId) {
            if (!this.dragId || this.dragId === targetId) { this.endDrag(); return; }
            let fromIdx = this.blocks.findIndex(b => b.id === this.dragId);
            let toIdx = this.blocks.findIndex(b => b.id === targetId);
            if (fromIdx === -1 || toIdx === -1) { this.endDrag(); return; }
            let [moved] = this.blocks.splice(fromIdx, 1);
            this.blocks.splice(toIdx, 0, moved);
            this.endDrag();
        },

        _pendingImageBlockId: null,
        triggerImageUpload(blockId) {
            this._pendingImageBlockId = blockId;
            document.getElementById('block-image-upload').click();
        },
        async handleImageFile(event) {
            let file = event.target.files[0];
            if (!file || !this._pendingImageBlockId) return;
            let block = this.blocks.find(b => b.id === this._pendingImageBlockId);
            if (!block) return;

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                let resp = await fetch('{{ route("admin.experiences.upload_body_media") }}', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Image upload failed.');
            }
            event.target.value = '';
            this._pendingImageBlockId = null;
        },

        // ── Video upload & embed ──
        _pendingVideoBlockId: null,
        triggerVideoUpload(blockId) {
            this._pendingVideoBlockId = blockId;
            document.getElementById('block-video-upload').click();
        },
        async handleVideoFile(event) {
            let file = event.target.files[0];
            if (!file || !this._pendingVideoBlockId) return;
            let block = this.blocks.find(b => b.id === this._pendingVideoBlockId);
            if (!block) return;

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                let resp = await fetch('{{ route("admin.experiences.upload_body_media") }}', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Video upload failed.');
            }
            event.target.value = '';
            this._pendingVideoBlockId = null;
        },
        promptVideoUrl(blockId) {
            let url = prompt('Enter YouTube or Vimeo URL:');
            if (!url) return;
            let block = this.blocks.find(b => b.id === blockId);
            if (block) block.src = url;
        },

        isEmbedUrl(url) {
            if (!url) return false;
            return /(?:youtube\.com\/watch\?v=|youtu\.be\/|vimeo\.com\/)/.test(url);
        },

        getEmbedUrl(url) {
            if (!url) return '';
            let ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
            if (ytMatch) return 'https://www.youtube.com/embed/' + ytMatch[1];
            let vmMatch = url.match(/vimeo\.com\/(\d+)/);
            if (vmMatch) return 'https://player.vimeo.com/video/' + vmMatch[1];
            return url;
        },

        setupFmtToolbar() {
            document.addEventListener('selectionchange', () => {
                let sel = window.getSelection();
                if (!sel || sel.isCollapsed || !sel.rangeCount) {
                    this.hideFmtToolbar();
                    return;
                }
                let range = sel.getRangeAt(0);
                let container = range.commonAncestorContainer;
                if (container.nodeType === 3) container = container.parentNode;
                if (!container.closest || !container.closest('.pe-block-content')) {
                    this.hideFmtToolbar();
                    return;
                }
                this.showFmtToolbar(range);
            });

            document.querySelectorAll('.pe-fmt-btn').forEach(btn => {
                btn.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    let fmt = btn.dataset.fmt;
                    if (fmt === 'bold') document.execCommand('bold');
                    else if (fmt === 'italic') document.execCommand('italic');
                    else if (fmt === 'strikethrough') document.execCommand('strikeThrough');
                    else if (fmt === 'link') {
                        let url = prompt('Enter URL:');
                        if (url) document.execCommand('createLink', false, url);
                    }
                });
            });
        },

        showFmtToolbar(range) {
            let toolbar = document.getElementById('fmt-toolbar');
            let rect = range.getBoundingClientRect();
            toolbar.style.left = (rect.left + rect.width / 2 - 80) + 'px';
            toolbar.style.top = (rect.top - 40) + 'px';
            toolbar.style.display = 'flex';
        },

        hideFmtToolbar() {
            document.getElementById('fmt-toolbar').style.display = 'none';
        },

        // ── Slideshow logic ──
        initSlideshow() {
            this.$nextTick(() => {
                const el = document.getElementById('slideshow-sortable');
                if (el && typeof Sortable !== 'undefined') {
                    new Sortable(el, {
                        animation: 150,
                        handle: '.slide-drag-handle',
                        onEnd: (evt) => {
                            const item = this.slides.splice(evt.oldIndex, 1)[0];
                            this.slides.splice(evt.newIndex, 0, item);
                        }
                    });
                }
            });
        },

        triggerSlideshowUpload() {
            document.getElementById('slideshow-upload-input').click();
        },

        handleSlideshowFile(event) {
            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.slides.push({
                        id: 'new_' + Date.now() + Math.random(),
                        type: 'new',
                        url: e.target.result,
                        croppedBase64: e.target.result,
                        name: file.name
                    });
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        },

        removeSlide(index) {
            this.slides.splice(index, 1);
        },

        openCrop(target, index = null) {
            this.cropTarget = target;
            this.currentCropIndex = index;
            this.cropModalOpen = true;
            this.$nextTick(() => {
                const img = document.getElementById('cropper-image');
                let src = '';
                if (target === 'thumb') {
                    src = this.thumbPreview;
                } else if (target === 'bg') {
                    src = this.bgPreviewUrl;
                } else if (target === 'slide') {
                    src = this.slides[index].croppedBase64 || this.slides[index].url;
                }
                img.src = src;

                if (this.cropper) {
                    this.cropper.destroy();
                }
                this.cropper = new Cropper(img, {
                    viewMode: 2,
                });
            });
        },

        closeCrop() {
            this.cropModalOpen = false;
            this.cropTarget = null;
            this.currentCropIndex = null;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        saveCrop() {
            if (!this.cropper) return;
            const canvas = this.cropper.getCroppedCanvas();
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            if (this.cropTarget === 'thumb') {
                this.thumbPreview = dataUrl;
            } else if (this.cropTarget === 'bg') {
                this.bgPreviewUrl = dataUrl;
                this.bgPreviewType = 'image';
            } else if (this.cropTarget === 'slide') {
                const slide = this.slides[this.currentCropIndex];
                slide.url = dataUrl;
                slide.croppedBase64 = dataUrl;
            }
            
            this.closeCrop();
        },

        submitForm(event) {
            this.isSubmitting = true;

            this.blocks.forEach(block => {
                if (['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(block.type)) {
                    let el = document.querySelector(`.pe-block-content[data-block-id="${block.id}"]`);
                    if (el) block.content = el.innerHTML;
                }
            });

            let cleanBlocks = this.blocks.map(b => {
                let clean = { id: b.id, type: b.type };
                if (b.content !== undefined) clean.content = b.content;
                if (b.src) clean.src = b.src;
                if (b.caption !== undefined && b.caption !== '') clean.caption = b.caption;
                if (b.level) clean.level = b.level;
                if (b.ratio) clean.ratio = b.ratio;
                if (b.posX !== undefined) clean.posX = b.posX;
                if (b.posY !== undefined) clean.posY = b.posY;
                return clean;
            });

            document.getElementById('body_content').value = JSON.stringify(cleanBlocks);

            // Populate slideshow payload
            let slideshowData = this.slides.map(s => {
                if (s.type === 'existing') {
                    if (s.croppedBase64) {
                        return { type: 'new', data: s.croppedBase64 };
                    }
                    return { type: 'existing', path: s.path };
                }
                return { type: 'new', data: s.croppedBase64 };
            });
            const reorderedInput = document.getElementById('reordered_bg_gallery');
            if (reorderedInput) {
                reorderedInput.value = JSON.stringify(slideshowData);
            }

            event.target.submit();
        }
    };
}
</script>

@endsection
