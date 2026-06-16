@extends('admin.layout')

@section('admin_content')

<style>
    .cms-main { background: #EDEAE0; display: flex; flex-direction: column; }
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
        display: flex; flex-direction: column;
        flex: 1; min-height: calc(100vh - 160px); /* Maximize viewport */
    }
    .lt-card-header {
        padding:0.85rem 1.25rem; border-bottom:1px solid #E2DDD3;
        background:#F7F5EE;
        display:flex; align-items:center; justify-content:space-between; gap:0.6rem;
    }
    .lt-card-title {
        font-family:'Outfit',sans-serif; font-size:0.875rem;
        font-weight:700; color:#1a1207;
        display:flex; align-items:center; gap:0.4rem;
    }
    .lt-count-badge {
        padding:0.15rem 0.55rem; border-radius:100px;
        font-family:'Space Mono',monospace; font-size:0.58rem;
        font-weight:700; background:#EEE6FF; color:#6829AA;
        border:1px solid #D8C0F8; margin-left: 0.5rem;
    }

    /* skill card */
    .sk-card {
        padding:0.8rem 0.9rem;
        background:#F7F5EE;
        border:1px solid #E2DDD3;
        border-radius:0.65rem;
        display:flex; align-items:center; justify-content:space-between; gap:0.75rem;
        transition:border-color 0.15s,box-shadow 0.15s;
    }
    .sk-card:hover { border-color:#C4BDB2; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
    .sk-name { font-size:0.82rem; font-weight:600; color:#1a1207; }
    
    /* category heading */
    .sk-cat-label {
        font-family:'Space Mono',monospace; font-size:0.6rem;
        text-transform:uppercase; letter-spacing:0.12em;
        color:#9B9589; margin-bottom:0.6rem;
    }

    /* Modals */
    .lt-modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5);
        backdrop-filter: blur(3px); z-index: 100;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .lt-modal-content {
        background: #fff; border-radius: 1rem; width: 100%; max-width: 420px;
        padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        position: relative; max-height: 90vh; overflow-y: auto;
    }
    .lt-modal-close {
        position: absolute; top: 1.25rem; right: 1.25rem;
        background: #F7F5EE; border: none; border-radius: 50%;
        width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #5A5248; transition: all 0.15s;
    }
    .lt-modal-close:hover { background: #E2DDD3; color: #1a1207; }

    /* Tool Tabs */
    .tool-tab-btn {
        padding: 0.5rem 1rem; border-radius: 0.5rem;
        font-family: 'Space Mono', monospace; font-size: 0.65rem;
        text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;
        transition: all 0.2s; border: 1px solid transparent;
        background: #F7F5EE; color: #9B9589;
    }
    .tool-tab-btn.active {
        background: #FF851B; color: #fff; border-color: #E6720D;
        box-shadow: 0 2px 5px rgba(255,133,27,0.2);
    }
    .tool-tab-btn:not(.active):hover {
        background: #E2DDD3; color: #1a1207;
    }
</style>

<div x-data="{ tab: sessionStorage.getItem('admin_skills_tab') || 'skills' }" x-init="$watch('tab', val => sessionStorage.setItem('admin_skills_tab', val))" style="display:flex; flex-direction:column; flex:1;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem; flex-shrink:0;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Skills &amp; Tools</h1>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Manage coding technologies and marquee tools</p>
        </div>
        
        <!-- TABS NAV -->
        <div class="flex items-center gap-2 bg-[#F7F5EE] border border-[#D8D4C8] p-1 rounded-lg">
            <button @click="tab = 'skills'" :class="tab === 'skills' ? 'bg-[#6829AA] text-white shadow-md' : 'text-[#5A5248] hover:bg-[#E2DDD3]'" class="px-4 py-1.5 rounded text-xs font-bold font-mono uppercase tracking-wider transition-all">Technical Skills</button>
            <button @click="tab = 'tools'" :class="tab === 'tools' ? 'bg-[#FF851B] text-white shadow-md' : 'text-[#5A5248] hover:bg-[#E2DDD3]'" class="px-4 py-1.5 rounded text-xs font-bold font-mono uppercase tracking-wider transition-all">Marquee Tools</button>
        </div>
    </div>

    <!-- SKILLS TAB -->
    <div x-show="tab === 'skills'" x-cloak x-data="skillCropperData()" style="display:flex; flex-direction:column; flex:1;">
        <div class="lt-card">
            <div class="lt-card-header">
                <div class="lt-card-title">
                    <span style="width:8px;height:8px;border-radius:50%;background:#4dd9f0;display:inline-block;"></span>
                    Active Technical Grid
                    <span class="lt-count-badge">{{ $skills->count() }}</span>
                </div>
                <button type="button" @click="openAddSkill()" class="lt-btn-primary">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Skill
                </button>
            </div>

            <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1.5rem; overflow-y:auto; flex:1;">
                @php $groupedSkills = $skills->groupBy('category'); @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 lg:divide-x lg:divide-[#D8D4C8] gap-y-8 flex-1 min-h-full">
                    <!-- Core Column -->
                    <div class="lg:pr-8">
                        <p class="sk-cat-label mb-4 text-center">Core</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($skills->where('category', 'Core') as $skill)
                                <div class="p-4 bg-[#111111] border border-[#2A2A2A] rounded-xl flex flex-col items-center justify-center gap-3 hover:border-[#FF851B] hover:shadow-[0_0_15px_rgba(255,133,27,0.15)] transition-all relative group h-32">
                                    @if($skill->image_path)
                                        <div class="w-full h-12 flex items-center justify-center px-2">
                                            <img src="{{ Storage::url($skill->image_path) }}" alt="{{ $skill->name }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg border border-[#333] bg-[#1A1A1A] flex items-center justify-center text-[#666] text-sm font-bold uppercase">
                                            {{ substr($skill->name, 0, 2) }}
                                        </div>
                                    @endif
                                    
                                    <span class="text-xs font-semibold text-[#EEEEEE] text-center truncate w-full">{{ $skill->name }}</span>
                                    
                                    <div class="absolute top-1.5 right-1.5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditSkill({ id: {{ $skill->id }}, name: '{{ addslashes($skill->name) }}', category: '{{ addslashes($skill->category) }}', tooltip_info: '{{ addslashes($skill->tooltip_info) }}', proficiency: {{ $skill->proficiency ?? 5 }}, image_path: '{{ $skill->image_path }}' })" class="text-[#888] hover:text-[#4dd9f0] p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Edit Skill">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.skills.delete', $skill->id) }}" method="POST" onsubmit="return confirm('Remove {{ $skill->name }}?');">
                                            @csrf
                                            <button type="submit" class="text-[#888] hover:text-rose-500 p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Delete Skill">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($skills->where('category', 'Core')->isEmpty())
                            <div style="padding:2rem;text-align:center;">
                                <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No Core skills added yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- External Column -->
                    <div class="lg:pl-8">
                        <p class="sk-cat-label mb-4 text-center">External</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($skills->where('category', 'External') as $skill)
                                <div class="p-4 bg-[#111111] border border-[#2A2A2A] rounded-xl flex flex-col items-center justify-center gap-3 hover:border-[#FF851B] hover:shadow-[0_0_15px_rgba(255,133,27,0.15)] transition-all relative group h-32">
                                    @if($skill->image_path)
                                        <div class="w-full h-12 flex items-center justify-center px-2">
                                            <img src="{{ Storage::url($skill->image_path) }}" alt="{{ $skill->name }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg border border-[#333] bg-[#1A1A1A] flex items-center justify-center text-[#666] text-sm font-bold uppercase">
                                            {{ substr($skill->name, 0, 2) }}
                                        </div>
                                    @endif
                                    
                                    <span class="text-xs font-semibold text-[#EEEEEE] text-center truncate w-full">{{ $skill->name }}</span>
                                    
                                    <div class="absolute top-1.5 right-1.5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditSkill({ id: {{ $skill->id }}, name: '{{ addslashes($skill->name) }}', category: '{{ addslashes($skill->category) }}', tooltip_info: '{{ addslashes($skill->tooltip_info) }}', proficiency: {{ $skill->proficiency ?? 5 }}, image_path: '{{ $skill->image_path }}' })" class="text-[#888] hover:text-[#4dd9f0] p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Edit Skill">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.skills.delete', $skill->id) }}" method="POST" onsubmit="return confirm('Remove {{ $skill->name }}?');">
                                            @csrf
                                            <button type="submit" class="text-[#888] hover:text-rose-500 p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Delete Skill">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($skills->where('category', 'External')->isEmpty())
                            <div style="padding:2rem;text-align:center;">
                                <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No External skills added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add / Edit Skill Modal -->
        <div x-show="isSkillModalOpen" x-cloak class="lt-modal-overlay">
            <div class="lt-modal-content" @click.outside="isSkillModalOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <button type="button" @click="isSkillModalOpen = false" class="lt-modal-close">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1207;margin-bottom:1.25rem;" x-text="skillFormMode === 'add' ? 'Add Technical Skill' : 'Edit Technical Skill'"></h3>
                <form :action="getSkillAction()" method="POST" class="space-y-4" @submit="onSkillSubmit">
                    @csrf
                    <input type="hidden" name="image_data" id="skill_image_data" x-model="skillCroppedData">

                    <div>
                        <label class="lt-label">Skill Name</label>
                        <input type="text" name="name" x-model="skillFormData.name" required placeholder="e.g. Laravel, React, Docker" class="lt-input">
                        @error('name')<p class="lt-err">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="lt-label">Tooltip / Description</label>
                        <input type="text" name="tooltip_info" x-model="skillFormData.tooltip_info" placeholder="Brief description of your expertise" class="lt-input">
                        @error('tooltip_info')<p class="lt-err">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="lt-label">Category</label>
                        <select name="category" x-model="skillFormData.category" required class="lt-input">
                            <option value="Core">Core</option>
                            <option value="External">External</option>
                        </select>
                        @error('category')<p class="lt-err">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="lt-label">Proficiency Rating (1-5)</label>
                        <select name="proficiency" x-model="skillFormData.proficiency" required class="lt-input">
                            <option value="5">5 - Expert / Master</option>
                            <option value="4">4 - Advanced</option>
                            <option value="3">3 - Intermediate</option>
                            <option value="2">2 - Beginner</option>
                            <option value="1">1 - Novice</option>
                        </select>
                        @error('proficiency')<p class="lt-err">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="lt-label">Skill Icon (Optional, 1:1 ratio)</label>
                        
                        <!-- Shows if no new image selected AND no old image exists -->
                        <div x-show="!skillImageSelected && !skillFormData.old_image" class="relative w-full h-32 border-2 border-dashed border-[#D8D4C8] hover:border-[#FF851B] rounded-xl flex flex-col items-center justify-center cursor-pointer transition-colors bg-[#F7F5EE] overflow-hidden group">
                            <input type="file" @change="skillFileSelected" accept="image/png, image/jpeg, image/svg+xml" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <svg class="w-8 h-8 text-[#9B9589] group-hover:text-[#FF851B] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-[#9B9589] font-semibold group-hover:text-[#FF851B] transition-colors">Click or drag image</span>
                        </div>

                        <!-- Shows if an old image exists and no new image is being cropped -->
                        <div x-show="!skillImageSelected && skillFormData.old_image" class="relative w-full h-32 border border-[#D8D4C8] rounded-xl flex items-center justify-center bg-[#111111] overflow-hidden group">
                            <input type="file" @change="skillFileSelected" accept="image/png, image/jpeg, image/svg+xml" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Click to replace image">
                            <img :src="skillFormData.old_image" class="h-20 w-auto object-contain">
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Replace Image</span>
                            </div>
                        </div>

                        <!-- Shows when actively cropping a new image -->
                        <div x-show="skillImageSelected" style="display: none;" class="space-y-3 mt-2">
                            <div class="w-full bg-[#F7F5EE] rounded-xl overflow-hidden border border-[#D8D4C8]" style="max-height: 250px;">
                                <img id="skill-cropper-image" src="" class="max-w-full hidden">
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="resetSkillImage" class="lt-btn-secondary w-full justify-center">Cancel Cropping</button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:0.5rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <button type="button" @click="isSkillModalOpen = false" class="lt-btn-secondary">Cancel</button>
                        <button type="submit" class="lt-btn-primary" x-text="skillFormMode === 'add' ? 'Add Skill' : 'Save Changes'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TOOLS TAB -->
    <div x-show="tab === 'tools'" x-cloak x-data="toolCropperData()" style="display:flex; flex-direction:column; flex:1;">
        
        <div class="lt-card">
            <div class="lt-card-header flex-wrap gap-4">
                <div class="lt-card-title">
                    <span style="width:8px;height:8px;border-radius:50%;background:#FF851B;display:inline-block;"></span>
                    Marquee Display Config
                    <span class="lt-count-badge" style="background:#FFF1E5; color:#FF851B; border-color:#FFD2AD;">{{ $toolItems->count() }}</span>
                </div>

                <div class="flex items-center gap-4 flex-wrap">
                    <!-- Switchable Category Tabs & Globe Settings Icon -->
                    <div class="flex gap-2 items-center">
                        @foreach($rowLabels as $label)
                            <button @click="toolTab = '{{ $label }}'" 
                                    :class="toolTab === '{{ $label }}' ? 'active' : ''"
                                    class="tool-tab-btn">
                                {{ $label }}
                            </button>
                        @endforeach
                        
                        <!-- Settings / Rename Globe Icon -->
                        <button type="button" @click="openRenameModal()" class="text-[#888] hover:text-[#4dd9f0] p-1.5 bg-[#F7F5EE] border border-[#D8D4C8] rounded-md shadow-sm transition-colors ml-2" title="Rename Marquee Category">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </div>

                    <!-- Add Button -->
                    <button @click="openAddModal()" class="lt-btn-primary" style="background:#FF851B;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add Tool
                    </button>
                </div>
            </div>

            <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                @forelse($groupedTools as $cat => $list)
                    <div x-show="toolTab === '{{ $cat }}'" x-cloak>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                            @foreach($list as $tool)
                                <!-- Dark background for tools -->
                                <div class="p-4 bg-[#111111] border border-[#2A2A2A] rounded-xl flex flex-col items-center justify-center gap-3 hover:border-[#FF851B] hover:shadow-[0_0_15px_rgba(255,133,27,0.15)] transition-all relative group h-32">
                                    
                                    @if($tool->image_path)
                                        <div class="w-full h-12 flex items-center justify-center px-2">
                                            <img src="{{ Storage::url($tool->image_path) }}" alt="{{ $tool->name }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg border border-[#333] bg-[#1A1A1A] flex items-center justify-center text-[#666] text-sm font-bold uppercase">
                                            {{ substr($tool->name, 0, 2) }}
                                        </div>
                                    @endif
                                    
                                    <span class="text-xs font-semibold text-[#EEEEEE] text-center truncate w-full">{{ $tool->name }}</span>
                                    
                                    <div class="absolute top-1.5 right-1.5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <!-- Edit Button -->
                                        <button type="button" @click="openEditModal({ id: {{ $tool->id }}, name: '{{ addslashes($tool->name) }}', tooltip_info: '{{ addslashes($tool->tooltip_info) }}', row_label: '{{ addslashes($tool->row_label) }}', proficiency: {{ $tool->proficiency ?? 5 }}, image_path: '{{ $tool->image_path }}' })" class="text-[#888] hover:text-[#4dd9f0] p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Edit Tool">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.tools.delete', $tool->id) }}" method="POST" onsubmit="return confirm('Remove {{ $tool->name }}?');">
                                            @csrf
                                            <button type="submit" class="text-[#888] hover:text-rose-500 p-1.5 bg-[#222] rounded-md shadow-sm border border-[#333] transition-colors" title="Delete Tool">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="padding:4rem;text-align:center;">
                        <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No tools added yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Add / Edit Tool Modal -->
        <div x-show="isModalOpen" x-cloak class="lt-modal-overlay">
            <div class="lt-modal-content" @click.outside="isModalOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <button type="button" @click="isModalOpen = false" class="lt-modal-close">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1207;margin-bottom:1.25rem;" x-text="formMode === 'add' ? 'Add Tool/Software' : 'Edit Tool/Software'"></h3>
                
                <form :action="getFormAction()" method="POST" class="space-y-4" @submit="onSubmit">
                    @csrf
                    <input type="hidden" name="image_data" id="image_data" x-model="croppedData">

                    <div>
                        <label for="name" class="lt-label">Tool Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. PHP, Figma" class="lt-input" x-model="formData.name">
                    </div>

                    <div>
                        <label for="tooltip_info" class="lt-label">Tooltip / Purpose (Optional)</label>
                        <input type="text" name="tooltip_info" id="tooltip_info" placeholder="e.g. Backend Framework, UI Design" class="lt-input" x-model="formData.tooltip_info">
                    </div>

                    <div>
                        <label for="row_label" class="lt-label">Marquee Category</label>
                        <input type="text" name="row_label" id="row_label" required list="row_categories" placeholder="e.g. Programming Languages" class="lt-input" x-model="formData.row_label">
                        
                        <datalist id="row_categories">
                            @foreach($rowLabels as $label)
                                <option value="{{ $label }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label for="proficiency" class="lt-label">Proficiency Rating (1-5)</label>
                        <select name="proficiency" id="proficiency" required class="lt-input" x-model="formData.proficiency">
                            <option value="5">5 - Expert / Master</option>
                            <option value="4">4 - Advanced</option>
                            <option value="3">3 - Intermediate</option>
                            <option value="2">2 - Beginner</option>
                            <option value="1">1 - Novice</option>
                        </select>
                    </div>

                    <div>
                        <label class="lt-label">Tool Icon (Optional PNG)</label>
                        
                        <!-- Shows if no new image selected AND no old image exists -->
                        <div x-show="!imageSelected && !formData.old_image" class="relative w-full h-32 border-2 border-dashed border-[#D8D4C8] hover:border-[#FF851B] rounded-xl flex flex-col items-center justify-center cursor-pointer transition-colors bg-[#F7F5EE] overflow-hidden group">
                            <input type="file" @change="fileSelected" accept="image/png, image/jpeg, image/svg+xml" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <svg class="w-8 h-8 text-[#9B9589] group-hover:text-[#FF851B] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-[#9B9589] font-semibold group-hover:text-[#FF851B] transition-colors">Click or drag image</span>
                        </div>

                        <!-- Shows if an old image exists and no new image is being cropped -->
                        <div x-show="!imageSelected && formData.old_image" class="relative w-full h-32 border border-[#D8D4C8] rounded-xl flex items-center justify-center bg-[#111111] overflow-hidden group">
                            <input type="file" @change="fileSelected" accept="image/png, image/jpeg, image/svg+xml" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Click to replace image">
                            <img :src="formData.old_image" class="h-20 w-auto object-contain">
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Replace Image</span>
                            </div>
                        </div>

                        <!-- Shows when actively cropping a new image -->
                        <div x-show="imageSelected" style="display: none;" class="space-y-3 mt-2">
                            <div class="w-full bg-[#F7F5EE] rounded-xl overflow-hidden border border-[#D8D4C8]" style="max-height: 250px;">
                                <img id="cropper-image" src="" class="max-w-full hidden">
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="resetImage" class="lt-btn-secondary w-full justify-center">Cancel Cropping</button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:0.5rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <button type="button" @click="isModalOpen = false" class="lt-btn-secondary">Cancel</button>
                        <button type="submit" class="lt-btn-primary" style="background:#FF851B;" x-text="formMode === 'add' ? 'Add Tool' : 'Save Changes'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rename Category Modal -->
        <div x-show="isRenameModalOpen" x-cloak class="lt-modal-overlay">
            <div class="lt-modal-content" @click.outside="isRenameModalOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0" style="max-width: 400px;">
                <button type="button" @click="isRenameModalOpen = false" class="lt-modal-close">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1207;margin-bottom:1.25rem;">Rename Category</h3>
                
                <form action="{{ route('admin.tools.rename_row') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="old_label" :value="renameFormData.old_label">
                    
                    <div>
                        <label for="new_label" class="lt-label">New Category Name</label>
                        <input type="text" name="new_label" id="new_label" required class="lt-input" x-model="renameFormData.new_label" autofocus>
                    </div>

                    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <button type="button" @click="isRenameModalOpen = false" class="lt-btn-secondary">Cancel</button>
                        <button type="submit" class="lt-btn-primary" style="background:#FF851B;">Rename</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include CropperJS CSS/JS from CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    function toolCropperData() {
        return {
            toolTab: sessionStorage.getItem('admin_tool_tab') || '{{ $rowLabels->first() ?? '' }}',
            init() {
                this.$watch('toolTab', val => sessionStorage.setItem('admin_tool_tab', val));
            },
            isModalOpen: false,
            formMode: 'add',
            formData: {
                id: null,
                name: '',
                tooltip_info: '',
                row_label: '',
                proficiency: 5,
                old_image: ''
            },
            isRenameModalOpen: false,
            renameFormData: { old_label: '', new_label: '' },
            imageSelected: false,
            cropper: null,
            croppedData: '',
            
            openAddModal() {
                this.formMode = 'add';
                this.formData = { id: null, name: '', tooltip_info: '', row_label: '', proficiency: 5, old_image: '' };
                this.resetImage();
                this.isModalOpen = true;
            },

            openRenameModal() {
                this.renameFormData.old_label = this.toolTab;
                this.renameFormData.new_label = this.toolTab;
                this.isRenameModalOpen = true;
            },

            openEditModal(tool) {
                this.formMode = 'edit';
                this.formData = { 
                    id: tool.id, 
                    name: tool.name, 
                    tooltip_info: tool.tooltip_info || '', 
                    row_label: tool.row_label,
                    proficiency: tool.proficiency || 5,
                    old_image: tool.image_path ? '/storage/' + tool.image_path : ''
                };
                this.resetImage();
                this.isModalOpen = true;
            },

            getFormAction() {
                return this.formMode === 'edit' 
                    ? `{{ route('admin.tools.update', ':id') }}`.replace(':id', this.formData.id) 
                    : `{{ route('admin.tools.store') }}`;
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
                            aspectRatio: NaN, // Free aspect ratio to support wide/landscape logos
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
                if (this.cropper && this.imageSelected) {
                    const canvas = this.cropper.getCroppedCanvas({ maxHeight: 300, maxWidth: 600 });
                    this.croppedData = canvas.toDataURL('image/png');
                } else {
                    this.croppedData = ''; // Ensure no empty crop string is sent
                }
            }
        }
    }

    function skillCropperData() {
        return {
            isSkillModalOpen: false,
            skillFormMode: 'add',
            skillFormData: {
                id: null,
                name: '',
                category: 'Core',
                tooltip_info: '',
                proficiency: 5,
                old_image: ''
            },
            skillImageSelected: false,
            skillCropper: null,
            skillCroppedData: '',
            
            openAddSkill() {
                this.skillFormMode = 'add';
                this.skillFormData = { id: null, name: '', category: 'Core', tooltip_info: '', proficiency: 5, old_image: '' };
                this.resetSkillImage();
                this.isSkillModalOpen = true;
            },

            openEditSkill(skill) {
                this.skillFormMode = 'edit';
                this.skillFormData = { 
                    id: skill.id, 
                    name: skill.name, 
                    category: skill.category,
                    tooltip_info: skill.tooltip_info || '', 
                    proficiency: skill.proficiency || 5,
                    old_image: skill.image_path ? '/storage/' + skill.image_path : ''
                };
                this.resetSkillImage();
                this.isSkillModalOpen = true;
            },

            getSkillAction() {
                return this.skillFormMode === 'edit' 
                    ? `{{ route('admin.skills.update', ':id') }}`.replace(':id', this.skillFormData.id) 
                    : `{{ route('admin.skills.store') }}`;
            },

            skillFileSelected(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = document.getElementById('skill-cropper-image');
                    img.src = event.target.result;
                    img.classList.remove('hidden');
                    this.skillImageSelected = true;

                    setTimeout(() => {
                        if (this.skillCropper) {
                            this.skillCropper.destroy();
                        }
                        this.skillCropper = new Cropper(img, {
                            aspectRatio: 1, // Strictly 1:1 ratio
                            viewMode: 1,
                            autoCropArea: 1,
                            background: false,
                        });
                    }, 100);
                };
                reader.readAsDataURL(file);
            },

            resetSkillImage() {
                this.skillImageSelected = false;
                this.skillCroppedData = '';
                if (this.skillCropper) {
                    this.skillCropper.destroy();
                    this.skillCropper = null;
                }
                const img = document.getElementById('skill-cropper-image');
                if(img) {
                    img.src = '';
                    img.classList.add('hidden');
                }
            },

            onSkillSubmit(e) {
                if (this.skillCropper && this.skillImageSelected) {
                    const canvas = this.skillCropper.getCroppedCanvas({ maxHeight: 300, maxWidth: 300 });
                    this.skillCroppedData = canvas.toDataURL('image/png');
                } else {
                    this.skillCroppedData = ''; // Ensure no empty crop string is sent
                }
            }
        }
    }
</script>

@endsection
