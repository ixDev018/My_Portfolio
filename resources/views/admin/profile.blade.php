@extends('admin.layout')

@section('admin_content')

@php
    $defaultHeroHtml =
'<div class="max-w-7xl mx-auto px-6 flex-grow flex flex-col justify-center items-center text-center relative z-10 w-full">

    <!-- Hero Typography Container -->
    <div class="inline-flex flex-col items-stretch select-none mx-auto mb-6">

        <!-- Turning Ideas Into (justified) -->
        <div class="flex justify-between w-full font-display uppercase text-white leading-none select-none relative z-10"
             style="font-size: clamp(12px, 4vw, 45px);">
            ' . '@' . 'php $topText = $profile->hero_top_text ?? \'TURNING IDEAS INTO\'; ' . '@' . 'endphp
            ' . '@' . 'foreach(explode(\' \', $topText) as $word)
                <span>{{ $word }}</span>
            ' . '@' . 'endforeach
        </div>

        <!-- REALITY (thin border, yellow fill, no shadows) -->
        <h1 class="text-yellow-400 font-normal leading-none uppercase font-display tracking-tight select-none text-center"
            style="font-size: clamp(50px, 18vw, 205.84px); margin-top: -0.12em;">
            {{ $profile->hero_title ?? \'REALITY\' }}
        </h1>

    </div>

    <!-- One Pixel At A Time -->
    <p class="text-xs sm:text-sm tracking-[0.4em] uppercase text-white/70 mb-10 font-sans">
        {{ $profile->hero_subtitle ?? \'One Pixel At A Time\' }}
    </p>

    <!-- Get Started Button -->
    <a href="#projects"
       class="px-8 py-3 bg-transparent border border-white font-sans text-xs font-bold uppercase tracking-wider rounded-none hover:bg-white hover:text-black transition-colors duration-300 relative z-10">
        Get Started
    </a>

</div>';

    $heroHtml   = $profile->hero_html_content ?? $defaultHeroHtml;
    $blurAmount = $profile->hero_blur_amount ?? 35;
    $videoSrc   = $profile && $profile->hero_video_path
                    ? Storage::url($profile->hero_video_path)
                    : asset('videos/bg_showreel_loop.mp4');
    $videoFilename = $profile && $profile->hero_video_path
                    ? basename($profile->hero_video_path)
                    : 'bg_showreel_loop.mp4 (default)';

    $gradientEnabled = $profile->hero_gradient_enabled ?? false;
    $gradientType = $profile->hero_gradient_type ?? 'linear';
    $gradientAngle = $profile->hero_gradient_angle ?? 180;
    $gradientOpacity = $profile->hero_gradient_opacity ?? 100;
    
    $gradientStops = $profile->hero_gradient_stops ?? [
        ['position' => 0, 'color' => '#D9D9D9', 'opacity' => 100],
        ['position' => 100, 'color' => '#737373', 'opacity' => 100],
    ];
@endphp

<style>
    /* ── Grid ────────────────────── */
    .hero-admin-wrap {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media(max-width:920px){ .hero-admin-wrap { grid-template-columns: 1fr; } }

    /* ── Panel ───────────────────── */
    .h-panel {
        background: #ffffff;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        overflow: hidden;
    }
    .h-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 1rem;
        border-bottom: 2px solid #e5e7eb;
        background: #f9fafb;
    }
    .h-panel-label {
        font-family: 'Poppins', sans-serif;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #111827;
    }

    /* ── Live Preview ────────────── */
    .hero-preview-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 16/7;
        background: #111;
        overflow: hidden;
    }
    #preview-video {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: filter 0.3s ease, opacity 0.3s ease;
    }
    .hero-preview-content {
        position: absolute; inset: 0; z-index: 10;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 1.5rem; text-align: center; color: #fff;
        pointer-events: none;
        background: linear-gradient(to bottom,rgba(0,0,0,0.4) 0%,transparent 40%,transparent 70%,rgba(0,0,0,0.2) 100%);
    }
    .preview-top-text {
        font-family: 'Bitcount Single', monospace;
        font-size: clamp(7px, 1.8vw, 16px);
        text-transform: uppercase; opacity: 1;
        margin-bottom: 0;
        display: flex;
        justify-content: space-between;
        width: 100%;
        line-height: 1;
    }
    .preview-title {
        font-family: 'Jaro', sans-serif;
        font-size: clamp(30px, 7vw, 82px);
        font-weight: 400; text-transform: uppercase;
        color: #FACC15; line-height: 0.92; margin: 0;
        margin-top: -0.12em;
        -webkit-text-stroke: 1px black;
    }
    .preview-subtitle {
        font-family: 'Bitcount Single', monospace;
        font-size: clamp(5px, 1vw, 10px);
        letter-spacing: 0.4em; text-transform: uppercase;
        opacity: 0.7; margin-top: 0.5rem; margin-bottom: 0.7rem;
    }
    .preview-cta {
        display: inline-block;
        padding: 0.4em 1.2em;
        border: 1px solid rgba(255,255,255,0.7);
        font-size: clamp(4px, 0.8vw, 8px);
        letter-spacing: 0.15em; text-transform: uppercase;
        font-family: 'Bitcount Single', monospace; color: #fff; opacity: 1;
        font-weight: 700;
    }
    .preview-typography-container {
        display: inline-flex;
        flex-direction: column;
        align-items: stretch;
        margin: 0 auto;
    }

    /* Live badge */
    .live-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: #FF851B; color: #fff;
        font-family: 'Poppins', sans-serif; font-size: 0.63rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.22rem 0.7rem; border-radius: 100px;
    }
    .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #fff; animation: livepulse 1.4s infinite; }
    @keyframes livepulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.75)} }

    /* ── Media card ──────────────── */
    .media-card { display: flex; gap: 1rem; align-items: center; padding: 1rem; }
    .media-thumb {
        width: 130px; height: 80px;
        border-radius: 0.5rem; overflow: hidden; flex-shrink: 0;
        background: #000; border: 1px solid rgba(255,255,255,0.1);
    }
    .media-thumb video { width: 100%; height: 100%; object-fit: cover; }
    .media-meta { flex: 1; min-width: 0; }
    .media-meta-row { display: flex; align-items: baseline; gap: 0.4rem; margin-bottom: 0.4rem; flex-wrap: wrap; }
    .meta-key {
        font-family: 'Space Mono', monospace; font-size: 0.6rem;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: #6b7280;
        white-space: nowrap;
    }
    .meta-val {
        font-family: 'Poppins', sans-serif; font-size: 0.72rem;
        font-weight: 600; color: #111827;
        word-break: break-all;
    }
    .media-upload-zone { padding: 0 1rem 1rem; }

    /* ── Blur Slider ─────────────── */
    .blur-panel-body { padding: 0.85rem 1rem; display: flex; align-items: center; gap: 1rem; }
    .blur-slider {
        flex: 1; -webkit-appearance: none; height: 4px;
        border-radius: 2px; background: #e5e7eb; outline: none; cursor: pointer;
    }
    .blur-slider::-webkit-slider-thumb {
        -webkit-appearance: none; width: 16px; height: 16px;
        border-radius: 50%; background: #FF851B; cursor: pointer;
        border: 2px solid #fff; box-shadow: 0 0 6px rgba(255,133,27,.5);
        transition: transform .15s ease;
    }
    .blur-slider::-webkit-slider-thumb:hover { transform: scale(1.2); }
    .blur-val {
        font-family: 'Space Mono', monospace; font-size: 0.75rem;
        font-weight: 700; color: #FF851B; min-width: 42px; text-align: right;
    }

    /* ── HTML textarea ───────────── */
    .html-textarea {
        width: 100%; background: rgba(0,0,0,0.45);
        border: none; padding: 1rem;
        color: #a5f3fc;     /* cyan - code look */
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem; line-height: 1.8;
        resize: vertical; outline: none; min-height: 440px;
    }
    .html-textarea::placeholder { color: rgba(255,255,255,0.18); }

    /* ── Inputs inside panels ────── */
    .cms-label { color: #374151 !important; }
    .cms-input { color: #111827 !important; background: #ffffff !important; border: 1px solid #d1d5db !important; }

    /* Save btn */
    .save-btn {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.3rem 0.9rem;
        background: rgba(255,133,27,0.15);
        border: 1px solid rgba(255,133,27,0.45);
        border-radius: 0.5rem; color: #FF851B;
        font-family: 'Poppins', sans-serif; font-size: 0.68rem;
        font-weight: 700; cursor: pointer; text-transform: uppercase;
        letter-spacing: 0.08em; transition: all .2s ease;
    }
    .save-btn:hover { background: rgba(255,133,27,0.25); color: #fff; }
</style>

{{-- Page header --}}
<div class="mb-6">
    <h1 class="cms-page-title">Hero</h1>
    <p class="cms-page-subtitle">Live preview · Media content · HTML template · Blur settings</p>
</div>

<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
      @mousemove.window="onMouseMove" @mouseup.window="onMouseUp"
      x-data="{
          blurVal: {{ $blurAmount }},
          videoSrc: @js($videoSrc),
          fileName: @js($videoFilename),
          fileSize: '—',
          gradEnabled: @js($gradientEnabled),
          gradType: @js($gradientType),
          gradAngle: {{ $gradientAngle }},
          globalOpacity: {{ $gradientOpacity }},
          gradStops: @js($gradientStops),
          dragIndex: null,
          isDragging: false,
          isDragOver: false,
          handleDrop(e) {
              if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                  const input = document.getElementById('hero_video');
                  const dt = new DataTransfer();
                  dt.items.add(e.dataTransfer.files[0]);
                  input.files = dt.files;
                  this.updateVideo(input);
              }
          },
          startDrag(index, e) {
              this.dragIndex = index;
              this.isDragging = true;
          },
          onMouseMove(e) {
              if (!this.isDragging || this.dragIndex === null) return;
              const bar = this.$refs.gradBar;
              const rect = bar.getBoundingClientRect();
              let pos = ((e.clientX - rect.left) / rect.width) * 100;
              pos = Math.max(0, Math.min(100, pos));
              this.gradStops[this.dragIndex].position = Math.round(pos);
          },
          onMouseUp() {
              this.isDragging = false;
              this.dragIndex = null;
          },
          addStop() {
              this.gradStops.push({position: 50, color: '#ffffff', opacity: 100});
          },
          removeStop(index) {
              if (this.gradStops.length > 2) {
                  this.gradStops.splice(index, 1);
              }
          },
          updateVideo(input) {
              if (!input.files || !input.files[0]) return;
              const file = input.files[0];
              this.fileName = file.name;
              this.fileSize = (file.size / (1024*1024)).toFixed(2) + ' MB';
              const url = URL.createObjectURL(file);
              this.videoSrc = url;
              const pv = document.getElementById('preview-video');
              pv.src = url; pv.load();
              const tv = document.getElementById('media-thumb-video');
              if (tv) { tv.src = url; tv.load(); }
          },
          get blurPx()     { return Math.round(this.blurVal * 0.3); },
          get opacityVal() { return Math.max(0.2, 0.7 - (this.blurVal / 100) * 0.35); },
          get gradientStyle() {
              if (!this.gradEnabled || !this.gradStops || this.gradStops.length === 0) return 'display: none;';
              
              const gAlpha = this.globalOpacity / 100;
              let sorted = [...this.gradStops].sort((a,b) => a.position - b.position);
              const hexToRgba = (hex, alphaPercent) => {
                  let alpha = (alphaPercent / 100) * gAlpha;
                  let c; if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
                      c= hex.substring(1).split('');
                      if(c.length== 3){c= [c[0], c[0], c[1], c[1], c[2], c[2]];}
                      c= '0x'+c.join('');
                      return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alpha+')';
                  }
                  return 'rgba(0,0,0,'+alpha+')';
              };
              
              const colorStops = sorted.map(s => `${hexToRgba(s.color, s.opacity)} ${s.position}%`).join(', ');
              
              if (this.gradType === 'linear') {
                  return `background: linear-gradient(${this.gradAngle}deg, ${colorStops}); z-index:2; pointer-events:none;`;
              } else {
                  return `background: radial-gradient(circle, ${colorStops}); z-index:2; pointer-events:none;`;
              }
          }
      }">
    @csrf
    <input type="hidden" name="hero_gradient_stops" :value="JSON.stringify(gradStops)">

    <div class="hero-admin-wrap">

        {{-- ══ LEFT COLUMN ══════════════════════════════ --}}
        <div class="flex flex-col gap-4">

            {{-- LIVE PREVIEW --}}
            <div class="h-panel">
                <div class="h-panel-header">
                    <span class="live-badge"><span class="live-dot"></span>Live Preview</span>
                    <span class="h-panel-label">Hero Section</span>
                </div>
                <div class="hero-preview-wrap">
                    <video id="preview-video" autoplay loop muted playsinline
                           :style="`filter:blur(${blurPx}px);opacity:${opacityVal}`"
                           style="filter:blur({{ round($blurAmount*0.3) }}px);opacity:{{ number_format(max(0.2,0.7-($blurAmount/100)*0.35),2) }}">
                        <source src="{{ $videoSrc }}" type="video/mp4">
                    </video>
                    <!-- Dynamic Overlay -->
                    <div class="absolute inset-0" :style="gradientStyle"></div>
                    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.5) 0%,transparent 35%);z-index:3;pointer-events:none;"></div>
                    <div class="hero-preview-content" style="z-index:10;">
                        <div class="preview-typography-container">
                            <div class="preview-top-text">
                                <span>TURNING</span>
                                <span>IDEAS</span>
                                <span>INTO</span>
                            </div>
                            <h1 class="preview-title">REALITY</h1>
                        </div>
                        <p class="preview-subtitle">One Pixel At A Time</p>
                        <span class="preview-cta">Get Started</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- MEDIA CONTENT --}}
                <div class="h-panel h-full flex flex-col relative"
                     @dragover.prevent="isDragOver = true"
                     @dragleave.prevent="isDragOver = false"
                     @drop.prevent="isDragOver = false; handleDrop($event)">
                     
                    <!-- Drop Zone Overlay (The 'Left' state) -->
                    <div x-show="isDragOver"
                         style="display: none;"
                         class="absolute inset-0 z-50 rounded-2xl flex flex-col items-center justify-center transition-all duration-200 bg-[#8f8f8f]">
                        <svg class="w-16 h-16 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-white font-bold text-[22px] tracking-wide">Drop your file here.</span>
                    </div>

                    <div class="h-panel-header border-b border-gray-100">
                        <span class="h-panel-label text-gray-800">Media Content</span>
                        <span style="font-family:'Space Mono',monospace;font-size:0.58rem;color:#9ca3af;letter-spacing:0.12em;">VIDEO LOOP</span>
                    </div>

                    <div class="flex flex-col md:flex-row gap-5 p-5 flex-grow items-stretch">
                        <!-- Left: Video Preview -->
                        <div class="w-full md:w-[60%] aspect-square bg-[#000000] rounded-xl overflow-hidden relative border border-gray-400 shadow-sm">
                             <video id="media-thumb-video" class="absolute inset-0 w-full h-full object-contain" autoplay loop muted playsinline>
                                 <source src="{{ $videoSrc }}" type="video/mp4">
                             </video>
                             <!-- Overlay Controls -->
                             <div class="absolute top-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                                 <button type="button" class="bg-black/60 hover:bg-black/80 text-white rounded p-1.5 transition-colors backdrop-blur-sm">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                 </button>
                                 <button type="button" class="bg-black/60 hover:bg-black/80 text-white rounded p-1.5 transition-colors backdrop-blur-sm">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 </button>
                             </div>
                        </div>

                        <!-- Right: Meta Data & Button -->
                        <div class="w-full md:w-[40%] flex flex-col justify-between pt-1">
                            <div class="flex flex-col gap-3.5">
                                <div>
                                    <div class="text-[10px] font-extrabold text-black uppercase mb-1 tracking-wider">File Name:</div>
                                    <div class="text-xs text-gray-800 pb-1 border-b border-gray-200 truncate font-medium uppercase" x-text="fileName">{{ $videoFilename }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-extrabold text-black uppercase mb-1 tracking-wider">File Size:</div>
                                    <div class="text-xs text-gray-800 pb-1 border-b border-gray-200 font-medium">
                                        <span x-text="fileSize !== '—' ? fileSize : '{{ ($profile && $profile->hero_video_path && Storage::exists($profile->hero_video_path)) ? round(Storage::size($profile->hero_video_path)/(1024*1024),2).' MB' : 'Default' }}'">
                                            @if($profile && $profile->hero_video_path)
                                                @php $vpath = $profile->hero_video_path; @endphp
                                                {{ Storage::exists($vpath) ? round(Storage::size($vpath)/(1024*1024),2).' MB' : '—' }}
                                            @else
                                                Default
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-extrabold text-black uppercase mb-1 tracking-wider">Type:</div>
                                    <div class="text-xs text-gray-500 pb-1 border-b border-gray-200 font-medium">
                                        MP4 Loop<br>
                                        <span class="text-gray-400 font-normal">Frame 51</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col items-center">
                                <button type="button" onclick="document.getElementById('hero_video').click()" class="w-full bg-[#ff6b00] hover:bg-[#e56000] text-white font-bold py-2 px-4 rounded-sm text-xs transition-colors">
                                    Change File
                                </button>
                                <input type="file" name="hero_video" id="hero_video" class="hidden" accept="video/mp4,video/mov,video/webm" @change="updateVideo($event.target)">
                                <span class="text-[8px] text-gray-400 mt-2 text-center leading-tight">Choose from File Explorer or Drag a file here.</span>
                                @error('hero_video')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EFFECTS --}}
                <div class="h-panel h-full flex flex-col">
                    <div class="h-panel-header">
                        <span class="h-panel-label">Effects</span>
                    </div>
                    <div class="p-5 flex flex-col gap-6">
                        
                        <!-- Blur -->
                        <div>
                            <label class="cms-label mb-2 flex justify-between">
                                <span>Background Blur</span>
                                <span class="text-orange-500 font-bold" x-text="blurVal + '%'"></span>
                            </label>
                            <input type="range" name="hero_blur_amount" min="0" max="100" x-model="blurVal" class="blur-slider w-full">
                        </div>

                        <hr class="border-gray-200">

                        <!-- Gradient Toggle -->
                        <div class="flex items-center justify-between">
                            <label class="cms-label mb-0 cursor-pointer" for="hero_gradient_enabled">Gradient Overlay</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="hero_gradient_enabled" id="hero_gradient_enabled" class="sr-only peer" x-model="gradEnabled">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                            </label>
                        </div>

                        <!-- Gradient Controls (Muted if disabled) -->
                        <div class="flex flex-col gap-4 transition-opacity duration-300" :class="gradEnabled ? 'opacity-100' : 'opacity-40 pointer-events-none'">
                            
                            <!-- Type and Angle -->
                            <div class="flex items-center gap-6">
                                <div class="flex gap-4">
                                    <label class="cms-label flex items-center gap-2 cursor-pointer" style="text-transform: none;">
                                        <input type="radio" name="hero_gradient_type" value="linear" x-model="gradType" class="accent-orange-500"> Linear
                                    </label>
                                    <label class="cms-label flex items-center gap-2 cursor-pointer" style="text-transform: none;">
                                        <input type="radio" name="hero_gradient_type" value="radial" x-model="gradType" class="accent-orange-500"> Radial
                                    </label>
                                </div>
                                <div class="flex-grow flex items-center gap-2" x-show="gradType === 'linear'" x-transition>
                                    <span class="text-[10px] text-gray-500 font-mono" x-text="gradAngle + '°'"></span>
                                    <input type="range" name="hero_gradient_angle" min="0" max="360" x-model="gradAngle" class="blur-slider w-full h-1">
                                </div>
                            </div>
                            
                            <!-- Global Opacity -->
                            <div class="flex items-center gap-2 transition-opacity duration-300">
                                <label class="cms-label mb-0 flex-shrink-0 text-[10px]">Global Opacity</label>
                                <input type="range" name="hero_gradient_opacity" min="0" max="100" x-model="globalOpacity" class="blur-slider flex-grow h-1">
                                <span class="text-[10px] text-gray-500 font-mono w-8 text-right" x-text="globalOpacity + '%'"></span>
                            </div>

                            <!-- Gradient Builder Visual Bar -->
                            <div class="mt-6 mb-2 relative w-full h-8 rounded border border-gray-300 shadow-inner select-none"
                                 x-ref="gradBar"
                                 :style="gradientStyle.replace('z-index:2; pointer-events:none;', '')">
                                 
                                 <!-- Draggable Pins -->
                                 <template x-for="(stop, index) in gradStops" :key="index">
                                     <div class="absolute top-0 w-4 h-6 -mt-6 -ml-2 cursor-grab active:cursor-grabbing flex flex-col items-center justify-start"
                                          :style="`left: ${stop.position}%; z-index: 20;`"
                                          @mousedown="startDrag(index, $event)">
                                          <!-- Pin Head -->
                                          <div class="w-4 h-4 rounded border-2 border-white shadow bg-white overflow-hidden flex-shrink-0">
                                              <div class="w-full h-full" :style="`background-color: ${stop.color}; opacity: ${stop.opacity/100};`"></div>
                                          </div>
                                          <!-- Pin stem -->
                                          <div class="w-0.5 h-2 bg-white shadow flex-shrink-0"></div>
                                     </div>
                                 </template>
                            </div>

                            <!-- Stops List -->
                            <div class="flex flex-col gap-2 mt-1 bg-gray-50 p-3 rounded border border-gray-200 shadow-sm max-h-[220px] overflow-y-auto overflow-x-hidden pr-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="cms-label text-[10px] font-bold tracking-wider">STOPS</span>
                                    <button type="button" @click="addStop()" class="text-gray-400 hover:text-black hover:bg-gray-200 rounded p-0.5 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                
                                <template x-for="(stop, index) in gradStops" :key="index">
                                    <div class="flex items-center gap-2 mb-1">
                                        <!-- Position -->
                                        <div class="w-16">
                                            <div class="relative w-full">
                                                <input type="number" x-model.number="stop.position" min="0" max="100" class="w-full text-xs py-1 px-2 border border-gray-300 rounded bg-white text-gray-700 outline-none focus:border-orange-500">
                                                <span class="absolute right-2 top-1 text-[10px] text-gray-400 pointer-events-none">%</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Color -->
                                        <div class="flex-grow flex items-center gap-2 bg-white border border-gray-300 rounded p-1 h-7">
                                            <input type="color" x-model="stop.color" class="w-5 h-5 rounded cursor-pointer border-0 p-0 block bg-transparent" style="padding:0; min-width:1.25rem;">
                                            <span class="text-[10px] font-mono text-gray-600 truncate" x-text="stop.color.toUpperCase()"></span>
                                        </div>

                                        <!-- Opacity -->
                                        <div class="w-16">
                                            <div class="relative w-full">
                                                <input type="number" x-model.number="stop.opacity" min="0" max="100" class="w-full text-xs py-1 px-2 border border-gray-300 rounded bg-white text-gray-700 outline-none focus:border-orange-500">
                                                <span class="absolute right-2 top-1 text-[10px] text-gray-400 pointer-events-none">%</span>
                                            </div>
                                        </div>

                                        <!-- Remove -->
                                        <button type="button" @click="removeStop(index)" class="text-gray-400 hover:text-red-500 p-1 flex-shrink-0" :disabled="gradStops.length <= 2" :class="gradStops.length <= 2 ? 'opacity-30 cursor-not-allowed' : ''">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


        </div>

    </div>

    {{-- Save all --}}
    <div style="display:flex;justify-content:flex-end;padding-top:1.5rem;padding-bottom:1rem;">
        <button type="submit" class="cms-btn-primary" style="padding:0.75rem 2.5rem;font-size:0.9rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save All Changes
        </button>
    </div>

</form>

@endsection
