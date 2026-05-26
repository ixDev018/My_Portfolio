@extends('admin.layout')

@section('admin_content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="cms-page-title">Achievements</h1>
            <p class="cms-page-subtitle">Awards & Certificates — displayed in the Achievements section</p>
        </div>
        <button onclick="document.getElementById('add-form').classList.toggle('hidden')"
                class="cms-btn-primary" style="padding:0.6rem 1.25rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Achievement
        </button>
    </div>

    {{-- ADD FORM --}}
    <div id="add-form" class="{{ old('_action') === 'add' ? '' : 'hidden' }} cms-card p-6 mb-6">
        <h2 style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:1.25rem;">
            ➕ New Achievement
        </h2>
        <form action="{{ route('admin.achievements.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="cms-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="cms-input" placeholder="e.g. Best UI Design Award">
                    @error('title')<p style="color:#f87171;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="cms-label">Issuer / Organization</label>
                    <input type="text" name="issuer" value="{{ old('issuer') }}" required class="cms-input" placeholder="e.g. Google, Adobe, etc.">
                </div>
                <div>
                    <label class="cms-label">Year</label>
                    <input type="text" name="year" value="{{ old('year') }}" required class="cms-input" placeholder="e.g. 2024">
                </div>
                <div>
                    <label class="cms-label">Type</label>
                    <select name="type" class="cms-input">
                        <option value="award" {{ old('type') === 'award' ? 'selected' : '' }}>🏆 Award</option>
                        <option value="certificate" {{ old('type') === 'certificate' ? 'selected' : '' }}>📜 Certificate</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="cms-label">Description (optional)</label>
                    <textarea name="description" rows="2" class="cms-input" style="resize:vertical;" placeholder="Brief description of the achievement...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div style="margin-top:1rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" onclick="document.getElementById('add-form').classList.add('hidden')" class="cms-btn-secondary">Cancel</button>
                <button type="submit" class="cms-btn-primary">Save Achievement</button>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="cms-card overflow-hidden">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:space-between;">
            <h2 style="font-family:'Outfit',sans-serif;font-size:0.875rem;font-weight:700;color:#fff;">
                All Achievements
                <span class="cms-badge cms-badge-cyan" style="margin-left:0.5rem;">{{ $achievements->count() }}</span>
            </h2>
            <div style="display:flex;gap:0.5rem;">
                <span class="cms-badge cms-badge-orange">{{ $achievements->where('type','award')->count() }} Awards</span>
                <span class="cms-badge cms-badge-purple">{{ $achievements->where('type','certificate')->count() }} Certs</span>
            </div>
        </div>

        @if($achievements->isEmpty())
            <div style="padding:3rem;text-align:center;">
                <svg style="width:36px;height:36px;color:rgba(255,255,255,0.1);margin:0 auto 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <p style="font-size:0.8rem;color:rgba(255,255,255,0.25);font-family:'Space Mono',monospace;">No achievements yet. Add one above.</p>
            </div>
        @else
            <table class="cms-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Issuer</th>
                        <th>Year</th>
                        <th>Type</th>
                        <th style="width:130px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achievements as $item)
                        <tr x-data="{ editing: {{ isset($achievement) && $achievement->id === $item->id ? 'true' : 'false' }} }">
                            <td>
                                <span x-show="!editing" style="color:#fff;font-weight:600;">{{ $item->title }}</span>
                                {{-- Inline Edit Form --}}
                                <div x-show="editing" style="display:none;">
                                    <form action="{{ route('admin.achievements.update', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-2" style="min-width:220px;">
                                            <input type="text" name="title" value="{{ $item->title }}" required class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                            <input type="text" name="issuer" value="{{ $item->issuer }}" required class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                            <div style="display:flex;gap:0.5rem;">
                                                <input type="text" name="year" value="{{ $item->year }}" required class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;width:80px;">
                                                <select name="type" class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;">
                                                    <option value="award" {{ $item->type === 'award' ? 'selected' : '' }}>Award</option>
                                                    <option value="certificate" {{ $item->type === 'certificate' ? 'selected' : '' }}>Certificate</option>
                                                </select>
                                            </div>
                                            <textarea name="description" rows="2" class="cms-input" style="font-size:0.8rem;padding:0.45rem 0.75rem;resize:vertical;">{{ $item->description }}</textarea>
                                            <div style="display:flex;gap:0.5rem;">
                                                <button type="submit" class="cms-btn-primary" style="padding:0.4rem 0.875rem;font-size:0.75rem;">Save</button>
                                                <button type="button" @click="editing=false" class="cms-btn-secondary" style="padding:0.4rem 0.75rem;font-size:0.75rem;">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td x-show="!editing" style="color:rgba(255,255,255,0.55);font-size:0.8rem;">{{ $item->issuer }}</td>
                            <td x-show="!editing">
                                <span class="cms-badge cms-badge-cyan">{{ $item->year }}</span>
                            </td>
                            <td x-show="!editing">
                                @if($item->type === 'award')
                                    <span class="cms-badge cms-badge-orange">🏆 Award</span>
                                @else
                                    <span class="cms-badge cms-badge-purple">📜 Certificate</span>
                                @endif
                            </td>
                            <td x-show="!editing">
                                <div style="display:flex;gap:0.4rem;justify-content:flex-end;">
                                    <button @click="editing=true" class="cms-btn-secondary" style="padding:0.4rem 0.75rem;font-size:0.72rem;">Edit</button>
                                    <form action="{{ route('admin.achievements.delete', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this achievement?')">
                                        @csrf
                                        <button type="submit" class="cms-btn-danger" style="padding:0.4rem 0.75rem;font-size:0.72rem;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
