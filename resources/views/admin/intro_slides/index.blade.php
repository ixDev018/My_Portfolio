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

    .lt-page-header {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:0.75rem; margin-bottom:0.85rem;
    }

    /* Modal styles */
    .lt-modal-overlay {
        position:fixed; inset:0; z-index:100;
        background:rgba(26,18,7,0.4); backdrop-filter:blur(3px);
        display:flex; align-items:center; justify-content:center;
        padding:1.5rem;
    }
    .lt-modal-content {
        background:#fff; border-radius:1rem; width:100%; max-width:600px;
        padding:1.75rem; box-shadow:0 10px 30px rgba(0,0,0,0.1);
        position:relative; max-height: 90vh; overflow-y: auto;
    }
    .lt-modal-close {
        position:absolute; top:1.25rem; right:1.25rem;
        color:#9B9589; cursor:pointer; background:transparent; border:none;
        transition:color 0.15s;
    }
    .lt-modal-close:hover { color:#1a1207; }
</style>

{{-- ─── SortableJS ─── --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<div x-data="slideManager()">

    {{-- Page header --}}
    <div class="lt-page-header">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Intro Slides</h1>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Introduction chapters — drag to reorder</p>
        </div>
        <button type="button" @click="openAddModal()" class="lt-btn-primary">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Slide
        </button>
    </div>

    @if($errors->any())
        <div style="background:#FFF1F1; border:1px solid #FECACA; border-radius:0.5rem; padding:1rem; margin-bottom:1rem;">
            <p class="lt-err" style="margin:0;">There was an error saving your slide. Please check your inputs.</p>
        </div>
    @endif

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
                    x-data="{ menuOpen: false }" @click.outside="menuOpen = false"
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
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.2rem;">
                                <span class="lt-info-title">{{ $slide->title }}</span>
                                <span class="lt-badge-cyan">{{ $slide->chapter_label }}</span>
                                @if($slide->is_locked)
                                    <span class="lt-badge-locked">Template Slide</span>
                                @endif
                            </div>
                            <p class="lt-info-sub">{{ $slide->subtitle }}</p>
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
                                    <button type="button" @click="openEditModal({{ json_encode($slide) }}); menuOpen = false">
                                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    @if(!$slide->is_locked)
                                        <div class="cms-dd-divider"></div>
                                        <form action="{{ route('admin.intro_slides.delete', $slide->id) }}" method="POST"
                                              @submit.prevent="if(confirm('Delete this slide?')) $el.submit()">
                                            @csrf
                                            <button type="submit">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Add / Edit Modal -->
    <div x-show="isModalOpen" x-cloak class="lt-modal-overlay">
        <div class="lt-modal-content" @click.outside="isModalOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <button type="button" @click="isModalOpen = false" class="lt-modal-close">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1207;margin-bottom:1.25rem;" x-text="mode === 'add' ? 'New Slide' : 'Edit Slide'"></h3>

            <form :action="mode === 'add' ? '{{ route('admin.intro_slides.store') }}' : '{{ url('admin/intro-slides/update') }}/' + form.id" method="POST" enctype="multipart/form-data" @submit="onSubmit">
                @csrf
                <input type="hidden" name="image_data" x-model="croppedData">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                    <div>
                        <label class="lt-label">Chapter Label</label>
                        <input type="text" name="chapter_label" x-model="form.chapter_label" required class="lt-input" placeholder="e.g. Chapter 2">
                    </div>
                    <div>
                        <label class="lt-label">Title</label>
                        <input type="text" name="title" x-model="form.title" required class="lt-input" placeholder="e.g. Visual Arts & Design">
                    </div>
                    <div style="grid-column:1/-1;">
                        <label class="lt-label">Subtitle / Roles</label>
                        <input type="text" name="subtitle" x-model="form.subtitle" class="lt-input" placeholder="e.g. Illustrator • UI Designer">
                    </div>
                    <div style="grid-column:1/-1;">
                        <label class="lt-label" x-text="mode === 'add' ? 'Slide Image (Optional)' : 'Replace Slide Image'"></label>
                        
                        <!-- Shows if no new image selected AND no old image exists AND NOT cropping -->
                        <div x-show="!imagePreviewUrl && !form.old_image && !isCropping" class="relative w-full h-32 border-2 border-dashed border-[#D8D4C8] hover:border-[#6829AA] rounded-xl flex flex-col items-center justify-center cursor-pointer transition-colors bg-[#F7F5EE] overflow-hidden group">
                            <input type="file" name="image" @change="fileSelected" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <svg class="w-8 h-8 text-[#9B9589] group-hover:text-[#6829AA] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-[#9B9589] font-semibold group-hover:text-[#6829AA] transition-colors">Click or drag image</span>
                        </div>

                        <!-- Shows if an old image exists OR a new image is selected AND NOT cropping -->
                        <div x-show="(imagePreviewUrl || form.old_image) && !isCropping" class="relative w-full h-40 border border-[#D8D4C8] rounded-xl flex items-center justify-center bg-[#111111] overflow-hidden group">
                            <!-- Overlay crop action (z-20 to be clickable above file input) -->
                            <div class="absolute top-2 right-2 z-20 flex opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" @click.stop.prevent="startCrop" class="p-2 bg-black/70 hover:bg-[#FF851B] text-white rounded-md transition-colors backdrop-blur-sm border border-white/10" title="Crop Image">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>
                                </button>
                            </div>

                            <input type="file" name="image" @change="fileSelected" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Click to replace image">
                            <template x-if="imagePreviewUrl || form.old_image">
                                <img :src="imagePreviewUrl ? imagePreviewUrl : '{{ asset('storage') }}/' + form.old_image" class="h-full w-auto object-cover opacity-90 group-hover:opacity-60 transition-opacity">
                            </template>
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-xs font-bold text-white uppercase tracking-wider" x-text="imagePreviewUrl ? 'Replace Selected Image' : 'Replace Image'"></span>
                            </div>
                        </div>

                        <!-- Shows when actively cropping -->
                        <div x-show="isCropping" style="display: none;" class="space-y-3 mt-2">
                            <div class="w-full bg-[#111111] rounded-xl overflow-hidden border border-[#D8D4C8]" style="height: 250px;">
                                <img id="slide-cropper-image" src="" class="max-w-full hidden">
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="cancelCrop" class="lt-btn-secondary w-full justify-center">Cancel</button>
                                <button type="button" @click="applyCrop" class="lt-btn-primary w-full justify-center" style="background:#FF851B;">Apply Crop</button>
                            </div>
                        </div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <label class="lt-label">Description Paragraphs</label>
                        <textarea name="description" x-model="form.description" rows="4" class="lt-input" placeholder="Write paragraphs... Double enter for new paragraph."></textarea>
                    </div>
                </div>
                <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:0.65rem;">
                    <button type="button" @click="isModalOpen = false" class="lt-btn-secondary">Cancel</button>
                    <button type="submit" class="lt-btn-primary" x-text="mode === 'add' ? 'Save Slide' : 'Update Slide'"></button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('slideManager', () => ({
            isModalOpen: false,
            mode: 'add',
            imagePreviewUrl: null,
            isCropping: false,
            cropper: null,
            croppedData: '',
            form: {
                id: null,
                chapter_label: 'Chapter',
                title: '',
                subtitle: '',
                description: '',
                old_image: null
            },
            openAddModal() {
                this.mode = 'add';
                this.imagePreviewUrl = null;
                this.isCropping = false;
                this.croppedData = '';
                if(this.cropper) { this.cropper.destroy(); this.cropper = null; }
                this.form = {
                    id: null,
                    chapter_label: 'Chapter ' + ({{ $slides->count() }} + 1),
                    title: '',
                    subtitle: '',
                    description: '',
                    old_image: null
                };
                this.isModalOpen = true;
            },
            openEditModal(slide) {
                this.mode = 'edit';
                this.imagePreviewUrl = null;
                this.isCropping = false;
                this.croppedData = '';
                if(this.cropper) { this.cropper.destroy(); this.cropper = null; }
                this.form = {
                    id: slide.id,
                    chapter_label: slide.chapter_label || '',
                    title: slide.title || '',
                    subtitle: slide.subtitle || '',
                    description: slide.description || '',
                    old_image: slide.image_path || null
                };
                this.isModalOpen = true;
            },
            fileSelected(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreviewUrl = URL.createObjectURL(file);
                    this.croppedData = ''; // Reset crop data if new image picked
                }
            },
            startCrop() {
                this.isCropping = true;
                const img = document.getElementById('slide-cropper-image');
                img.src = this.imagePreviewUrl ? this.imagePreviewUrl : ('/storage/' + this.form.old_image);
                img.classList.remove('hidden');
                
                setTimeout(() => {
                    if (this.cropper) {
                        this.cropper.destroy();
                    }
                    this.cropper = new Cropper(img, {
                        aspectRatio: NaN,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                    });
                }, 100);
            },
            cancelCrop() {
                this.isCropping = false;
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            },
            applyCrop() {
                if (this.cropper) {
                    const canvas = this.cropper.getCroppedCanvas({ maxWidth: 1920, maxHeight: 1080 });
                    this.croppedData = canvas.toDataURL('image/jpeg', 0.9);
                    this.imagePreviewUrl = this.croppedData;
                    this.cancelCrop();
                }
            },
            onSubmit() {
                // If they applied a crop, it's stored in croppedData and will submit.
                // If they picked a new file but didn't crop, it uploads the file via <input type="file">
            }
        }));
    });

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
