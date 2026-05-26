@extends('admin.layout')

@section('admin_content')

    <!-- Projects Header -->
    <div x-data="{ selectedIds: [], selectAll: false }" class="mb-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Manage Projects</h1>
                <p class="text-sm text-slate-400 font-mono mt-1">Add, edit, or delete items showcasing your software works</p>
            </div>
            <div class="flex gap-3">
                <form x-show="selectedIds.length > 0" x-cloak action="{{ route('admin.projects.bulk-delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete the selected projects?');">
                    @csrf
                    <input type="hidden" name="ids" x-bind:value="selectedIds.join(',')">
                    <button type="submit" class="px-5 py-3 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 font-semibold rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete Selected (<span x-text="selectedIds.length"></span>)
                    </button>
                </form>
                <a href="{{ route('admin.projects.create') }}" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 active:scale-95 transition-all duration-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Project
                </a>
            </div>
        </div>

    <!-- Listings Table Card -->
    <div class="bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-850 text-slate-400 text-xs uppercase tracking-wider font-mono">
                        <th class="px-6 py-4 w-12">
                            <input type="checkbox" x-model="selectAll" 
                                   @change="if(selectAll){ selectedIds = {{ $projects->pluck('id')->toJson() }} } else { selectedIds = [] }" 
                                   class="w-4 h-4 text-cyan-500 bg-slate-900 border-slate-700 rounded focus:ring-cyan-500/20">
                        </th>
                        <th class="px-6 py-4">Project Info</th>
                        <th class="px-6 py-4">Tags</th>
                        <th class="px-6 py-4">Featured</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($projects as $proj)
                        <tr class="hover:bg-slate-850/10 transition-colors duration-150">
                            <!-- Checkbox -->
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $proj->id }}" x-model="selectedIds" 
                                       class="w-4 h-4 text-cyan-500 bg-slate-900 border-slate-700 rounded focus:ring-cyan-500/20">
                            </td>
                            <!-- Info -->
                            <td class="px-6 py-4 flex items-center gap-4">
                                @if($proj->thumbnail_path)
                                    <img src="{{ asset('storage/' . $proj->thumbnail_path) }}" class="w-12 h-12 rounded-lg object-cover border border-slate-800 shadow">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-950 border border-slate-850 flex items-center justify-center text-slate-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-200 truncate">{{ $proj->title }}</p>
                                    <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $proj->slug }}</p>
                                </div>
                            </td>

                            <!-- Tags -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach(array_filter(array_map('trim', explode(',', $proj->tags ?? ''))) as $tag)
                                        <span class="text-[9px] font-medium text-slate-400 bg-slate-950 border border-slate-850 px-2 py-0.5 rounded">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Featured flag status -->
                            <td class="px-6 py-4">
                                @if($proj->featured)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-bold uppercase tracking-wider">
                                        Yes
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-500 font-medium">No</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.projects.edit', $proj->id) }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-950 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-lg hover:text-white transition-all">
                                        Edit
                                    </a>
                                    
                                    <!-- Delete Button Form -->
                                    <form action="{{ route('admin.projects.delete', $proj->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this project?');">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 rounded-lg transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 text-slate-650 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-sm font-medium">No projects added yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div> <!-- End x-data -->

@endsection
