@extends('admin.layout')

@section('admin_content')

    <!-- Skills Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Manage Skills</h1>
        <p class="text-sm text-slate-400 font-mono mt-1">Configure your active coding technologies and expertise indices</p>
    </div>

    <!-- Main Workspace Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Add Skill Form -->
        <div class="lg:col-span-4 bg-slate-900 border border-slate-850 p-6 rounded-2xl shadow-xl">
            <h2 class="text-base font-bold text-white mb-4 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                Add Technical Skill
            </h2>

            <form action="{{ route('admin.skills.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Skill Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required 
                           placeholder="e.g. Laravel, React, Docker"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Category</label>
                    <select name="category" 
                            id="category" 
                            required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-slate-200 text-sm outline-none focus:ring-1 focus:ring-cyan-500/20 transition-all duration-200 cursor-pointer">
                        <option value="Frontend">Frontend</option>
                        <option value="Backend">Backend</option>
                        <option value="Tools">Tools</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proficiency -->
                <div>
                    <label for="proficiency" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 font-mono">Proficiency / Skill Index (0 - 100%)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" 
                               name="proficiency" 
                               id="proficiency" 
                               min="0" 
                               max="100" 
                               value="80"
                               oninput="this.nextElementSibling.value = this.value + '%'"
                               class="w-full bg-slate-950 accent-cyan-500 cursor-pointer h-1.5 rounded-lg appearance-none">
                        <output class="text-xs font-bold text-cyan-400 font-mono w-10 text-right">80%</output>
                    </div>
                    @error('proficiency')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow shadow-cyan-500/10 active:scale-95 transition-all">
                    Add Skill
                </button>

            </form>
        </div>

        <!-- Right Side: Active Skills Listing grouped by category -->
        <div class="lg:col-span-8 bg-slate-900 border border-slate-850 p-6 rounded-2xl shadow-xl space-y-6">
            <h2 class="text-base font-bold text-white mb-4 border-b border-slate-800 pb-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Active Technical Grid
            </h2>

            @php
                $groupedSkills = $skills->groupBy('category');
            @endphp

            @forelse($groupedSkills as $cat => $list)
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider font-mono mb-3 border-b border-slate-950 pb-1.5">{{ $cat }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($list as $skill)
                            <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl flex items-center justify-between gap-3 hover:border-slate-800 transition-colors">
                                <div class="min-w-0 flex-grow">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-semibold text-slate-200 truncate">{{ $skill->name }}</span>
                                        <span class="text-xs font-mono text-cyan-400 font-medium">{{ $skill->proficiency }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-900 rounded-full h-1 overflow-hidden">
                                        <div class="bg-gradient-to-r from-cyan-500 to-indigo-500 h-1 rounded-full" style="width: {{ $skill->proficiency }}%"></div>
                                    </div>
                                </div>
                                
                                <form action="{{ route('admin.skills.delete', $skill->id) }}" method="POST" onsubmit="return confirm('Remove {{ $skill->name }}?');">
                                    @csrf
                                    <button type="submit" class="text-slate-500 hover:text-rose-400 p-1.5 transition-colors" title="Delete Skill">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500">
                    <p class="text-sm font-medium">No active skills added to database.</p>
                </div>
            @endforelse

        </div>

    </div>

@endsection
