<div class="mu-section" x-data="{ 
    // Main Media
    @php
        $initMediaType = '';
        if (isset($project)) {
            if ($project->main_media_type) {
                $initMediaType = $project->main_media_type;
            } elseif ($project->thumbnail_video_path || $project->video_url) {
                $initMediaType = 'video';
            } elseif ($project->main_image_path || !empty($project->thumbnail_images) || $project->thumbnail_path || $project->media_type == 'image') {
                $initMediaType = 'image';
            }
        }
    @endphp
    mainMediaType: '{{ $initMediaType }}', 
    loopStart: {{ $project->video_loop_start ?? 0 }}, 
    loopEnd: {{ $project->video_loop_end ?? 0 }}, 
    videoDuration: 0,
    hasMainVideo: {{ (isset($project) && ($project->main_video_path || $project->thumbnail_video_path || $project->video_url)) ? 'true' : 'false' }},
    isDraggingMain: false,
    isScrubbing: false,
    previewMainImages: [],
    filmstripReady: false,
    
    // Custom Thumbnail
    useCustomThumbnail: {{ (isset($project) && $project->use_custom_thumbnail) ? 'true' : 'false' }},
    isDraggingThumb: false,
    thumbPreview: null,
    removeThumbnail: false,
    cropper: null,
    
    // Featured Thumbnail
    isDraggingFeaturedThumb: false,
    featuredThumbPreview: null,
    removeFeaturedThumbnail: false,
    featuredCropper: null,
    
    init() {
        this.$nextTick(() => {
            // Small delay to ensure Alpine x-show has fully rendered the video div
            setTimeout(() => {
                if (this.mainMediaType === 'video' || this.hasMainVideo) {
                    let vid = document.getElementById('main-video-player');
                    if (vid) {
                        vid.muted = true;
                        if (vid.src && vid.src.startsWith('http') && !vid.src.startsWith('blob:')) {
                            fetch(vid.src)
                                .then(response => response.blob())
                                .then(blob => {
                                    let url = URL.createObjectURL(blob);
                                    vid.src = url;
                                    vid.onloadedmetadata = () => {
                                        vid.onloadedmetadata = null;
                                        this.setupVideo(vid);
                                    };
                                    vid.load();
                                })
                                .catch(err => {
                                    console.error('Failed to fetch video as blob:', err);
                                    if (vid.readyState >= 1) this.setupVideo(vid);
                                    else {
                                        vid.addEventListener('loadedmetadata', () => this.setupVideo(vid), { once: true });
                                        vid.load();
                                    }
                                });
                        } else {
                            if (vid.readyState >= 1) {
                                this.setupVideo(vid);
                            } else {
                                vid.addEventListener('loadedmetadata', () => this.setupVideo(vid), { once: true });
                                if (vid.src) vid.load();
                            }
                        }
                    }
                }
            }, 100);
        });
    },

    _isLooping: false,

    setupVideo(vid) {
        this.videoDuration = vid.duration || 15;
        // Clamp loopEnd to video duration
        if (this.loopEnd <= 0 || this.loopEnd > this.videoDuration) {
            this.loopEnd = Math.min(this.videoDuration, 15);
        }
        if (this.loopStart >= this.loopEnd) {
            this.loopStart = 0;
        }
        vid.muted = true;
        vid.playsInline = true;
        
        let tc = document.getElementById('main-trim-controls');
        if (tc) tc.classList.remove('hidden');
        
        // Generate filmstrip and init the custom trimmer
        this.generateFilmstrip(vid);

        // Clear any previous loop handler
        vid.ontimeupdate = null;
        this._isLooping = false;

        // Seek to loop start, then play, then enable the loop handler
        let startPlayback = () => {
            vid.currentTime = this.loopStart;
            vid.addEventListener('seeked', () => {
                vid.play().then(() => {
                    // Only enable the loop handler AFTER playback has started
                    this._isLooping = true;
                    vid.ontimeupdate = () => {
                        if (!this._isLooping || this.isScrubbing || vid.seeking || vid.paused) return;

                        if (vid.currentTime >= this.loopEnd) {
                            vid.currentTime = this.loopStart;
                        }
                        
                        // Sync hidden inputs
                        let si = document.getElementById('video_loop_start_input');
                        let ei = document.getElementById('video_loop_end_input');
                        if(si && si.value != this.loopStart) si.value = this.loopStart;
                        if(ei && ei.value != this.loopEnd) ei.value = this.loopEnd;
                    };
                }).catch(e => console.log('Autoplay prevented:', e));
            }, { once: true });
        };

        if (vid.readyState >= 3) {
            startPlayback();
        } else {
            vid.addEventListener('canplay', startPlayback, { once: true });
        }
    },

    formatTime(seconds) {
        let s = Math.max(0, Math.floor(seconds));
        let h = Math.floor(s / 3600);
        let m = Math.floor((s % 3600) / 60);
        let sec = s % 60;
        return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(sec).padStart(2,'0');
    },

    async generateFilmstrip(vid) {
        let container = document.getElementById('filmstrip-container');
        let canvas = document.getElementById('filmstrip-canvas');
        if (!container || !canvas) return;

        let ctx = canvas.getContext('2d');
        let duration = this.videoDuration;
        let frameCount = 8;
        
        let containerW = container.getBoundingClientRect().width;
        if (containerW <= 0) containerW = container.offsetWidth;
        if (containerW <= 0) containerW = canvas.parentElement.offsetWidth;
        if (containerW <= 0) containerW = 600;

        let frameWidth = Math.floor(containerW / frameCount);
        let frameHeight = 56;
        
        canvas.width = frameWidth * frameCount;
        canvas.height = frameHeight;
        canvas.style.width = '100%';
        canvas.style.height = frameHeight + 'px';

        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        let srcUrl = vid.src || vid.currentSrc;
        if (!srcUrl) return;

        this.filmstripReady = true;
        this.initTrimmerHandles(vid, duration, container);

        try {
            await this.extractFramesWithHiddenVideo(srcUrl, duration, frameCount, frameWidth, frameHeight, ctx, canvas);
        } catch (e) {
            console.warn('Hidden video extraction failed, falling back to main player:', e);
            await this.extractFramesWithMainVideo(vid, duration, frameCount, frameWidth, frameHeight, ctx, canvas);
        }
    },

    async extractFramesWithHiddenVideo(srcUrl, duration, frameCount, frameWidth, frameHeight, ctx, canvas) {
        let extractor = document.createElement('video');
        extractor.muted = true;
        extractor.playsInline = true;
        extractor.preload = 'auto';
        
        // Ensure browser decodes frames by putting it in DOM
        extractor.style.position = 'fixed';
        extractor.style.top = '0';
        extractor.style.left = '0';
        extractor.style.width = '2px';
        extractor.style.height = '2px';
        extractor.style.opacity = '0.01';
        extractor.style.pointerEvents = 'none';
        extractor.style.zIndex = '-9999';
        document.body.appendChild(extractor);
        
        extractor.src = srcUrl;
        
        try {
            await new Promise((resolve, reject) => {
                let resolved = false;
                extractor.onloadeddata = () => { if (!resolved) { resolved = true; resolve(); } };
                extractor.onerror = () => { if (!resolved) { resolved = true; reject(new Error('Extractor error')); } };
                extractor.load();
                setTimeout(() => {
                    if (!resolved) {
                        if (extractor.readyState >= 2) { resolved = true; resolve(); }
                        else { resolved = true; reject(new Error('Load timeout')); }
                    }
                }, 4000);
            });
            
            for (let i = 0; i < frameCount; i++) {
                let seekTime = (i / (frameCount - 1)) * duration;
                seekTime = Math.min(seekTime, duration - 0.2);
                if (seekTime < 0) seekTime = 0;
                
                extractor.currentTime = seekTime;
                
                await new Promise(r => {
                    let onSeeked = () => {
                        extractor.removeEventListener('seeked', onSeeked);
                        setTimeout(r, 80);
                    };
                    extractor.addEventListener('seeked', onSeeked);
                    // Failsafe for seeked event
                    setTimeout(() => {
                        extractor.removeEventListener('seeked', onSeeked);
                        r();
                    }, 1000);
                });
                
                if (extractor.videoWidth > 0) {
                    ctx.drawImage(extractor, i * frameWidth, 0, frameWidth, frameHeight);
                    // Check if it actually drew anything (if the pixel is completely black, it might have failed, but we assume success if no error thrown)
                } else {
                    throw new Error('Video width is 0');
                }
            }
        } finally {
            if (extractor.parentNode) {
                extractor.remove();
            }
        }
    },

    async extractFramesWithMainVideo(vid, duration, frameCount, frameWidth, frameHeight, ctx, canvas) {
        let originalTime = vid.currentTime;
        let originalOnTimeUpdate = vid.ontimeupdate;
        vid.ontimeupdate = null;
        this._isLooping = false;
        vid.pause();

        try {
            for (let i = 0; i < frameCount; i++) {
                let seekTime = (i / (frameCount - 1)) * duration;
                seekTime = Math.min(seekTime, duration - 0.2);
                if (seekTime < 0) seekTime = 0;
                
                vid.currentTime = seekTime;
                
                await new Promise(r => {
                    let onSeeked = () => {
                        vid.removeEventListener('seeked', onSeeked);
                        setTimeout(r, 80);
                    };
                    vid.addEventListener('seeked', onSeeked);
                    setTimeout(() => {
                        vid.removeEventListener('seeked', onSeeked);
                        r();
                    }, 1000);
                });
                
                if (vid.videoWidth > 0) {
                    ctx.drawImage(vid, i * frameWidth, 0, frameWidth, frameHeight);
                }
            }
        } catch (e) {
            console.error('Main video fallback failed:', e);
        } finally {
            // Restore playback state
            vid.currentTime = this.loopStart;
            vid.ontimeupdate = originalOnTimeUpdate;
            vid.addEventListener('seeked', () => {
                vid.play().then(() => {
                    this._isLooping = true;
                }).catch(e => {});
            }, { once: true });
        }
    },

    initTrimmerHandles(vid, duration, container) {
        let leftHandle = document.getElementById('trim-handle-left');
        let rightHandle = document.getElementById('trim-handle-right');
        let overlay = document.getElementById('trim-selection-overlay');
        let dimLeft = document.getElementById('trim-dim-left');
        let dimRight = document.getElementById('trim-dim-right');
        if (!leftHandle || !rightHandle || !overlay) return;

        // Clone handles to strip old event listeners (if this is called again)
        let newLeft = leftHandle.cloneNode(true);
        leftHandle.parentNode.replaceChild(newLeft, leftHandle);
        leftHandle = newLeft;

        let newRight = rightHandle.cloneNode(true);
        rightHandle.parentNode.replaceChild(newRight, rightHandle);
        rightHandle = newRight;
        
        let newOverlay = overlay.cloneNode(true);
        overlay.parentNode.replaceChild(newOverlay, overlay);
        overlay = newOverlay;

        let that = this;
        let containerWidth = container.offsetWidth;
        if (containerWidth <= 0) containerWidth = 600; // fallback if zero
        
        function timeToX(t) { return (t / duration) * containerWidth; }
        function xToTime(x) { return (x / containerWidth) * duration; }

        function updatePositions() {
            let lx = timeToX(that.loopStart);
            let rx = timeToX(that.loopEnd);
            leftHandle.style.left = lx + 'px';
            rightHandle.style.left = rx + 'px';
            overlay.style.left = lx + 'px';
            overlay.style.width = Math.max(0, rx - lx) + 'px';
            dimLeft.style.width = lx + 'px';
            dimRight.style.left = rx + 'px';
            dimRight.style.width = Math.max(0, containerWidth - rx) + 'px';
        }
        updatePositions();

        function makeDraggable(handle, isLeft) {
            function onPointerDown(e) {
                e.preventDefault();
                e.stopPropagation(); // prevent triggering overlay drag
                that.isScrubbing = true;
                that._isLooping = false;
                vid.pause();
                document.addEventListener('pointermove', onPointerMove);
                document.addEventListener('pointerup', onPointerUp);
            }
            function onPointerMove(e) {
                if (!that.isScrubbing) return;
                let rect = container.getBoundingClientRect();
                let x = Math.max(0, Math.min(e.clientX - rect.left, containerWidth));
                let t = xToTime(x);
                
                if (isLeft) {
                    t = Math.max(0, Math.min(t, that.loopEnd - 1)); // min 1s gap
                    // Enforce max 15s span
                    if (that.loopEnd - t > 15) {
                        t = that.loopEnd - 15;
                    }
                    that.loopStart = t;
                } else {
                    t = Math.max(that.loopStart + 1, Math.min(t, duration));
                    // Enforce max 15s span
                    if (t - that.loopStart > 15) {
                        t = that.loopStart + 15;
                    }
                    that.loopEnd = t;
                }
                vid.currentTime = t; // Scrub to frame
                updatePositions();
            }
            function onPointerUp() {
                that.isScrubbing = false;
                document.removeEventListener('pointermove', onPointerMove);
                document.removeEventListener('pointerup', onPointerUp);
                vid.currentTime = that.loopStart;
                vid.addEventListener('seeked', () => {
                    vid.play().then(() => {
                        that._isLooping = true;
                    }).catch(e => {});
                }, { once: true });
                // Sync hidden inputs
                let si = document.getElementById('video_loop_start_input');
                let ei = document.getElementById('video_loop_end_input');
                if(si) si.value = that.loopStart;
                if(ei) ei.value = that.loopEnd;
            }
            handle.addEventListener('pointerdown', onPointerDown);
        }

        function makeOverlayDraggable(ov) {
            let startClientX = 0;
            let startLoopStart = 0;
            let startLoopEnd = 0;

            function onPointerDown(e) {
                e.preventDefault();
                that.isScrubbing = true;
                that._isLooping = false;
                vid.pause();
                startClientX = e.clientX;
                startLoopStart = that.loopStart;
                startLoopEnd = that.loopEnd;
                ov.style.cursor = 'grabbing';
                document.addEventListener('pointermove', onPointerMove);
                document.addEventListener('pointerup', onPointerUp);
            }
            function onPointerMove(e) {
                if (!that.isScrubbing) return;
                let deltaX = e.clientX - startClientX;
                let deltaT = xToTime(deltaX);
                
                let newStart = startLoopStart + deltaT;
                let newEnd = startLoopEnd + deltaT;
                
                // Clamp to edges
                if (newStart < 0) {
                    newStart = 0;
                    newEnd = startLoopEnd - startLoopStart;
                }
                if (newEnd > duration) {
                    newEnd = duration;
                    newStart = duration - (startLoopEnd - startLoopStart);
                }
                
                that.loopStart = newStart;
                that.loopEnd = newEnd;
                vid.currentTime = that.loopStart; // scrub to start
                updatePositions();
            }
            function onPointerUp() {
                that.isScrubbing = false;
                ov.style.cursor = 'grab';
                document.removeEventListener('pointermove', onPointerMove);
                document.removeEventListener('pointerup', onPointerUp);
                vid.currentTime = that.loopStart;
                vid.addEventListener('seeked', () => {
                    vid.play().then(() => {
                        that._isLooping = true;
                    }).catch(e => {});
                }, { once: true });
                let si = document.getElementById('video_loop_start_input');
                let ei = document.getElementById('video_loop_end_input');
                if(si) si.value = that.loopStart;
                if(ei) ei.value = that.loopEnd;
            }
            
            ov.style.cursor = 'grab';
            ov.addEventListener('pointerdown', onPointerDown);
        }

        makeDraggable(leftHandle, true);
        makeDraggable(rightHandle, false);
        makeOverlayDraggable(overlay);
    },

    processMainFiles(files) {
        if (!files || files.length === 0) return;
        let firstFile = files[0];
        
        if (firstFile.type.startsWith('video/')) {
            this.mainMediaType = 'video';
            this.hasMainVideo = true;
            this.previewMainImages = [];
            this.filmstripReady = false;
            
            // Reset loop state for the new video
            this._isLooping = false;
            this.loopStart = 0;
            this.loopEnd = 0;
            
            let url = window.URL.createObjectURL(firstFile);
            let vid = document.getElementById('main-video-player');
            
            // Clear any existing handlers before changing src
            vid.ontimeupdate = null;
            vid.onloadedmetadata = null;
            
            let that = this;
            vid.onloadedmetadata = () => {
                vid.onloadedmetadata = null; // prevent re-fire
                that.setupVideo(vid);
            };

            vid.src = url;
            vid.classList.remove('hidden');
            vid.style.display = 'block';
            let ph = document.getElementById('main-video-placeholder');
            if(ph) ph.classList.add('hidden');
        } else if (firstFile.type.startsWith('image/')) {
            this.mainMediaType = 'image';
            this.hasMainVideo = false;
            this.previewMainImages = [];
            for (let i = 0; i < files.length; i++) {
                if(files[i].type.startsWith('image/')) {
                    this.previewMainImages.push(window.URL.createObjectURL(files[i]));
                }
            }
        }
    },
    
    processThumbFile(file) {
        if (!file || !file.type.startsWith('image/')) return;
        this.removeThumbnail = false;
        let url = window.URL.createObjectURL(file);
        this.thumbPreview = url;
        
        this.$nextTick(() => {
            let img = document.getElementById('thumb-crop-image');
            img.src = url;
            if(this.cropper) { this.cropper.destroy(); }
            this.cropper = new Cropper(img, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                crop: (event) => {
                    let canvas = this.cropper.getCroppedCanvas({ width: 1280, height: 720 });
                    document.getElementById('custom_thumbnail_base64').value = canvas.toDataURL('image/jpeg', 0.8);
                }
            });
        });
    },

    removeCustomThumb() {
        this.removeThumbnail = true;
        this.thumbPreview = null;
        if(this.cropper) { this.cropper.destroy(); this.cropper = null; }
        document.getElementById('custom_thumbnail_upload').value = '';
        document.getElementById('custom_thumbnail_base64').value = '';
    },

    processFeaturedThumbFile(file) {
        if (!file || !file.type.startsWith('image/')) return;
        this.removeFeaturedThumbnail = false;
        let url = window.URL.createObjectURL(file);
        this.featuredThumbPreview = url;
        
        this.$nextTick(() => {
            let img = document.getElementById('featured-thumb-crop-image');
            img.src = url;
            if(this.featuredCropper) { this.featuredCropper.destroy(); }
            this.featuredCropper = new Cropper(img, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                crop: (event) => {
                    let canvas = this.featuredCropper.getCroppedCanvas({ width: 1280, height: 720 });
                    document.getElementById('featured_thumbnail_base64').value = canvas.toDataURL('image/jpeg', 0.8);
                }
            });
        });
    },

    removeFeaturedThumb() {
        this.removeFeaturedThumbnail = true;
        this.featuredThumbPreview = null;
        if(this.featuredCropper) { this.featuredCropper.destroy(); this.featuredCropper = null; }
        document.getElementById('featured_thumbnail_upload').value = '';
        document.getElementById('featured_thumbnail_base64').value = '';
    }
}">

    <style>
        .mu-section { display: flex; flex-direction: column; gap: 0.65rem; }
        .mu-section-title {
            font-family:'Outfit',sans-serif; font-size:0.9rem;
            font-weight:700; color:#1a1207;
            padding-bottom:0.5rem; border-bottom:1px solid #E2DDD3;
        }
        .mu-dropzone {
            position:relative; background:#F7F5EE;
            border:2px dashed #D8D4C8; border-radius:0.75rem;
            padding:1.5rem; text-align:center;
            transition:all 0.18s; cursor:pointer;
        }
        .mu-dropzone.dragging { border-color:#6829AA; background:#F3ECFF; }
        .mu-dropzone:hover { border-color:#C4BDB2; }
        .mu-dropzone-icon {
            width:2.5rem; height:2.5rem; border-radius:50%;
            background:#EEE6FF; display:flex; align-items:center;
            justify-content:center; margin:0 auto 0.6rem; color:#6829AA;
        }
        .mu-dropzone-title {
            font-family:'Outfit',sans-serif; font-size:0.85rem;
            font-weight:700; color:#1a1207; margin-bottom:0.15rem;
        }
        .mu-dropzone-sub { font-size:0.72rem; color:#9B9589; }
        .mu-dropzone-tip {
            font-family:'Space Mono',monospace; font-size:0.58rem;
            color:#6829AA; margin-top:0.5rem;
        }
        .mu-preview-card {
            background:#fff; border:1px solid #E2DDD3;
            border-radius:0.65rem; padding:0.75rem; overflow:hidden;
        }
        .mu-preview-label {
            font-family:'Space Mono',monospace; font-size:0.58rem;
            text-transform:uppercase; letter-spacing:0.08em;
            color:#9B9589; margin-bottom:0.4rem; display:block;
        }
        .mu-img-grid {
            display:grid; grid-template-columns:repeat(auto-fill,minmax(100px,1fr)); gap:0.5rem;
        }
        .mu-img-grid img {
            width:100%; aspect-ratio:16/9; object-fit:cover;
            border-radius:0.5rem; border:1px solid #E2DDD3;
        }
        .mu-video-wrap {
            width:100%; background:#000; border-radius:0.65rem;
            display:flex; flex-direction:column;
            align-items:center; justify-content:center; overflow:hidden;
            position:relative;
        }
        .mu-video-wrap video { width:100%; height:auto; max-height:260px; object-fit:contain; display:block; }
        .mu-no-media {
            width:100%; min-height:120px; background:#F7F5EE;
            border-radius:0.5rem; border:1px solid #E2DDD3;
            display:flex; align-items:center; justify-content:center;
            font-family:'Space Mono',monospace; font-size:0.65rem; color:#C4BDB2;
        }

        /* === FILMSTRIP TRIMMER === */
        .mu-filmstrip-wrap {
            margin-top: 0.75rem;
            padding: 0;
        }
        .mu-filmstrip-track {
            position: relative;
            width: 100%;
            height: 56px;
            border-radius: 0.35rem;
            overflow: visible;
            user-select: none;
            touch-action: none;
        }
        .mu-filmstrip-track canvas {
            display: block;
            border-radius: 0.35rem;
        }
        /* Dim overlays for areas outside the selected span */
        .mu-filmstrip-dim {
            position: absolute;
            top: 0;
            height: 100%;
            background: rgba(0,0,0,0.55);
            pointer-events: none;
            z-index: 2;
        }
        .mu-filmstrip-dim-left { left: 0; border-radius: 0.35rem 0 0 0.35rem; }
        .mu-filmstrip-dim-right { right: 0; border-radius: 0 0.35rem 0.35rem 0; }
        /* Selection highlight border */
        .mu-filmstrip-selection {
            position: absolute;
            top: 0;
            height: 100%;
            border: 2.5px solid #fff;
            border-radius: 0.2rem;
            box-sizing: border-box;
            /* Remove pointer-events: none to allow dragging */
            z-index: 3;
        }
        /* Draggable handle bars */
        .mu-trim-handle {
            position: absolute;
            top: -3px;
            width: 16px;
            height: calc(100% + 6px);
            background: #fff;
            border: 2px solid #fff;
            border-radius: 4px;
            cursor: ew-resize;
            z-index: 5;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 6px rgba(0,0,0,0.3);
            transition: box-shadow 0.15s;
        }
        .mu-trim-handle:hover, .mu-trim-handle:active {
            box-shadow: 0 2px 12px rgba(104,41,170,0.4);
        }
        .mu-trim-handle-bar {
            width: 2.5px;
            height: 18px;
            background: #999;
            border-radius: 2px;
            margin: 0 1px;
        }
        /* Time label */
        .mu-filmstrip-time {
            font-family: 'Space Mono', monospace;
            font-size: 0.68rem;
            color: #5A5248;
            text-align: center;
            margin-top: 0.55rem;
            letter-spacing: 0.04em;
        }

        /* Custom Cropper tweaks */
        .cropper-view-box, .cropper-face { border-radius: 0.5rem; }
    </style>

    <input type="hidden" name="main_media_type" x-model="mainMediaType">
    <input type="hidden" name="use_custom_thumbnail" :value="useCustomThumbnail ? '1' : '0'">
    <input type="hidden" name="remove_thumbnail" :value="removeThumbnail ? '1' : '0'">
    <input type="hidden" name="custom_thumbnail_base64" id="custom_thumbnail_base64">
    <input type="hidden" name="remove_featured_thumbnail" :value="removeFeaturedThumbnail ? '1' : '0'">
    <input type="hidden" name="featured_thumbnail_base64" id="featured_thumbnail_base64">
    <input type="hidden" name="video_loop_start" id="video_loop_start_input" :value="loopStart">
    <input type="hidden" name="video_loop_end" id="video_loop_end_input" :value="loopEnd">

    <!-- MAIN MEDIA UPLOAD & PREVIEW -->
    <div class="mu-preview-card" style="margin-top:0.5rem; position: relative;"
         :style="isDraggingMain ? 'border: 2px dashed #6829AA; background: #F3ECFF;' : ''"
         @dragover.prevent="isDraggingMain = true" @dragleave.prevent="isDraggingMain = false"
         @drop.prevent="isDraggingMain = false; if($event.dataTransfer.files.length) { document.getElementById('main_media_upload').files = $event.dataTransfer.files; processMainFiles($event.dataTransfer.files); }">
         
        <input type="file" name="main_media_upload[]" id="main_media_upload" multiple accept="image/*,video/*" class="hidden" style="display:none;" @change="if($event.target.files.length) processMainFiles($event.target.files)">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:0.6rem;">
            <span class="mu-preview-label" style="margin: 0; color:#B0A99F;">Media Display/Player</span>
            <button type="button" @click="document.getElementById('main_media_upload').click()" style="font-family:'Space Mono',monospace; font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:#6829AA; background:transparent; border:none; cursor:pointer; font-weight:700;" x-show="mainMediaType === 'video' || mainMediaType === 'image' || hasMainVideo">
                <svg style="width:11px; height:11px; display:inline; margin-bottom:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> CHANGE MEDIA
            </button>
        </div>

        <!-- Initial empty state dropzone (only shows when NO media is present) -->
        <div x-show="!(mainMediaType === 'video' || mainMediaType === 'image' || hasMainVideo)" 
             class="mu-dropzone" style="margin-top: 0; border: 2px dashed #D8D4C8; border-radius:0.5rem; background:#F7F5EE; padding:2rem 1.5rem; text-align:center; cursor:pointer; min-height: 140px;"
             @click="document.getElementById('main_media_upload').click()">
            <div style="pointer-events:none;">
                <div class="mu-dropzone-icon"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg></div>
                <p class="mu-dropzone-title">Main Media Drop Zone</p>
                <p class="mu-dropzone-sub">Drop video or images (Hero Display)</p>
            </div>
        </div>

        <!-- Main Image Preview -->
        <div x-show="mainMediaType === 'image'" style="display:none;">
            <template x-if="previewMainImages.length === 0">
                <div>
                    @if(isset($project) && !empty($project->main_images))
                        <div class="mu-img-grid">
                            @foreach($project->main_images as $img)
                                <img src="{{ asset('storage/' . $img) }}">
                            @endforeach
                        </div>
                    @elseif(isset($project) && $project->main_image_path)
                        <img src="{{ asset('storage/' . $project->main_image_path) }}" style="width:100%;max-height:200px;object-fit:contain;border-radius:0.5rem;">
                    @elseif(isset($project) && !empty($project->thumbnail_images))
                        <div class="mu-img-grid">
                            @foreach($project->thumbnail_images as $img)
                                <img src="{{ asset('storage/' . $img) }}">
                            @endforeach
                        </div>
                    @elseif(isset($project) && $project->thumbnail_path)
                        <img src="{{ Str::startsWith($project->thumbnail_path, 'http') ? $project->thumbnail_path : asset('storage/' . $project->thumbnail_path) }}" style="width:100%;max-height:200px;object-fit:contain;border-radius:0.5rem;">
                    @else
                        <div class="mu-no-media">No image selected</div>
                    @endif
                </div>
            </template>
            <template x-if="previewMainImages.length > 0">
                <div class="mu-img-grid">
                    <template x-for="(imgSrc, index) in previewMainImages" :key="index">
                        <img :src="imgSrc">
                    </template>
                </div>
            </template>
        </div>

        <!-- Main Video Preview -->
        <div x-show="mainMediaType === 'video'" class="relative" style="display:none;">
            <div class="mu-video-wrap">
                @if(isset($project) && $project->main_video_path)
                    <video id="main-video-player" src="{{ asset('storage/' . $project->main_video_path) }}" style="width:100%;height:auto;max-height:260px;object-fit:contain;" muted playsinline controls preload="metadata"></video>
                @elseif(isset($project) && $project->thumbnail_video_path)
                    <video id="main-video-player" src="{{ asset('storage/' . $project->thumbnail_video_path) }}" style="width:100%;height:auto;max-height:260px;object-fit:contain;" muted playsinline controls preload="metadata"></video>
                @elseif(isset($project) && $project->video_url)
                    <video id="main-video-player" src="{{ $project->video_url }}" style="width:100%;height:auto;max-height:260px;object-fit:contain;" muted playsinline controls preload="metadata"></video>
                @else
                    <video id="main-video-player" class="hidden" style="width:100%;height:auto;max-height:260px;object-fit:contain;display:none;" muted playsinline controls preload="metadata"></video>
                    <span id="main-video-placeholder" class="mu-no-media" style="min-height:140px;" x-show="!hasMainVideo">No video selected</span>
                @endif
            </div>
                
            <!-- Filmstrip Frame Span Selector -->
            <div class="mu-filmstrip-wrap {{ (isset($project) && ($project->main_video_path || $project->thumbnail_video_path || $project->video_url)) ? '' : 'hidden' }}" id="main-trim-controls">
                <div class="mu-filmstrip-track" id="filmstrip-container">
                    <canvas id="filmstrip-canvas"></canvas>
                    
                    <!-- Dim overlays -->
                    <div class="mu-filmstrip-dim mu-filmstrip-dim-left" id="trim-dim-left"></div>
                    <div class="mu-filmstrip-dim mu-filmstrip-dim-right" id="trim-dim-right"></div>
                    
                    <!-- Selection highlight -->
                    <div class="mu-filmstrip-selection" id="trim-selection-overlay"></div>

                    <!-- Left handle -->
                    <div class="mu-trim-handle" id="trim-handle-left">
                        <span class="mu-trim-handle-bar"></span>
                        <span class="mu-trim-handle-bar"></span>
                    </div>
                    <!-- Right handle -->
                    <div class="mu-trim-handle" id="trim-handle-right">
                        <span class="mu-trim-handle-bar"></span>
                        <span class="mu-trim-handle-bar"></span>
                    </div>
                </div>

                <!-- Time display -->
                <p class="mu-filmstrip-time">
                    <span x-text="formatTime(loopStart)">00:00:00</span> – <span x-text="formatTime(loopEnd)">00:00:15</span>
                </p>
            </div>
        </div>
    </div>

    <div class="pe-section-divider" style="margin: 1.25rem 0 0.85rem;"></div>

    <!-- CUSTOM THUMBNAIL SECTION -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem;">
        <span style="font-family:'Outfit',sans-serif; font-size:1.15rem; font-weight:800; color:#1a1207;">Thumbnail</span>
        <!-- Toggle for custom thumbnail -->
        <div class="pe-featured-row" style="margin:0; padding:0; border:none; background:transparent;" @click="useCustomThumbnail = !useCustomThumbnail">
            <span class="pe-featured-label" style="margin-right: 0.4rem; text-transform:none; font-size:0.72rem;">Use Custom</span>
            <div class="pe-featured-toggle" :class="useCustomThumbnail ? 'on' : ''" style="cursor:pointer;"></div>
        </div>
    </div>
    
    <div x-show="useCustomThumbnail" x-transition style="display:none; margin-top:0.5rem;">
        <div class="mu-dropzone" :class="isDraggingThumb ? 'dragging' : ''"
             @dragover.prevent="isDraggingThumb = true" @dragleave.prevent="isDraggingThumb = false"
             @drop.prevent="isDraggingThumb = false; if($event.dataTransfer.files.length) { document.getElementById('custom_thumbnail_upload').files = $event.dataTransfer.files; processThumbFile($event.dataTransfer.files[0]); }"
             @click="document.getElementById('custom_thumbnail_upload').click()">
            <input type="file" id="custom_thumbnail_upload" accept="image/*" class="hidden" style="display:none;" @change="if($event.target.files.length) processThumbFile($event.target.files[0])">
            <div style="pointer-events:none;">
                <div class="mu-dropzone-icon"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <p class="mu-dropzone-title">Drop Custom Thumbnail Image</p>
                <p class="mu-dropzone-tip">Replaces the default frame span on the grid.</p>
            </div>
        </div>

        <!-- Custom Thumbnail Cropper Preview -->
        <div class="mu-preview-card" style="margin-top:0.5rem;" x-show="thumbPreview || {{ isset($project->thumbnail_path) && $project->thumbnail_path ? 'true' : 'false' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="mu-preview-label" style="margin-bottom:0;">Thumbnail Preview</span>
                <button type="button" @click="removeCustomThumb()" class="text-xs text-red-500 hover:text-red-700 font-bold">Remove Custom Thumbnail</button>
            </div>
            
            <!-- Cropper Container -->
            <div x-show="thumbPreview" style="display:none; width:100%; max-height:400px; overflow:hidden; border-radius:0.5rem; background:#000;">
                <img id="thumb-crop-image" style="max-width:100%; display:block;">
            </div>

            <!-- Existing Custom Thumbnail -->
            <div x-show="!thumbPreview && !removeThumbnail">
                @if(isset($project->thumbnail_path) && $project->thumbnail_path)
                    <img src="{{ asset('storage/' . $project->thumbnail_path) }}" style="width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:0.5rem; border:1px solid #E2DDD3;">
                @endif
            </div>
        </div>
    </div>

    <div class="pe-section-divider" style="margin: 1.25rem 0 0.85rem;" x-show="is_best_work" x-cloak></div>

    <!-- BEST WORK THUMBNAIL SECTION -->
    <div x-show="is_best_work" x-cloak style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem;">
        <span style="font-family:'Outfit',sans-serif; font-size:1.15rem; font-weight:800; color:#1a1207;">Best Work Slider Thumbnail (16:9)</span>
    </div>
    
    <div x-show="is_best_work" x-cloak style="margin-top:0.5rem;">
        <div class="mu-dropzone" :class="isDraggingFeaturedThumb ? 'dragging' : ''"
             @dragover.prevent="isDraggingFeaturedThumb = true" @dragleave.prevent="isDraggingFeaturedThumb = false"
             @drop.prevent="isDraggingFeaturedThumb = false; if($event.dataTransfer.files.length) { document.getElementById('featured_thumbnail_upload').files = $event.dataTransfer.files; processFeaturedThumbFile($event.dataTransfer.files[0]); }"
             @click="document.getElementById('featured_thumbnail_upload').click()">
            <input type="file" id="featured_thumbnail_upload" accept="image/*" class="hidden" style="display:none;" @change="if($event.target.files.length) processFeaturedThumbFile($event.target.files[0])">
            <div style="pointer-events:none;">
                <div class="mu-dropzone-icon"><svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <p class="mu-dropzone-title">Drop Best Work Thumbnail</p>
                <p class="mu-dropzone-tip">Required for the homepage "Best Works" slider.</p>
            </div>
        </div>

        <!-- Featured Thumbnail Cropper Preview -->
        <div class="mu-preview-card" style="margin-top:0.5rem;" x-show="featuredThumbPreview || {{ isset($project->featured_thumbnail) && $project->featured_thumbnail ? 'true' : 'false' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="mu-preview-label" style="margin-bottom:0;">Featured Thumbnail Preview</span>
                <button type="button" @click="removeFeaturedThumb()" class="text-xs text-red-500 hover:text-red-700 font-bold">Remove</button>
            </div>
            
            <!-- Cropper Container -->
            <div x-show="featuredThumbPreview" style="display:none; width:100%; max-height:400px; overflow:hidden; border-radius:0.5rem; background:#000;">
                <img id="featured-thumb-crop-image" style="max-width:100%; display:block;">
            </div>

            <!-- Existing Featured Thumbnail -->
            <div x-show="!featuredThumbPreview && !removeFeaturedThumbnail">
                @if(isset($project->featured_thumbnail) && $project->featured_thumbnail)
                    <img src="{{ asset('storage/' . $project->featured_thumbnail) }}" style="width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:0.5rem; border:1px solid #E2DDD3;">
                @endif
            </div>
        </div>
    </div>

</div>
