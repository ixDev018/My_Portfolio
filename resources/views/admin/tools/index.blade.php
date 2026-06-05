@extends('admin.layout')

@section('admin_content')

    <!-- Tools/Marquee Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Manage Tools & Software</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Configure items for the Marquee section with optional PNG icons.</p>
    </div>

    <!-- Main Workspace Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start" x-data="toolCropperData()">
        
        <!-- Left Side: Add Tool Form -->
        <div class="lg:col-span-4 bg-slate-900 border border-slate-850 p-6 rounded-2xl shadow-xl">
            <h2 class="text-base font-bold text-white mb-4 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                Add Tool/Software
            </h2>

            <form action="{{ route('admin.tools.store') }}" method="POST" class="space-y-5" @submit="onSubmit">
                @csrf
                <input type="hidden" name="image_data" id="image_data" x-model="croppedData">

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tool Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required 
                           placeholder="e.g. PHP, Figma"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tooltip Info -->
                <div>
                    <label for="tooltip_info" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tooltip Info (Optional)</label>
                    <input type="text" 
                           name="tooltip_info" 
                           id="tooltip_info" 
                           placeholder="e.g. 5+ Years Experience"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('tooltip_info')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category / Row -->
                <div>
                    <label for="row_label" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Marquee Category</label>
                    <input type="text" 
                           name="row_label" 
                           id="row_label" 
                           required 
                           list="row_categories"
                           placeholder="e.g. Programming Languages"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    
                    <datalist id="row_categories">
                        @foreach($rowLabels as $label)
                            <option value="{{ $label }}">
                        @endforeach
                    </datalist>
                    @error('row_label')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon/Image (Cropper) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Tool Icon (Optional PNG)</label>
                    
                    <div x-show="!imageSelected" class="relative w-full h-32 border-2 border-dashed border-slate-700 hover:border-cyan-500 rounded-xl flex flex-col items-center justify-center cursor-pointer transition-colors bg-slate-950/50 overflow-hidden group">
                        <input type="file" @change="fileSelected" accept="image/png, image/jpeg, image/svg+xml" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <svg class="w-8 h-8 text-slate-500 group-hover:text-cyan-400 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm text-slate-400 font-medium group-hover:text-cyan-400 transition-colors">Click or drag image</span>
                    </div>

                    <div x-show="imageSelected" style="display: none;" class="space-y-3">
                        <div class="w-full bg-slate-950 rounded-xl overflow-hidden border border-slate-800" style="max-height: 300px;">
                            <img id="cropper-image" src="" class="max-w-full hidden">
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="resetImage" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-lg transition-colors text-xs uppercase tracking-widest">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow shadow-cyan-500/10 active:scale-95 transition-all">
                    Add Tool
                </button>

            </form>
        </div>

        <!-- Right Side: Tool Items Listing -->
        <div class="lg:col-span-8 bg-slate-900 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-6">
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Marquee Rows
            </h2>

            @forelse($grouped as $cat => $list)
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider font-mono mb-3 border-b border-slate-950 pb-1.5">{{ $cat }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($list as $tool)
                            <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl flex flex-col items-center gap-3 hover:border-slate-800 transition-colors relative group">
                                
                                @if($tool->image_path)
                                    <div class="w-12 h-12 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $tool->image_path) }}" alt="{{ $tool->name }}" class="w-full h-full object-contain">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded border border-slate-800 bg-slate-900 flex items-center justify-center text-slate-500 text-xs font-bold uppercase">
                                        {{ substr($tool->name, 0, 2) }}
                                    </div>
                                @endif
                                
                                <span class="text-xs font-semibold text-slate-200 text-center truncate w-full">{{ $tool->name }}</span>
                                
                                <form action="{{ route('admin.tools.delete', $tool->id) }}" method="POST" onsubmit="return confirm('Remove {{ $tool->name }}?');" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    <button type="submit" class="text-slate-500 hover:text-rose-400 p-1 bg-slate-900 rounded-full transition-colors" title="Delete Tool">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500">
                    <p class="text-sm font-medium">No tools added yet.</p>
                </div>
            @endforelse

        </div>

    </div>

    <!-- Include CropperJS CSS/JS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        function toolCropperData() {
            return {
                imageSelected: false,
                cropper: null,
                croppedData: '',
                
                fileSelected(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = document.getElementById('cropper-image');
                        img.src = event.target.result;
                        img.classList.remove('hidden');
                        this.imageSelected = true;

                        // Give it a tiny delay to render the UI before init Cropper
                        setTimeout(() => {
                            if (this.cropper) {
                                this.cropper.destroy();
                            }
                            this.cropper = new Cropper(img, {
                                aspectRatio: 1, // Square icons
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
                    img.src = '';
                    img.classList.add('hidden');
                },

                onSubmit(e) {
                    if (this.cropper) {
                        // Extract base64 of cropped image
                        const canvas = this.cropper.getCroppedCanvas({
                            width: 256,
                            height: 256
                        });
                        this.croppedData = canvas.toDataURL('image/png');
                    }
                }
            }
        }
    </script>
@endsection
