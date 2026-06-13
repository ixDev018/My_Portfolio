@extends('admin.layout')

@section('admin_content')

<!-- NoUiSlider for Video Trimming -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>

<style>
    .cms-main { background: #EDEAE0; }

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
    .pe-field { margin-bottom:0.15rem; }
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
    .pe-last-saved {
        display:flex; flex-direction:row; flex-wrap:nowrap;
        align-items:center; gap:0.35rem;
        white-space:nowrap; flex-shrink:0;
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

    /* Responsive */
    @media (max-width:900px) {
        .pe-shell { flex-direction:column; height:auto; overflow:visible; }
        .pe-sidebar { width:100%; }
        .pe-editor-col { min-height:60vh; }
    }

    /* dragging ghost */
    .pe-block.dragging { opacity:0.4; }
    .pe-block.drag-over { border-top:2px solid #6829AA; }

    [x-cloak] { display:none !important; }

    /* ─── Link Popover ─── */
    .pe-link-popover {
        position: fixed;
        z-index: 110;
        background: #1a1207;
        border-radius: 0.55rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.28);
        padding: 0.45rem 0.55rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        min-width: 280px;
    }
    .pe-link-popover input {
        flex: 1;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 0.35rem;
        padding: 0.3rem 0.55rem;
        font-size: 0.75rem;
        color: #fff;
        outline: none;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.15s;
    }
    .pe-link-popover input::placeholder { color: rgba(255,255,255,0.35); }
    .pe-link-popover input:focus { border-color: rgba(255,255,255,0.4); }
    .pe-link-popover-btn {
        display: flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 0.3rem;
        background: transparent; border: none; cursor: pointer;
        color: rgba(255,255,255,0.55); transition: all 0.12s;
        flex-shrink: 0;
    }
    .pe-link-popover-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .pe-link-popover-btn.apply { background: #6829AA; color: #fff; width: auto; padding: 0 0.6rem; font-size: 0.7rem; font-weight: 700; font-family: 'Space Mono', monospace; }
    .pe-link-popover-btn.apply:hover { background: #5720A0; }
    .pe-link-popover-btn.unlink { color: #f87171; }
    .pe-link-popover-btn.unlink:hover { background: rgba(248,113,113,0.15); color: #f87171; }
    .pe-link-popover-divider { width: 1px; height: 18px; background: rgba(255,255,255,0.12); flex-shrink: 0; }
</style>

{{-- Back link --}}
<a href="{{ route('admin.projects.index') }}" class="pe-back">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Outputs
</a>

<form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data"
      id="project-form"
      x-data="notionEditor()"
      @submit.prevent="submitForm($event)">
    @csrf

    <div class="pe-shell">

        {{-- ═══ LEFT: Editor Column ═══ --}}
        <div class="pe-editor-col">

            {{-- Title & Subtitle --}}
            <div class="pe-editor-header">
                <input type="text" name="title" class="pe-title-input"
                       placeholder="Project Title"
                       value="{{ old('title', $project->title) }}" required>
                @error('title') <p class="pe-field-err">{{ $message }}</p> @enderror

                <input type="text" name="subtitle" class="pe-subtitle-input"
                       placeholder="Add a subtitle…"
                       value="{{ old('subtitle', $project->subtitle) }}">
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
                                         @click="!block.isUploading && triggerImageUpload(block.id)"
                                         :style="block.isUploading ? 'opacity:0.7; pointer-events:none;' : ''">
                                        <svg x-show="!block.isUploading" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <svg x-show="block.isUploading" class="animate-spin" style="width:1.5rem; height:1.5rem; color:#6829AA;" fill="none" viewBox="0 0 24 24" x-cloak><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="block.isUploading ? 'Uploading Image...' : 'Click to upload image'"></span>
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
                                             @click="!block.isUploading && triggerVideoUpload(block.id)"
                                             :style="block.isUploading ? 'opacity:0.7; pointer-events:none;' : ''">
                                            <svg x-show="!block.isUploading" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <svg x-show="block.isUploading" class="animate-spin" style="width:1.5rem; height:1.5rem; color:#6829AA;" fill="none" viewBox="0 0 24 24" x-cloak><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span x-text="block.isUploading ? 'Uploading Video... Please wait.' : 'Upload your own video file'"></span>
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

                        {{-- Text-based blocks (paragraph, heading, quote, code) --}}
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

                {{-- Click area below blocks to add new --}}
                <div style="min-height:120px; cursor:text; padding:0 1rem;"
                     @click="addBlockAtEnd()"></div>
            </div>

            {{-- Editor footer --}}
            <div class="pe-footer">
                <a href="{{ route('admin.projects.index') }}" class="pe-btn-cancel">Cancel</a>
                <div style="display:flex; gap:0.75rem; align-items:center;">
                    {{-- Last saved indicator --}}
                    <div class="pe-last-saved" x-show="lastSaved" x-cloak>
                        <span style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#9B9589; text-transform:uppercase; letter-spacing:0.1em;">Saved</span>
                        <svg style="width:10px;height:10px;color:#0A8C5E;flex-shrink:0;" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        <span style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#9B9589; text-transform:uppercase; letter-spacing:0.1em;" x-text="formatRelativeTime(lastSaved)"></span>
                    </div>
                    <span style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#D8D4C8; text-transform:uppercase; letter-spacing:0.1em;" x-show="lastSaved" x-cloak>·</span>
                    <span style="font-family:'Space Mono',monospace; font-size:0.55rem; color:#9B9589; text-transform:uppercase; letter-spacing:0.1em;"
                          x-text="blocks.length + ' blocks'"></span>
                    <button type="submit" class="pe-btn-save" :disabled="isSubmitting">
                        <svg x-show="isSubmitting" class="animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                            <circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Saving…' : 'Save Changes'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT: Sidebar ═══ --}}
        <div class="pe-sidebar" x-data="{ tab: 'media', featured: {{ old('featured', $project->featured ?? false) ? 'true' : 'false' }}, is_best_work: {{ old('is_best_work', $project->is_best_work ?? false) ? 'true' : 'false' }}, is_top: {{ old('is_top', $project->is_top ?? false) ? 'true' : 'false' }}, is_archived: {{ old('is_archived', $project->is_archived ?? false) ? 'true' : 'false' }} }">
            <div class="pe-sidebar-header">
                <span style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; color:#9B9589;">Project Details</span>
                <span style="font-family:'Space Mono',monospace; font-size:0.58rem; color:#6829AA; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;">Editing</span>
            </div>

            <div class="pe-sidebar-scroll">

                {{-- Toggles: Featured & Best Work --}}
                <div class="flex gap-2" style="margin-bottom: 0.65rem;">
                    <!-- Featured Toggle -->
                    <div class="pe-featured-row">
                        <input type="hidden" name="featured" :value="featured ? '1' : '0'">
                        <button type="button" @click="featured = !featured" 
                                class="flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200"
                                :class="featured ? 'bg-[#FFF9E6] border-[#FF851B] text-[#783800]' : 'bg-[#E2DDD3] border-transparent text-[#9B9589] hover:bg-[#D5D0C6]'"
                                title="Toggle Featured">
                            <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 24 24" x-show="featured" x-cloak><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-show="!featured"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </button>
                    </div>
                    
                    <!-- Best Work Toggle -->
                    <div class="pe-featured-row">
                        <input type="hidden" name="is_best_work" :value="is_best_work ? '1' : '0'">
                        <button type="button" @click="is_best_work = !is_best_work" 
                                class="flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200"
                                :class="is_best_work ? 'bg-[#F3ECFF] border-[#6829AA] text-[#6829AA]' : 'bg-[#E2DDD3] border-transparent text-[#9B9589] hover:bg-[#D5D0C6]'"
                                title="Toggle Best Work">
                            <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 24 24" x-show="is_best_work" x-cloak><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-show="!is_best_work"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </button>
                    </div>
                    
                    <!-- Top Toggle -->
                    <div class="pe-featured-row">
                        <input type="hidden" name="is_top" :value="is_top ? '1' : '0'">
                        <button type="button" @click="is_top = !is_top" 
                                class="flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200"
                                :class="is_top ? 'bg-[#E6FAF5] border-[#0A8C5E] text-[#0A8C5E]' : 'bg-[#E2DDD3] border-transparent text-[#9B9589] hover:bg-[#D5D0C6]'"
                                title="Toggle Top">
                            <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 24 24" x-show="is_top" x-cloak><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-show="!is_top"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </button>
                    </div>

                    <!-- Archived Toggle -->
                    <div class="pe-featured-row">
                        <input type="hidden" name="is_archived" :value="is_archived ? '1' : '0'">
                        <button type="button" @click="is_archived = !is_archived" 
                                class="flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200"
                                :class="is_archived ? 'bg-[#FFF1F1] border-[#FECACA] text-[#dc2626]' : 'bg-[#E2DDD3] border-transparent text-[#9B9589] hover:bg-[#D5D0C6]'"
                                title="Toggle Archive">
                            <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 24 24" x-show="is_archived" x-cloak><path d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12.14l.84 1H5.12z"/></svg>
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-show="!is_archived"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Tabs Header --}}
                <div style="display:flex; justify-content:space-between; align-items:flex-end; border-bottom: 1px solid #D8D4C8; margin-bottom: 0.85rem; position:relative;">
                    <div style="display:flex; gap:1.25rem;">
                        <button type="button" @click="tab = 'media'" 
                                style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:0.5rem; background:transparent; border:none; cursor:pointer; position:relative; transition:color 0.2s;"
                                :style="{ color: tab === 'media' ? '#1a1207' : '#B0A99F', fontWeight: tab === 'media' ? '700' : '600' }">
                            Media
                            <div x-show="tab === 'media'" style="position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#1a1207; border-radius:1px;"></div>
                        </button>
                        <button type="button" @click="tab = 'meta'" 
                                style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:0.5rem; background:transparent; border:none; cursor:pointer; position:relative; transition:color 0.2s;"
                                :style="{ color: tab === 'meta' ? '#1a1207' : '#B0A99F', fontWeight: tab === 'meta' ? '700' : '600' }">
                            Details
                            <div x-show="tab === 'meta'" style="position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#1a1207; border-radius:1px;"></div>
                        </button>
                        <button type="button" @click="tab = 'links'" 
                                style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:0.5rem; background:transparent; border:none; cursor:pointer; position:relative; transition:color 0.2s;"
                                :style="{ color: tab === 'links' ? '#1a1207' : '#B0A99F', fontWeight: tab === 'links' ? '700' : '600' }">
                            Links
                            <div x-show="tab === 'links'" style="position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#1a1207; border-radius:1px;"></div>
                        </button>
                    </div>
                </div>
                {{-- Section Title Removed --}}

                {{-- MEDIA TAB --}}
                <div x-show="tab === 'media'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display:none;">

                {{-- Thumbnail Media --}}
                @include('admin.projects.partials.media_upload')

                {{-- Existing Gallery Management --}}
                @if(!empty($project->gallery_images))
                    <div style="border-top:1px solid #E2DDD3; padding-top:0.75rem; margin-top:0.25rem;">
                        <span class="pe-field-label" style="margin-bottom:0.5rem;">Existing Legacy Gallery</span>
                        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.4rem;">
                            @foreach($project->gallery_images as $index => $imagePath)
                                <div style="position:relative; border-radius:0.4rem; overflow:hidden; border:1px solid #E2DDD3; aspect-ratio:16/9;">
                                    <img src="{{ asset('storage/' . $imagePath) }}" style="width:100%; height:100%; object-fit:cover;">
                                    <label style="position:absolute; inset:0; background:rgba(0,0,0,0.4); opacity:0; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:opacity 0.15s;"
                                           onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                        <input type="checkbox" name="delete_gallery[]" value="{{ $index }}" style="display:none;">
                                        <span style="font-size:0.6rem; color:#fca5a5; font-weight:700; font-family:'Space Mono',monospace;">DELETE</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                </div>

                {{-- META DATA TAB --}}
                <div x-show="tab === 'meta'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display:none;">
                    
                    {{-- Category & Medium --}}
                <div class="pe-field-row">
                    <div class="pe-field">
                        <label class="pe-field-label">Category</label>
                        <select name="category" class="pe-field-select">
                            <option value="ui" {{ old('category', $project->category) == 'ui' ? 'selected' : '' }}>UI/UX Design</option>
                            <option value="visual" {{ old('category', $project->category) == 'visual' ? 'selected' : '' }}>Creative Visual</option>
                            <option value="other" {{ old('category', $project->category) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="pe-field">
                        <label class="pe-field-label">Medium</label>
                        <input type="text" name="medium" class="pe-field-input"
                               value="{{ old('medium', $project->medium) }}"
                               placeholder="e.g. SaaS">
                    </div>
                </div>

                {{-- Client & Role --}}
                <div class="pe-field-row">
                    <div class="pe-field">
                        <label class="pe-field-label">Source / Studio</label>
                        <input type="text" name="client" class="pe-field-input"
                               value="{{ old('client', $project->client) }}"
                               placeholder="e.g. Freelance">
                    </div>
                    <div class="pe-field">
                        <label class="pe-field-label">Your Role</label>
                        <input type="text" name="role" class="pe-field-input"
                               value="{{ old('role', $project->role) }}"
                               placeholder="e.g. Lead Designer">
                    </div>
                </div>

                {{-- Year & Collaborators --}}
                <div class="pe-field-row">
                    <div class="pe-field">
                        <label class="pe-field-label">Date Published</label>
                        <input type="text" name="date_published" class="pe-field-input"
                               value="{{ old('date_published', $project->date_published) }}"
                               placeholder="e.g. 2025">
                    </div>
                    <div class="pe-field">
                        <label class="pe-field-label">Collaborators</label>
                        <input type="text" name="collaborators" class="pe-field-input"
                               value="{{ old('collaborators', $project->collaborators) }}"
                               placeholder="Names…">
                    </div>
                </div>

                {{-- Tags --}}
                <div class="pe-field-row" style="margin-top:0.75rem;">
                    <div class="pe-field">
                        <label class="pe-field-label">Tags (Comma Separated)</label>
                        <input type="text" name="tags" class="pe-field-input"
                               value="{{ old('tags', $project->tags) }}"
                               placeholder="e.g. Laravel, Tailwind CSS">
                    </div>
                </div>

                </div>

                {{-- LINKS TAB --}}
                <div x-show="tab === 'links'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display:none;">
                    
                    {{-- Links --}}
                    <div style="display:flex; flex-direction:column; gap:0.75rem;">
                        <div class="pe-field">
                            <label class="pe-field-label">Live Demo URL</label>
                            <input type="url" name="demo_url" class="pe-field-input"
                                   value="{{ old('demo_url', $project->demo_url) }}"
                                   placeholder="https://…">
                        </div>
                        <div class="pe-field">
                            <label class="pe-field-label">GitHub URL</label>
                            <input type="url" name="github_url" class="pe-field-input"
                                   value="{{ old('github_url', $project->github_url) }}"
                                   placeholder="https://github.com/…">
                        </div>
                        
                        <div class="pe-field">
                            <label class="pe-field-label">External Full Video Link (Fallback & 15s Preview End)</label>
                            <input type="url" name="full_video_url" class="pe-field-input"
                                   value="{{ old('full_video_url', $project->full_video_url ?? '') }}"
                                   placeholder="https://youtube.com/... or https://vimeo.com/...">
                            <p style="font-size:0.52rem; color:#9B9589; margin-top:0.15rem; font-family:'Space Mono',monospace;">Visitors will be linked here for the 'See full video' fallback, or after the 15-second preview ends on thumbnails.</p>
                        </div>
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
    <button type="button" class="pe-fmt-btn" id="fmt-link-btn" data-fmt="link" title="Add / Edit Link (Ctrl+K)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
    </button>
</div>

{{-- Link Popover --}}
<div class="pe-link-popover" id="link-popover" style="display:none;">
    <input type="text" id="link-popover-input" placeholder="Paste or type a URL…" autocomplete="off" spellcheck="false">
    <button type="button" class="pe-link-popover-btn apply" id="link-popover-apply" title="Apply link">Apply</button>
    <div class="pe-link-popover-divider" id="link-popover-unlink-divider"></div>
    <button type="button" class="pe-link-popover-btn unlink" id="link-popover-unlink" title="Remove link" style="display:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </button>
    <button type="button" class="pe-link-popover-btn" id="link-popover-open" title="Open link in new tab" style="display:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
    </button>
    <button type="button" class="pe-link-popover-btn" id="link-popover-close" title="Close">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- Hidden file input for image blocks --}}
<input type="file" id="block-image-upload" accept="image/*" style="display:none;">
{{-- Hidden file input for video blocks --}}
<input type="file" id="block-video-upload" accept="video/mp4,video/webm,video/mov,video/quicktime" style="display:none;">

<script>
function notionEditor() {
    return {
        blocks: [],
        activeBlockId: null,
        isSubmitting: false,
        lastSaved: '{{ $project->updated_at ? $project->updated_at->toIso8601String() : '' }}',
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

        formatRelativeTime(iso) {
            if (!iso) return '';
            const d = new Date(iso);
            const diff = Math.floor((Date.now() - d) / 1000);
            if (diff < 10)    return 'just now';
            if (diff < 60)   return Math.floor(diff) + 's ago';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },

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
            this.loadContent();
            this.setupSlashMenu();
            this.setupFmtToolbar();
            document.getElementById('block-image-upload').addEventListener('change', (e) => this.handleImageFile(e));
            document.getElementById('block-video-upload').addEventListener('change', (e) => this.handleVideoFile(e));
        },

        generateId() {
            return 'b_' + Math.random().toString(36).substr(2, 9);
        },

        loadContent() {
            let raw = @json(old('body_content', $project->body_content));
            if (!raw || (typeof raw === 'string' && raw.trim() === '')) {
                this.blocks = [{ id: this.generateId(), type: 'paragraph', content: '' }];
                return;
            }

            // Try JSON blocks
            if (typeof raw === 'string') {
                try {
                    let parsed = JSON.parse(raw);
                    if (Array.isArray(parsed)) {
                        this.blocks = parsed.map(b => ({ ...b, id: b.id || this.generateId() }));
                        return;
                    }
                } catch(e) {}

                // Legacy HTML — wrap in a single HTML paragraph block
                if (raw.trim().startsWith('<')) {
                    this.blocks = [{ id: this.generateId(), type: 'paragraph', content: raw }];
                } else {
                    this.blocks = [{ id: this.generateId(), type: 'paragraph', content: raw }];
                }
            } else {
                this.blocks = [{ id: this.generateId(), type: 'paragraph', content: '' }];
            }
        },

        getPlaceholder(type, index) {
            if (index === 0 && this.blocks.length === 1 && type === 'paragraph') {
                return "Type '/' for commands, or start writing...";
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

        // ── Slash command menu ──
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

        // ── Block menu (on handle click) ──
        openBlockMenu(blockId, event) {
            let idx = this.blocks.findIndex(b => b.id === blockId);
            if (idx === -1) return;
            
            let actions = [
                'Delete block',
                'Duplicate block',
                '─',
                'Turn into Text',
                'Turn into Heading 2',
                'Turn into Heading 3',
                'Turn into Quote',
                'Turn into Bullet',
            ];
            // Simple prompt-based for now
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

        // ── Drag & Drop ──
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

        // ── Image upload ──
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

            block.isUploading = true;

            try {
                let resp = await fetch('{{ route("admin.projects.upload_body_media") }}', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Image upload failed.');
            } finally {
                block.isUploading = false;
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

            if (file.size > 250 * 1024 * 1024) {
                alert('This video is too large! The maximum allowed file size is 250MB.');
                event.target.value = '';
                this._pendingVideoBlockId = null;
                return;
            }

            let block = this.blocks.find(b => b.id === this._pendingVideoBlockId);
            if (!block) return;

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            block.isUploading = true;

            try {
                let resp = await fetch('{{ route("admin.projects.upload_body_media") }}', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Video upload failed.');
            } finally {
                block.isUploading = false;
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
            // YouTube
            let ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
            if (ytMatch) return 'https://www.youtube.com/embed/' + ytMatch[1];
            // Vimeo
            let vmMatch = url.match(/vimeo\.com\/(\d+)/);
            if (vmMatch) return 'https://player.vimeo.com/video/' + vmMatch[1];
            return url;
        },

        // ── Formatting toolbar ──
        _linkSavedRange: null,
        _linkAnchorEl: null,

        setupFmtToolbar() {
            const toolbar   = document.getElementById('fmt-toolbar');
            const popover   = document.getElementById('link-popover');
            const popInput  = document.getElementById('link-popover-input');
            const popApply  = document.getElementById('link-popover-apply');
            const popUnlink = document.getElementById('link-popover-unlink');
            const popOpen   = document.getElementById('link-popover-open');
            const popClose  = document.getElementById('link-popover-close');
            const popUnlinkDivider = document.getElementById('link-popover-unlink-divider');
            const linkBtn   = document.getElementById('fmt-link-btn');

            // Position helper
            const positionPopover = (referenceRect) => {
                popover.style.display = 'flex';
                let left = referenceRect.left + referenceRect.width / 2 - popover.offsetWidth / 2;
                let top  = referenceRect.bottom + 8;
                // Clamp to viewport
                left = Math.max(8, Math.min(left, window.innerWidth - popover.offsetWidth - 8));
                popover.style.left = left + 'px';
                popover.style.top  = top + 'px';
            };

            const closeLinkPopover = () => {
                popover.style.display = 'none';
                popInput.value = '';
                this._linkSavedRange = null;
                this._linkAnchorEl   = null;
            };

            const applyLink = () => {
                let url = popInput.value.trim();
                if (!url) return;
                if (!/^https?:\/\//i.test(url) && !url.startsWith('mailto:') && !url.startsWith('#')) {
                    url = 'https://' + url;
                }
                // Restore saved selection
                if (this._linkSavedRange) {
                    let sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(this._linkSavedRange);
                }
                if (this._linkAnchorEl) {
                    // Editing existing anchor
                    this._linkAnchorEl.href = url;
                    this._linkAnchorEl.setAttribute('target', '_blank');
                    this._linkAnchorEl.setAttribute('rel', 'noopener noreferrer');
                } else {
                    document.execCommand('createLink', false, url);
                    // Set target=_blank on the newly created link
                    let sel2 = window.getSelection();
                    if (sel2 && sel2.anchorNode) {
                        let a = sel2.anchorNode.parentElement;
                        if (!a || a.tagName !== 'A') a = sel2.anchorNode.closest ? sel2.anchorNode.closest('a') : null;
                        if (a && a.tagName === 'A') {
                            a.setAttribute('target', '_blank');
                            a.setAttribute('rel', 'noopener noreferrer');
                        }
                    }
                }
                closeLinkPopover();
            };

            // ── Selection change — show/hide toolbar ──
            document.addEventListener('selectionchange', () => {
                if (popover.style.display === 'flex') return; // keep popover open
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

                // Highlight link btn if selection is inside an <a>
                let node = sel.anchorNode;
                let anchorEl = node && node.nodeType === 3 ? node.parentElement : node;
                let insideLink = anchorEl && anchorEl.closest('a');
                linkBtn && linkBtn.classList.toggle('active', !!insideLink);
            });

            // ── Ctrl+K shortcut ──
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    let sel = window.getSelection();
                    if (sel && !sel.isCollapsed && sel.rangeCount) {
                        let container = sel.getRangeAt(0).commonAncestorContainer;
                        if (container.nodeType === 3) container = container.parentNode;
                        if (container.closest && container.closest('.pe-block-content')) {
                            e.preventDefault();
                            linkBtn && linkBtn.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
                        }
                    }
                }
            });

            // ── Bold / Italic / Strikethrough buttons ──
            document.querySelectorAll('.pe-fmt-btn:not(#fmt-link-btn)').forEach(btn => {
                btn.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    let fmt = btn.dataset.fmt;
                    if (fmt === 'bold')          document.execCommand('bold');
                    else if (fmt === 'italic')   document.execCommand('italic');
                    else if (fmt === 'strikethrough') document.execCommand('strikeThrough');
                });
            });

            // ── Link button ──
            linkBtn && linkBtn.addEventListener('mousedown', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // If popover already open, close it
                if (popover.style.display === 'flex') { closeLinkPopover(); return; }

                let sel = window.getSelection();
                let editingExisting = false;
                let existingHref   = '';

                // Check if cursor / selection is inside an existing <a>
                if (sel && sel.rangeCount) {
                    let node = sel.anchorNode;
                    let el = node && node.nodeType === 3 ? node.parentElement : node;
                    let anchor = el && el.closest ? el.closest('a') : null;
                    if (anchor) {
                        editingExisting = true;
                        existingHref    = anchor.getAttribute('href') || '';
                        this._linkAnchorEl = anchor;
                        // Save the range anyway for restoring selection on apply
                        this._linkSavedRange = sel.getRangeAt(0).cloneRange();
                    }
                }

                if (!editingExisting) {
                    if (!sel || sel.isCollapsed) return;
                    this._linkSavedRange = sel.getRangeAt(0).cloneRange();
                    this._linkAnchorEl   = null;
                }

                // Populate input
                popInput.value = existingHref;

                // Show/hide unlink & open buttons
                popUnlink.style.display        = editingExisting ? 'flex'  : 'none';
                popOpen.style.display          = editingExisting ? 'flex'  : 'none';
                popUnlinkDivider.style.display = editingExisting ? 'block' : 'none';

                // Position relative to the toolbar
                const toolbarRect = toolbar.getBoundingClientRect();
                popover.style.display = 'flex';
                // Let browser calculate popover size, then position
                requestAnimationFrame(() => {
                    positionPopover(toolbarRect);
                    popInput.focus();
                    popInput.select();
                });
            });

            // ── Apply on Enter key in input ──
            popInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') { e.preventDefault(); applyLink(); }
                if (e.key === 'Escape') { closeLinkPopover(); }
            });

            // ── Apply button ──
            popApply.addEventListener('mousedown', (e) => { e.preventDefault(); applyLink(); });

            // ── Unlink button ──
            popUnlink.addEventListener('mousedown', (e) => {
                e.preventDefault();
                if (this._linkAnchorEl) {
                    // Unwrap anchor node
                    let parent = this._linkAnchorEl.parentNode;
                    while (this._linkAnchorEl.firstChild) {
                        parent.insertBefore(this._linkAnchorEl.firstChild, this._linkAnchorEl);
                    }
                    parent.removeChild(this._linkAnchorEl);
                } else if (this._linkSavedRange) {
                    let sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(this._linkSavedRange);
                    document.execCommand('unlink');
                }
                closeLinkPopover();
            });

            // ── Open link in new tab ──
            popOpen.addEventListener('mousedown', (e) => {
                e.preventDefault();
                let href = this._linkAnchorEl ? this._linkAnchorEl.href : popInput.value;
                if (href) window.open(href, '_blank', 'noopener');
            });

            // ── Close button ──
            popClose.addEventListener('mousedown', (e) => { e.preventDefault(); closeLinkPopover(); });

            // ── Close popover on outside click ──
            document.addEventListener('mousedown', (e) => {
                if (popover.style.display === 'flex'
                    && !popover.contains(e.target)
                    && e.target !== linkBtn
                    && !linkBtn.contains(e.target)) {
                    closeLinkPopover();
                }
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

        // ── Form submission ──
        submitForm(event) {
            this.isSubmitting = true;
            this.lastSaved = new Date().toISOString();

            // Sync block contents from DOM
            this.blocks.forEach(block => {
                if (['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(block.type)) {
                    let el = document.querySelector(`.pe-block-content[data-block-id="${block.id}"]`);
                    if (el) block.content = el.innerHTML;
                }
            });

            // Serialize blocks to JSON
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

            // Submit the form natively
            event.target.submit();
        }
    };
}
</script>

@endsection
