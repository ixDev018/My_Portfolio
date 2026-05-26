<div class="space-y-6 pt-6 border-t border-slate-800" x-data="{ 
    thumbnailType: '{{ $project->thumbnail_type ?? 'image' }}', 
    loopStart: {{ $project->video_loop_start ?? 0 }}, 
    loopEnd: {{ $project->video_loop_end ?? 0 }}, 
    hasVideo: {{ (isset($project) && $project->thumbnail_video_path) ? 'true' : 'false' }},
    isDimmed: false,
    timeoutStarted: false,
    isDragging: false,
    previewImages: [],
    
    initSlider(vid, duration) {
        let slider = document.getElementById('video-slider');
        if(slider.noUiSlider) { slider.noUiSlider.destroy(); }
        
        noUiSlider.create(slider, {
            start: [this.loopStart, this.loopEnd > 0 ? this.loopEnd : Math.min(duration, 15)],
            connect: true,
            margin: 1,
            limit: 15,
            range: { 'min': 0, 'max': duration }
        });
        
        slider.querySelectorAll('.noUi-connect').forEach(c => c.style.background = '#06b6d4');
        slider.querySelectorAll('.noUi-handle').forEach(h => {
            h.style.background = '#fff';
            h.style.borderRadius = '4px';
            h.style.boxShadow = '0 0 5px rgba(0,0,0,0.5)';
            h.style.cursor = 'grab';
        });

        let that = this;
        slider.noUiSlider.on('update', function (values, handle) {
            let val = parseFloat(values[handle]);
            if(handle === 0) { 
                that.loopStart = val; 
                if(Math.abs(vid.currentTime - that.loopStart) > 1) {
                    vid.currentTime = that.loopStart;
                }
            } else { 
                that.loopEnd = val; 
            }
            that.isDimmed = false;
            that.timeoutStarted = false;
        });
    },
    
    processFiles(files) {
        if (!files || files.length === 0) return;
        
        let firstFile = files[0];
        
        if (firstFile.type.startsWith('video/')) {
            this.thumbnailType = 'video';
            this.previewImages = [];
            
            let url = window.URL.createObjectURL(firstFile);
            let vid = document.getElementById('video-preview-player');
            vid.src = url;
            vid.classList.remove('hidden');
            let ph = document.getElementById('video-placeholder');
            if(ph) ph.classList.add('hidden');
            document.getElementById('trim-controls').classList.remove('hidden');
            
            let that = this;
            vid.onloadedmetadata = () => {
                let videoDuration = vid.duration;
                that.loopEnd = that.loopEnd > 0 ? that.loopEnd : Math.min(videoDuration, 15);
                vid.currentTime = that.loopStart;
                vid.play();
                that.initSlider(vid, videoDuration);
            };
        } else if (firstFile.type.startsWith('image/')) {
            this.thumbnailType = 'image';
            this.previewImages = [];
            
            // Loop through images and create blob URLs
            for (let i = 0; i < files.length; i++) {
                if(files[i].type.startsWith('image/')) {
                    this.previewImages.push(window.URL.createObjectURL(files[i]));
                }
            }
        }
    },
    
    handleDrop(e) {
        this.isDragging = false;
        let files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('media_upload').files = files;
            this.processFiles(files);
        }
    },
    
    init() {
        if(this.hasVideo) {
            let vid = document.getElementById('video-preview-player');
            let that = this;
            const initVid = () => {
                let videoDuration = vid.duration || 0;
                if(videoDuration > 0) {
                    that.loopEnd = that.loopEnd > 0 ? that.loopEnd : Math.min(videoDuration, 15);
                    vid.currentTime = that.loopStart;
                    vid.play();
                    that.initSlider(vid, videoDuration);
                }
            };
            if (vid.readyState >= 1) {
                initVid();
            } else {
                vid.addEventListener('loadedmetadata', initVid);
            }
            vid.onplay = () => {
                if (!that.timeoutStarted) {
                    that.timeoutStarted = true;
                    setTimeout(() => {
                        vid.pause();
                        that.isDimmed = true;
                    }, 15000);
                }
            };
            vid.ontimeupdate = () => {
                if(vid.currentTime >= that.loopEnd && that.loopEnd > 0) {
                    vid.currentTime = that.loopStart;
                }
            };
        }
    }
}">

    <div class="flex justify-between items-end border-b border-white/10 pb-2">
        <h2 class="text-xl font-bold text-white">2. Thumbnail Media</h2>
        <input type="hidden" name="thumbnail_type" x-model="thumbnailType">
    </div>

    <!-- Smart Drop Zone -->
    <div class="relative bg-slate-900 border-2 border-dashed rounded-xl p-8 text-center transition-colors cursor-pointer"
         :class="isDragging ? 'border-cyan-400 bg-slate-800' : 'border-slate-700 hover:border-slate-500'"
         @dragover.prevent="isDragging = true"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="handleDrop"
         @click="document.getElementById('media_upload').click()">
        
        <input type="file" name="media_upload[]" id="media_upload" multiple accept="image/*,video/*" class="hidden" @change="processFiles($event.target.files)">
        
        <div class="pointer-events-none">
            <div class="mx-auto w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4 text-cyan-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <p class="text-lg font-semibold text-white mb-1">Drag & Drop Media Here</p>
            <p class="text-sm text-slate-400">Supports JPG, PNG, GIF, MP4, WEBM</p>
            <p class="text-xs text-cyan-500/80 mt-2 font-mono">Tip: Drop a video to show trim slider. Drop multiple images for a carousel.</p>
        </div>
    </div>

    <!-- PREVIEW AREA -->
    <div class="bg-black/20 p-5 rounded-2xl border border-white/5" x-show="thumbnailType === 'video' || thumbnailType === 'image'">
        
        <!-- Image Preview Grid -->
        <div x-show="thumbnailType === 'image'" style="display:none;">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Image Preview</label>
            
            <!-- Existing DB Images -->
            <template x-if="previewImages.length === 0">
                <div class="w-full relative">
                    @if(isset($project) && !empty($project->thumbnail_images))
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($project->thumbnail_images as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="w-full aspect-video object-cover rounded-xl border border-white/10">
                            @endforeach
                        </div>
                    @elseif(isset($project) && $project->thumbnail_path)
                        <img src="{{ asset('storage/' . $project->thumbnail_path) }}" class="w-full max-h-[400px] object-contain rounded-xl">
                    @else
                        <div class="w-full min-h-[200px] bg-black rounded-xl border border-white/10 flex items-center justify-center text-slate-600 font-mono text-sm">No image selected</div>
                    @endif
                </div>
            </template>
            
            <!-- New Upload Images -->
            <template x-if="previewImages.length > 0">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="(imgSrc, index) in previewImages" :key="index">
                        <img :src="imgSrc" class="w-full aspect-video object-cover rounded-xl border border-white/10">
                    </template>
                </div>
            </template>
        </div>

        <!-- Video Preview -->
        <div x-show="thumbnailType === 'video'" style="display:none;" class="relative">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 font-mono">Video Thumbnail & Trim</label>
            <div class="w-full bg-black rounded-xl border border-white/10 flex flex-col items-center justify-center overflow-hidden mb-4 shadow-inner relative">
                @if(isset($project) && $project->thumbnail_video_path)
                    <video id="video-preview-player" src="{{ asset('storage/' . $project->thumbnail_video_path) }}" class="w-full h-auto object-contain max-h-[400px]" muted playsinline></video>
                @else
                    <video id="video-preview-player" class="w-full h-auto object-contain hidden max-h-[400px]" muted playsinline></video>
                    <span id="video-placeholder" class="text-slate-600 font-mono text-sm py-20" :class="hasVideo ? 'hidden' : ''">No video selected (16:9 mp4/webm)</span>
                @endif
                
                <!-- Dimming Overlay -->
                <div x-show="isDimmed" style="display: none;"
                     x-transition.opacity.duration.1000ms
                     class="absolute inset-0 bg-black/70 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center p-4"
                     @click="isDimmed = false; document.getElementById('video-preview-player').play(); timeoutStarted = false;">
                    <div class="text-white font-mono text-xs tracking-widest uppercase mb-3 opacity-80">15s Preview Ended</div>
                    <span class="px-5 py-2.5 bg-white text-black font-semibold rounded-full text-sm shadow-xl cursor-pointer">
                        Click to Replay
                    </span>
                </div>
                
                <!-- Trim overlay -->
                <div class="absolute bottom-4 left-4 right-4 bg-black/60 backdrop-blur border border-white/10 p-4 rounded-xl {{ (isset($project) && $project->thumbnail_video_path) ? '' : 'hidden' }}" id="trim-controls">
                    <div class="flex justify-between text-xs font-mono text-white mb-4">
                        <span>Loop Start: <span x-text="loopStart.toFixed(1)"></span>s</span>
                        <span class="text-cyan-400 font-bold">Span: <span x-text="(loopEnd - loopStart).toFixed(1)"></span>s (Max 15s)</span>
                        <span>Loop End: <span x-text="loopEnd.toFixed(1)"></span>s</span>
                    </div>
                    
                    <!-- NoUiSlider Container -->
                    <div id="video-slider" class="mx-2 mb-2"></div>
                    
                    <p class="text-[10px] text-slate-400 text-center mt-3">Drag handles to select up to a 15-second loop for the thumbnail.</p>
                    <input type="hidden" name="video_loop_start" x-model="loopStart">
                    <input type="hidden" name="video_loop_end" x-model="loopEnd">
                </div>
            </div>
        </div>

    </div>

    <!-- External Video Links -->
    <div x-show="thumbnailType === 'video'">
        <label for="full_video_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">External Full Video Link (For 15s Preview End)</label>
        <input type="url" name="full_video_url" id="full_video_url" value="{{ old('full_video_url', $project->full_video_url ?? '') }}" placeholder="https://youtube.com/... or https://vimeo.com/..." class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200 mb-4">
        <p class="text-[11px] text-slate-500 -mt-2 mb-4 font-mono">Visitors will be linked here after the 15-second preview ends on thumbnails.</p>
    </div>

    <div x-show="thumbnailType === 'video'">
        <label for="video_url" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">External Full Video URL (Optional fallback)</label>
        <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $project->video_url ?? '') }}" placeholder="https://youtube.com/..." class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-3 text-slate-200 text-sm outline-none transition-all duration-200">
    </div>

</div>
