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
    .sk-pct  { font-family:'Space Mono',monospace; font-size:0.68rem; color:#6829AA; font-weight:700; }
    .sk-bar-track { width:100%; height:4px; background:#E2DDD3; border-radius:4px; margin-top:0.35rem; overflow:hidden; }
    .sk-bar-fill  { height:4px; border-radius:4px; background:linear-gradient(90deg,#6829AA,#4dd9f0); }
    .sk-delete-btn {
        background:transparent; border:none; cursor:pointer;
        color:#C4BDB2; padding:0.3rem; border-radius:0.35rem;
        transition:all .15s; flex-shrink:0;
    }
    .sk-delete-btn:hover { color:#dc2626; background:#FFF1F1; }

    /* category heading */
    .sk-cat-label {
        font-family:'Space Mono',monospace; font-size:0.6rem;
        text-transform:uppercase; letter-spacing:0.12em;
        color:#9B9589; margin-bottom:0.6rem;
        padding-bottom:0.4rem; border-bottom:1px solid #E2DDD3;
    }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem;flex-wrap:wrap;gap:0.75rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Skills &amp; Tools</h1>
        <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Coding technologies and expertise indices</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:340px 1fr;gap:1.25rem;align-items:start;">

    {{-- ADD FORM --}}
    <div class="lt-form-card">
        <p style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:#1a1207;margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;">
            <span style="width:8px;height:8px;border-radius:50%;background:#6829AA;display:inline-block;"></span>
            Add Technical Skill
        </p>
        <form action="{{ route('admin.skills.store') }}" method="POST" style="display:flex;flex-direction:column;gap:0.9rem;">
            @csrf
            <div>
                <label class="lt-label">Skill Name</label>
                <input type="text" name="name" id="name" required
                       placeholder="e.g. Laravel, React, Docker" class="lt-input">
                @error('name')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lt-label">Category</label>
                <select name="category" id="category" required class="lt-input">
                    <option value="Core">Core</option>
                    <option value="External">External</option>
                </select>
                @error('category')<p class="lt-err">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="lt-btn-primary" style="width:100%;justify-content:center;">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Skill
            </button>
        </form>
    </div>

    {{-- SKILL GRID --}}
    <div class="lt-card">
        <div class="lt-card-header">
            <span class="lt-card-title" style="display:flex;align-items:center;gap:0.4rem;">
                <span style="width:8px;height:8px;border-radius:50%;background:#4dd9f0;display:inline-block;"></span>
                Active Technical Grid
            </span>
            <span class="lt-count-badge">{{ $skills->count() }}</span>
        </div>

        <div style="padding:1.25rem;display:flex;flex-direction:column;gap:1.5rem;">
            @php $groupedSkills = $skills->groupBy('category'); @endphp

            @forelse($groupedSkills as $cat => $list)
                <div>
                    <p class="sk-cat-label">{{ $cat }}</p>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.65rem;">
                        @foreach($list as $skill)
                            <div class="sk-card" x-data="{ editing: false, menuOpen: false }" @click.outside="menuOpen = false">
                                <div style="flex:1;min-width:0;" x-show="!editing">
                                    <div style="display:flex;justify-content:space-between;align-items:baseline;">
                                        <span class="sk-name">{{ $skill->name }}</span>
                                    </div>
                                </div>
                                <div x-show="editing" style="display:none;flex:1;">
                                    <form action="{{ route('admin.skills.update', $skill->id) }}" method="POST" style="display:flex; flex-direction:column; gap:0.5rem;">
                                        @csrf
                                        <input type="text" name="name" value="{{ $skill->name }}" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                        <select name="category" required class="lt-input" style="padding:0.35rem 0.65rem;font-size:0.78rem;">
                                            <option value="Core" {{ $skill->category == 'Core' ? 'selected' : '' }}>Core</option>
                                            <option value="External" {{ $skill->category == 'External' ? 'selected' : '' }}>External</option>
                                        </select>
                                        <div style="display:flex;gap:0.5rem;margin-top:0.25rem;">
                                            <button type="submit" class="lt-btn-primary" style="padding:0.38rem 0.9rem;font-size:0.73rem;">Update</button>
                                            <button type="button" @click="editing=false" class="lt-btn-secondary" style="padding:0.38rem 0.7rem;font-size:0.73rem;">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                                <div style="flex-shrink:0; position:relative; z-index:20;" x-show="!editing">
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
                                            <button @click="editing = true; menuOpen = false">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <div class="cms-dd-divider"></div>
                                            <form action="{{ route('admin.skills.delete', $skill->id) }}" method="POST"
                                                  @submit.prevent="if(confirm('Remove {{ $skill->name }}?')) $el.submit()">
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
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="padding:3rem;text-align:center;">
                    <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No skills added yet.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
