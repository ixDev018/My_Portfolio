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

    /* Modals */
    .lt-modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5);
        backdrop-filter: blur(3px); z-index: 100;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .lt-modal-content {
        background: #fff; border-radius: 1rem; width: 100%; max-width: 480px;
        padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        position: relative; height: 650px; max-height: 90vh; overflow-y: auto;
        display: flex; flex-direction: column;
    }
    .lt-modal-close {
        position: absolute; top: 1.25rem; right: 1.25rem;
        background: #F7F5EE; border: none; border-radius: 50%;
        width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #5A5248; transition: all 0.15s;
    }
    .lt-modal-close:hover { background: #E2DDD3; color: #1a1207; }
</style>

<div x-data="achievementData()">
    {{-- Page header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Achievements</h1>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Awards &amp; Certificates — displayed in the Achievements section</p>
        </div>
        <button type="button" @click="openAddModal()" class="lt-btn-primary">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Achievement
        </button>
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
                        <th>Media</th>
                        <th>Title</th>
                        <th>Issuer</th>
                        <th>Year</th>
                        <th>Type</th>
                        <th style="width:140px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achievements as $item)
                        <tr x-data="{ menuOpen: false }" @click.outside="menuOpen = false">
                            <td style="width: 60px; padding: 0.5rem 1rem;">
                                @if($item->media_path)
                                    <div class="w-10 h-14 bg-[#111] rounded-md overflow-hidden border border-[#D8D4C8] shadow-sm">
                                        <img src="{{ asset('storage/' . $item->media_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-10 h-14 bg-[#F7F5EE] rounded-md border border-dashed border-[#D8D4C8] flex items-center justify-center text-[#B0A99F]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight:600;color:#1a1207;">{{ $item->title }}</span>
                            </td>
                            <td style="color:#7A7267;">{{ $item->issuer }}</td>
                            <td><span class="badge-year">{{ $item->year }}</span></td>
                            <td>
                                @if($item->type === 'award')
                                    <span class="badge-award">🏆 Award</span>
                                @else
                                    <span class="badge-cert">📜 Certificate</span>
                                @endif
                            </td>
                            <td style="padding-right:0.75rem; text-align:right;">
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
                                        <button @click="openEditModal({ id: {{ $item->id }}, title: '{{ addslashes($item->title) }}', issuer: '{{ addslashes($item->issuer) }}', year: '{{ addslashes($item->year) }}', type: '{{ addslashes($item->type) }}', description: '{{ addslashes(str_replace(["\r", "\n"], ["", "\\n"], $item->description)) }}', media_path: '{{ $item->media_path ? addslashes($item->media_path) : '' }}' }); menuOpen = false">
                                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <div class="cms-dd-divider"></div>
                                        <form action="{{ route('admin.achievements.delete', $item->id) }}" method="POST"
                                              @submit.prevent="if(confirm('Delete this achievement?')) $el.submit()">
                                            @csrf
                                            <button type="submit">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
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
            <h3 style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1207;margin-bottom:1rem;" x-text="formMode === 'add' ? '➕ New Achievement' : '✏️ Edit Achievement'"></h3>
            
            <div class="flex border-b border-[#E2DDD3] mb-4">
                <button type="button" @click="modalTab = 'details'" :class="modalTab === 'details' ? 'border-[#6829AA] text-[#6829AA]' : 'border-transparent text-[#9B9589] hover:text-[#1a1207] hover:border-[#D8D4C8]'" class="pb-2 px-1 border-b-2 font-bold font-sans text-sm mr-4 transition-colors">Details</button>
                <button type="button" @click="modalTab = 'media'" :class="modalTab === 'media' ? 'border-[#6829AA] text-[#6829AA]' : 'border-transparent text-[#9B9589] hover:text-[#1a1207] hover:border-[#D8D4C8]'" class="pb-2 px-1 border-b-2 font-bold font-sans text-sm transition-colors">Media</button>
            </div>

            <form :action="getFormAction()" method="POST" class="flex flex-col flex-1 space-y-4" @submit="onSubmit">
                @csrf
                <input type="hidden" name="image_data" id="image_data" x-model="croppedData">
                
                <div x-show="modalTab === 'details'" class="space-y-4">
                    <div>
                        <label class="lt-label">Title</label>
                        <input type="text" name="title" x-model="formData.title" required class="lt-input" placeholder="e.g. Best UI Design Award">
                        @error('title')<p class="lt-err">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="lt-label">Issuer / Organization</label>
                        <input type="text" name="issuer" x-model="formData.issuer" required class="lt-input" placeholder="e.g. Google, Adobe, etc.">
                    </div>
                    <div>
                        <label class="lt-label">Year</label>
                        <input type="text" name="year" x-model="formData.year" required class="lt-input" placeholder="e.g. 2024">
                    </div>
                    <div>
                        <label class="lt-label">Type</label>
                        <select name="type" x-model="formData.type" class="lt-input">
                            <option value="award">🏆 Award</option>
                            <option value="certificate">📜 Certificate</option>
                        </select>
                    </div>
                    <div>
                        <label class="lt-label">Description (optional)</label>
                        <textarea name="description" x-model="formData.description" rows="3" class="lt-input" placeholder="Brief description…"></textarea>
                    </div>
                </div>

                <div x-show="modalTab === 'media'" class="space-y-4" x-cloak>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="lt-label !mb-0">Achievement Media (9:16 format)</label>
                            <button type="button" x-show="imageSelected" @click="resetImage" x-cloak class="text-[0.65rem] font-bold tracking-wider text-red-500 hover:text-red-700 uppercase mb-0.5">
                                Remove Image
                            </button>
                        </div>
                        
                        <!-- Shows if no new image selected AND no old image exists -->
                        <div x-show="!imageSelected && !formData.old_image" class="relative w-full h-48 border-2 border-dashed border-[#D8D4C8] hover:border-[#6829AA] rounded-xl flex flex-col items-center justify-center cursor-pointer transition-colors bg-[#F7F5EE] overflow-hidden group">
                            <input type="file" @change="fileSelected" accept="image/png, image/jpeg, image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <svg class="w-8 h-8 text-[#9B9589] group-hover:text-[#6829AA] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-[#9B9589] font-semibold group-hover:text-[#6829AA] transition-colors">Click or drag image</span>
                        </div>

                        <!-- Shows if an old image exists and no new image is being cropped -->
                        <div x-show="!imageSelected && formData.old_image" class="relative w-full h-48 border border-[#D8D4C8] rounded-xl flex items-center justify-center bg-[#111111] overflow-hidden group">
                            <input type="file" @change="fileSelected" accept="image/png, image/jpeg, image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Click to replace image">
                            <img :src="formData.old_image" class="h-full w-auto object-contain">
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Replace Image</span>
                            </div>
                        </div>

                        <!-- Shows when actively cropping a new image -->
                        <div x-show="imageSelected" style="display: none;" class="mt-2">
                            <div class="w-full bg-[#F7F5EE] rounded-xl overflow-hidden border border-[#D8D4C8] flex justify-center" style="height: 350px;">
                                <img id="cropper-image" src="" class="max-w-full hidden">
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-[#9B9589]">Upload an image of your award or certificate. It will be cropped to a vertical 9:16 aspect ratio (like a mobile screen).</p>
                </div>

                <div style="margin-top:auto;display:flex;justify-content:flex-end;gap:0.75rem;padding-top:1.5rem;">
                    <button type="button" @click="isModalOpen = false" class="lt-btn-secondary">Cancel</button>
                    <button type="submit" class="lt-btn-primary" x-text="formMode === 'add' ? 'Save Achievement' : 'Update Achievement'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include CropperJS CSS/JS from CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    function achievementData() {
        return {
            isModalOpen: false,
            formMode: 'add',
            modalTab: 'details',
            formData: {
                id: null,
                title: '',
                issuer: '',
                year: '',
                type: 'award',
                description: '',
                old_image: ''
            },
            imageSelected: false,
            cropper: null,
            croppedData: '',
            
            openAddModal() {
                this.formMode = 'add';
                this.modalTab = 'details';
                this.formData = { id: null, title: '', issuer: '', year: '', type: 'award', description: '', old_image: '' };
                this.resetImage();
                this.isModalOpen = true;
            },

            openEditModal(item) {
                this.formMode = 'edit';
                this.modalTab = 'details';
                this.formData = { 
                    id: item.id, 
                    title: item.title, 
                    issuer: item.issuer, 
                    year: item.year,
                    type: item.type,
                    description: item.description || '',
                    old_image: item.media_path ? '/storage/' + item.media_path : ''
                };
                this.resetImage();
                this.isModalOpen = true;
            },

            getFormAction() {
                return this.formMode === 'edit' ? `/admin/achievements/update/${this.formData.id}` : `/admin/achievements/store`;
            },

            fileSelected(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = document.getElementById('cropper-image');
                    img.src = event.target.result;
                    img.classList.remove('hidden');
                    this.imageSelected = true;

                    setTimeout(() => {
                        if (this.cropper) {
                            this.cropper.destroy();
                        }
                        this.cropper = new Cropper(img, {
                            aspectRatio: 9 / 16,
                            viewMode: 1,
                            autoCropArea: 1,
                            background: false,
                        });
                    }, 100);
                };
                reader.readAsDataURL(file);
            },

            resetImage() {
                this.imageSelected = false;
                this.croppedData = '';
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                const img = document.getElementById('cropper-image');
                if(img) {
                    img.src = '';
                    img.classList.add('hidden');
                }
            },

            onSubmit(e) {
                if (this.cropper && this.imageSelected) {
                    // Maximum reasonable size for 9:16
                    const canvas = this.cropper.getCroppedCanvas({ maxHeight: 1920, maxWidth: 1080 });
                    this.croppedData = canvas.toDataURL('image/jpeg', 0.9);
                } else {
                    this.croppedData = ''; 
                }
            }
        }
    }
</script>

@endsection
