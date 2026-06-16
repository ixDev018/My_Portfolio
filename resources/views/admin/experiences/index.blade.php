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
<!-- CropperJS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div x-data="globalSettingsData()" style="display: flex; flex-direction: column; min-height: calc(100vh - 4rem);">

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Work Experience</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Timeline entries — drag to reorder</p>
    </div>
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <button type="button" @click="settingsModalOpen = true" class="lt-btn-secondary" style="padding:0.55rem; border-radius:0.55rem;" title="Global Background Settings">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
        </button>
        <a href="{{ route('admin.experiences.create') }}" class="lt-btn-primary" style="text-decoration:none;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Experience
        </a>
    </div>
</div>

{{-- Global Background Settings Modal --}}
<div x-show="settingsModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);" x-cloak>
    <form action="{{ route('admin.experiences.settings') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-2xl flex flex-col" @click.stop style="background:#fff; border-radius:1rem; max-height:90vh;" @submit="submitForm">
        @csrf
        <div style="padding:1.5rem; border-bottom:1px solid #E2DDD3; display:flex; justify-content:space-between; align-items:center; background:#F7F5EE; flex-shrink:0;">
            <div>
                <h3 style="font-family:'Outfit',sans-serif; font-size:1.25rem; font-weight:800; color:#1a1207; margin:0 0 0.2rem 0;">Default Unselected Background</h3>
                <p style="font-family:'Inter',sans-serif; font-size:0.75rem; color:#7A7267; margin:0;">Displayed when no specific timeline entry is hovered or selected.</p>
            </div>
            <button type="button" @click="settingsModalOpen = false" style="background:none; border:none; color:#9B9589; cursor:pointer;">
                <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div style="padding:1.5rem; overflow-y:auto; flex:1;">
            <div style="display:flex; gap:1.5rem; margin-bottom:1.25rem; flex-wrap:wrap;">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="radio" name="exp_default_bg_mode" value="cycle" x-model="bgMode" style="accent-color:#6829AA;">
                    <span style="font-size:0.85rem; font-weight:600; color:#1a1207;">Cycle Timeline Media</span>
                </label>
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="radio" name="exp_default_bg_mode" value="custom" x-model="bgMode" style="accent-color:#6829AA;">
                    <span style="font-size:0.85rem; font-weight:600; color:#1a1207;">Custom Global Media</span>
                </label>
            </div>

            <div x-show="bgMode === 'custom'" x-collapse>
                <div style="background:#F7F5EE; border:1px solid #E2DDD3; border-radius:0.75rem; padding:1.25rem; margin-bottom:1.25rem;">
                    <div style="display:flex; gap:1.25rem; margin-bottom:1rem; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer;">
                            <input type="radio" name="exp_default_bg_type" value="image" x-model="bgType" style="accent-color:#6829AA;">
                            <span style="font-size:0.8rem; color:#5A5248;">Single Image</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer;">
                            <input type="radio" name="exp_default_bg_type" value="video" x-model="bgType" style="accent-color:#6829AA;">
                            <span style="font-size:0.8rem; color:#5A5248;">Video (MP4/WebM)</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer;">
                            <input type="radio" name="exp_default_bg_type" value="slideshow" x-model="bgType" style="accent-color:#6829AA;">
                            <span style="font-size:0.8rem; color:#5A5248;">Image Slideshow</span>
                        </label>
                    </div>

                    {{-- Image / Video Upload with Preview --}}
                    <div x-show="bgType !== 'slideshow'"
                         style="background:#fff; border:1px solid #E2DDD3; border-radius:0.65rem; padding:0.75rem; position:relative;"
                         :style="isDraggingSingle ? 'border: 2px dashed #6829AA; background: #F3ECFF;' : ''"
                         @dragover.prevent="isDraggingSingle = true" @dragleave.prevent="isDraggingSingle = false"
                         @drop.prevent="isDraggingSingle = false; if($event.dataTransfer.files.length) { document.getElementById('bg_media_file_upload').files = $event.dataTransfer.files; processSingleMedia($event.dataTransfer.files[0]); }">
                         
                        <input type="file" name="bg_media_file" id="bg_media_file_upload" class="hidden" style="display:none;" :accept="bgType === 'video' ? 'video/mp4,video/webm' : 'image/*'" @change="if($event.target.files.length) processSingleMedia($event.target.files[0])">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:0.6rem;">
                            <span style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.08em; color:#9B9589; display:block;" x-text="bgType === 'video' ? 'Video Preview' : 'Image Preview'"></span>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <button type="button" @click="openSingleCrop()" style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:#6829AA; background:transparent; border:none; cursor:pointer; font-weight:700;" x-show="bgType === 'image' && (singleMediaPreviewUrl || hasInitialMedia)">
                                    <svg style="width:11px; height:11px; display:inline; margin-bottom:1px;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg> CROP IMAGE
                                </button>
                                <button type="button" @click="document.getElementById('bg_media_file_upload').click()" style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:#6829AA; background:transparent; border:none; cursor:pointer; font-weight:700;" x-show="singleMediaPreviewUrl || hasInitialMedia">
                                    <svg style="width:11px; height:11px; display:inline; margin-bottom:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> CHANGE MEDIA
                                </button>
                            </div>
                        </div>

                        <!-- Dropzone Empty State -->
                        <div x-show="!singleMediaPreviewUrl && !hasInitialMedia" 
                             style="border: 2px dashed #D8D4C8; border-radius:0.5rem; background:#F7F5EE; padding:2rem 1.5rem; text-align:center; cursor:pointer; min-height:140px; transition:all 0.18s;"
                             @click="document.getElementById('bg_media_file_upload').click()">
                            <div style="pointer-events:none;">
                                <div style="width:2.5rem; height:2.5rem; border-radius:50%; background:#EEE6FF; display:flex; align-items:center; justify-content:center; margin:0 auto 0.6rem; color:#6829AA;"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg></div>
                                <p style="font-family:'Outfit',sans-serif; font-size:0.85rem; font-weight:700; color:#1a1207; margin-bottom:0.15rem;">Media Drop Zone</p>
                                <p style="font-size:0.72rem; color:#9B9589;">Drop <span x-text="bgType === 'video' ? 'video' : 'image'"></span> file here</p>
                            </div>
                        </div>

                        <!-- Preview Image/Video -->
                        <div x-show="singleMediaPreviewUrl || hasInitialMedia" style="width:100%; border-radius:0.5rem; overflow:hidden; background:#000; display:flex; align-items:center; justify-content:center; position:relative;">
                            <!-- Video -->
                            <video x-show="bgType === 'video'" :src="singleMediaPreviewUrl ? singleMediaPreviewUrl : initialMediaUrl" style="width:100%; max-height:260px; object-fit:contain; display:block;" muted playsinline controls preload="metadata"></video>
                            
                            <!-- Image -->
                            <img x-show="bgType === 'image'" :src="singleMediaPreviewUrl ? singleMediaPreviewUrl : initialMediaUrl" style="width:100%; max-height:260px; object-fit:cover; display:block;">
                        </div>
                    </div>

            {{-- Slideshow logic (Copied from edit.blade.php) --}}
            <div x-show="bgType === 'slideshow'">
                <style>
                    .slide-item .slide-actions { opacity: 0; transition: opacity 0.2s; pointer-events: none; }
                    .slide-item:hover .slide-actions { opacity: 1; pointer-events: auto; }
                </style>
                <input type="hidden" name="reordered_bg_gallery" id="reordered_bg_gallery">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                    <label class="lt-label" style="margin:0;">Slideshow Images</label>
                    <button type="button" @click="triggerSlideshowUpload" class="lt-btn-secondary" style="padding:0.3rem 0.6rem; font-size:0.65rem;" x-show="slides.length > 0">
                        + Add Image
                    </button>
                </div>

                <div id="slideshow-sortable-global" style="display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1rem;" x-show="slides.length > 0">
                    <template x-for="(slide, index) in slides" :key="slide.id">
                        <div class="slide-item" style="display:flex; align-items:center; gap:0.75rem; background:#fff; padding:0.4rem; border-radius:0.4rem; border:1px solid #D8D4C8; box-shadow:0 1px 2px rgba(0,0,0,0.02);">
                            <div class="slide-drag-handle-global" style="cursor:grab; padding:0.2rem; color:#C4BDB2;" title="Drag to reorder">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                            </div>
                            <div style="font-family:'Space Mono', monospace; font-size:0.65rem; font-weight:bold; color:#9B9589; width:1.2rem; text-align:center;">
                                <span x-text="'#' + (index + 1)"></span>
                            </div>
                            <div style="width:4.8rem; height:2.7rem; border-radius:0.25rem; overflow:hidden; background:#eee; flex-shrink:0; border:1px solid rgba(0,0,0,0.1);">
                                <img :src="slide.url" style="width:100%; height:100%; object-fit:cover; display:block;">
                            </div>
                            <div style="flex:1; min-width:0;">
                                <p style="font-size:0.75rem; font-weight:600; color:#333; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="slide.name"></p>
                                <p style="font-size:0.6rem; font-family:'Space Mono', monospace; color:#6829AA; margin:0; font-weight:600;" x-text="slide.type === 'new' ? 'New Upload' : 'Saved Image'"></p>
                            </div>
                            <div class="slide-actions" style="display:flex; gap:0.25rem;">
                                <button type="button" @click="openCrop(index)" title="Crop Image" style="background:#F3ECFF; color:#6829AA; border:none; padding:0.35rem; border-radius:0.35rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s;" onmouseover="this.style.background='#EADDFC'" onmouseout="this.style.background='#F3ECFF'">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg>
                                </button>
                                <button type="button" @click="removeSlide(index)" title="Delete Image" style="background:#FEE2E2; color:#DC2626; border:none; padding:0.35rem; border-radius:0.35rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s;" onmouseover="this.style.background='#FECACA'" onmouseout="this.style.background='#FEE2E2'">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="slides.length === 0" style="padding:1.5rem; border:2px dashed #D8D4C8; border-radius:0.75rem; text-align:center; cursor:pointer; background:#fff; transition:border-color 0.2s;" @click="triggerSlideshowUpload" onmouseover="this.style.borderColor='#6829AA'" onmouseout="this.style.borderColor='#D8D4C8'">
                    <svg style="width:24px; height:24px; margin:0 auto 0.5rem; color:#9B9589;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span style="font-size:0.8rem; color:#5A5248; font-weight:600;">Click to add images to the slideshow</span>
                </div>
                <input type="file" id="slideshow-upload-input-global" accept="image/*" multiple @change="handleSlideshowFile" style="display:none;">
            </div>
        </div>
    </div>
    </div> <!-- end scrollable area -->

    <div style="padding:1rem 1.5rem; border-top:1px solid #E2DDD3; background:#F7F5EE; display:flex; justify-content:flex-end; gap:0.75rem; flex-shrink:0;">
        <button type="button" @click="settingsModalOpen = false" class="lt-btn-secondary">Cancel</button>
        <button type="submit" class="lt-btn-primary" :disabled="isSubmitting">
            <span x-text="isSubmitting ? 'Saving...' : 'Save Settings'"></span>
        </button>
    </div>

    {{-- Cropper Modal (z-index higher than settings modal) --}}
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
                    <img id="cropper-image-global" src="" style="max-width:100%; max-height:100%; display:block; margin:0 auto;">
                </div>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid #E2DDD3; background:#F7F5EE; display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" @click="closeCrop()" class="lt-btn-secondary">Cancel</button>
                <button type="button" @click="saveCrop()" class="lt-btn-primary">Apply Crop</button>
            </div>
        </div>
    </div>
    </form>
</div>

<script>
function globalSettingsData() {
    return {
        settingsModalOpen: false,
        bgMode: '{{ $profile->exp_default_bg_mode ?? 'cycle' }}',
        bgType: '{{ $profile->exp_default_bg_type ?? 'image' }}',
        isSubmitting: false,
        
        // Single Media state
        isDraggingSingle: false,
        singleMediaPreviewUrl: null,
        singleCroppedBase64: '',
        hasInitialMedia: {{ ($profile && $profile->exp_default_bg_media_path) ? 'true' : 'false' }},
        initialMediaUrl: '{{ ($profile && $profile->exp_default_bg_media_path) ? (Str::startsWith($profile->exp_default_bg_media_path, 'http') ? $profile->exp_default_bg_media_path : (Str::startsWith($profile->exp_default_bg_media_path, 'images/embedded/') ? asset($profile->exp_default_bg_media_path) : asset('storage/' . $profile->exp_default_bg_media_path))) : '' }}',

        // Slideshow state
        slides: [],
        cropModalOpen: false,
        currentCropIndex: null,
        cropper: null,

        init() {
            this.$watch('bgType', (value) => {
                // Keep file input in sync if changed type, but basically rely on new uploads
                this.singleMediaPreviewUrl = null; 
            });
            const existing = @json($profile->exp_default_bg_gallery_images ?? []);
            this.slides = existing.map((path, idx) => ({
                id: 'existing_' + idx,
                type: 'existing',
                path: path,
                url: '{{ asset('storage') }}/' + path,
                name: path.split('/').pop()
            }));

            this.$nextTick(() => {
                const el = document.getElementById('slideshow-sortable-global');
                if (el && typeof Sortable !== 'undefined') {
                    new Sortable(el, {
                        animation: 150,
                        handle: '.slide-drag-handle-global',
                        onEnd: (evt) => {
                            const item = this.slides.splice(evt.oldIndex, 1)[0];
                            this.slides.splice(evt.newIndex, 0, item);
                        }
                    });
                }
            });
        },

        processSingleMedia(file) {
            if (!file) return;
            this.singleMediaPreviewUrl = URL.createObjectURL(file);
            this.singleCroppedBase64 = ''; // Reset crop when new file uploaded
        },

        triggerSlideshowUpload() {
            document.getElementById('slideshow-upload-input-global').click();
        },

        handleSlideshowFile(e) {
            const files = e.target.files;
            if (!files.length) return;
            for(let i=0; i<files.length; i++) {
                const f = files[i];
                const url = URL.createObjectURL(f);
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.slides.push({
                        id: 'new_' + Date.now() + '_' + i,
                        type: 'new',
                        file: f,
                        url: url,
                        croppedBase64: e.target.result,
                        name: f.name
                    });
                };
                reader.readAsDataURL(f);
            }
            e.target.value = '';
        },

        removeSlide(index) {
            this.slides.splice(index, 1);
        },

        openSingleCrop() {
            this.cropModalOpen = true;
            this.currentCropIndex = 'single';
            
            this.$nextTick(() => {
                const img = document.getElementById('cropper-image-global');
                img.src = this.singleMediaPreviewUrl || this.initialMediaUrl;
                
                if (this.cropper) {
                    this.cropper.destroy();
                }
                this.cropper = new Cropper(img, {
                    viewMode: 2,
                });
            });
        },

        openCrop(index) {
            this.currentCropIndex = index;
            this.cropModalOpen = true;
            
            this.$nextTick(() => {
                const img = document.getElementById('cropper-image-global');
                img.src = this.slides[index].url;
                
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
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        saveCrop() {
            if (!this.cropper) return;
            const canvas = this.cropper.getCroppedCanvas();
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            if (this.currentCropIndex === 'single') {
                this.singleMediaPreviewUrl = dataUrl;
                this.singleCroppedBase64 = dataUrl;
                const fileInput = document.getElementById('bg_media_file_upload');
                if(fileInput) fileInput.value = ''; // clear original file
            } else {
                const slide = this.slides[this.currentCropIndex];
                slide.url = dataUrl;
                slide.croppedBase64 = dataUrl;
            }
            
            this.closeCrop();
        },

        submitForm(e) {
            this.isSubmitting = true;
            let slideshowData = this.slides.map(s => {
                if (s.type === 'existing') {
                    if (s.croppedBase64) {
                        return { type: 'new', data: s.croppedBase64 };
                    }
                    return { type: 'existing', path: s.path };
                }
                return { type: 'new', data: s.croppedBase64 };
            });
            document.getElementById('reordered_bg_gallery').value = JSON.stringify(slideshowData);
            
            // Add hidden input for single cropped base64
            if (this.singleCroppedBase64) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'single_cropped_base64';
                input.value = this.singleCroppedBase64;
                e.target.appendChild(input);
            }
        }
    }
}
</script>


{{-- Experience list --}}
<div class="lt-card" style="flex: 1; display: flex; flex-direction: column;">
    <div class="lt-card-header" style="flex-shrink: 0;">
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
        <ul id="experience-list" style="list-style:none;padding:0;margin:0; overflow-y:auto; flex:1;">
            @foreach($experiences as $exp)
                <li data-id="{{ $exp->id }}" x-data="{ menuOpen: false }" @click.outside="menuOpen = false">
                    <div class="exp-row">

                        {{-- Drag handle --}}
                        <div class="lt-drag-handle drag-handle" title="Drag to reorder">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h2v2H9V3zm4 0h2v2h-2V3zM9 7h2v2H9V7zm4 0h2v2h-2V7zM9 11h2v2H9v-2zm4 0h2v2h-2v-2zM9 15h2v2H9v-2zm4 0h2v2h-2v-2zM9 19h2v2H9v-2zm4 0h2v2h-2v-2z"/></svg>
                        </div>

                        {{-- Thumb --}}
                        @if($exp->image_path)
                            <img src="{{ (Str::startsWith($exp->image_path, 'http') ? $exp->image_path : (Str::startsWith($exp->image_path, 'images/embedded/') ? asset($exp->image_path) : asset('storage/' . $exp->image_path))) }}" class="lt-thumb">
                        @else
                            <div class="lt-thumb-ph">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        {{-- Info (view mode) --}}
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.2rem;">
                                <span style="font-weight:700;color:#1a1207;font-size:0.875rem;">
                                    {{ $exp->company }}
                                    @if($exp->is_active)
                                        <span style="display:inline-block; width:6px; height:6px; background:#0A8C5E; border-radius:50%; margin-left:4px;" title="Active"></span>
                                    @endif
                                </span>
                                <span class="badge-duration">{{ $exp->duration }}</span>
                            </div>
                            <p style="font-size:0.78rem;color:#7A7267;">{{ $exp->role }}</p>
                        </div>

                        {{-- Actions --}}
                        <div style="flex-shrink:0; position:relative; z-index:20;">
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
                                    <a href="{{ route('admin.experiences.edit', $exp->id) }}" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; color:#1a1207; font-size:0.8rem; text-decoration:none;" @click="menuOpen = false">
                                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
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

</div> {{-- end x-data wrapper --}}

@endsection
