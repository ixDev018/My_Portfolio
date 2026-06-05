@extends('admin.layout')

@section('admin_content')

<style>
    /* ─── Override cms-main body bg so our light panels pop ─── */
    .cms-main { background: #EDEAE0; }

    /* ─── Shell ─────────────────────────────────────────────── */
    /* cms-main: 2rem top + 2rem bottom = 4rem.
       Page header ~52px + 1rem margin ≈ 4.25rem.
       Total ≈ 8.25rem. */
    .op-shell {
        display: flex;
        gap: 1.25rem;
        height: calc(100vh - 8.5rem);
        min-height: 0;
        overflow: hidden;
    }

    /* ─── LEFT panel ─────────────────────────────────────────── */
    .op-left {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border: 1px solid #D8D4C8;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    /* toolbar */
    .op-toolbar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 1rem;
        background: #F7F5EE;
        border-bottom: 1px solid #E2DDD3;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    .op-search-wrap { position: relative; flex: 1; min-width: 150px; }
    .op-search-wrap svg {
        position: absolute; left: 0.65rem; top: 50%;
        transform: translateY(-50%);
        color: #9B9589; pointer-events: none;
    }
    .op-search {
        width: 100%;
        background: #fff;
        border: 1px solid #D8D4C8;
        border-radius: 0.5rem;
        padding: 0.42rem 0.85rem 0.42rem 2.2rem;
        color: #1a1a1a;
        font-size: 0.8125rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .op-search::placeholder { color: #B0A99F; }
    .op-search:focus {
        border-color: #6829AA;
        box-shadow: 0 0 0 3px rgba(104,41,170,0.1);
    }

    /* filter pills */
    .op-filter-btn {
        display: inline-flex; align-items: center;
        padding: 0.28rem 0.7rem;
        background: #fff;
        border: 1px solid #D8D4C8;
        border-radius: 100px;
        color: #7A7267;
        font-size: 0.6rem;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .op-filter-btn:hover {
        border-color: #6829AA;
        color: #6829AA;
        background: #F3ECFF;
    }
    .op-filter-btn.active {
        background: #6829AA;
        border-color: #6829AA;
        color: #fff;
    }

    /* table scroll */
    .op-table-scroll { flex: 1; overflow-y: auto; }
    .op-table { width: 100%; border-collapse: collapse; }

    .op-table thead tr {
        background: #F7F5EE;
        border-bottom: 1px solid #E2DDD3;
        position: sticky; top: 0; z-index: 1;
    }
    .op-table th {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem; text-transform: uppercase;
        letter-spacing: 0.12em; color: #9B9589;
        padding: 0.6rem 0.9rem; text-align: left;
        white-space: nowrap;
    }
    .op-table td {
        padding: 0.6rem 0.9rem;
        border-bottom: 1px solid #F0EDE6;
        font-size: 0.8125rem; color: #2c2826;
        vertical-align: middle;
    }
    .op-table tr:last-child td { border-bottom: none; }
    .op-table tbody tr { cursor: pointer; transition: background 0.12s; }
    .op-table tbody tr:hover td { background: #F7F5EE; }
    .op-table tbody tr.selected td { background: #EEE6FF; }
    .op-table tbody tr.selected td:first-child {
        box-shadow: inset 3px 0 0 #6829AA;
    }

    /* footer */
    .op-table-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 1rem;
        border-top: 1px solid #E2DDD3;
        background: #F7F5EE;
        flex-shrink: 0;
    }
    .op-count-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem; color: #9B9589;
        text-transform: uppercase; letter-spacing: 0.1em;
    }

    /* ─── 3-dot dropdown ─────────────────────────────────────── */
    .op-dots-wrap { position: relative; }
    .op-dots-btn {
        width: 28px; height: 28px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.4rem;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        color: #9B9589;
        transition: all 0.15s;
    }
    .op-dots-btn:hover {
        background: #F0EDE6;
        border-color: #D8D4C8;
        color: #2c2826;
    }
    .op-dots-btn.open {
        background: #EEE6FF;
        border-color: #C4A8F0;
        color: #6829AA;
    }
    .op-dropdown {
        position: absolute;
        right: 0; top: calc(100% + 4px);
        z-index: 50;
        background: #fff;
        border: 1px solid #D8D4C8;
        border-radius: 0.6rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 140px;
        overflow: hidden;
    }
    .op-dropdown a, .op-dropdown button {
        display: flex; align-items: center; gap: 0.5rem;
        width: 100%; text-align: left;
        padding: 0.55rem 0.85rem;
        font-size: 0.8rem; font-weight: 600;
        font-family: 'Outfit', sans-serif;
        color: #2c2826; background: transparent;
        border: none; cursor: pointer;
        text-decoration: none;
        transition: background 0.12s;
    }
    .op-dropdown a:hover { background: #F7F5EE; }
    .op-dropdown button:hover { background: #FFF1F1; color: #dc2626; }
    .op-dropdown .op-dd-divider {
        height: 1px; background: #F0EDE6; margin: 0.25rem 0;
    }

    /* ─── Sort dropdown ──────────────────────────────────────── */
    .op-sort-wrap { position: relative; flex-shrink: 0; }
    .op-sort-btn {
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.45rem;
        background: #fff;
        border: 1px solid #D8D4C8;
        cursor: pointer;
        color: #7A7267;
        transition: all 0.15s;
    }
    .op-sort-btn:hover, .op-sort-btn.open {
        background: #EEE6FF;
        border-color: #C4A8F0;
        color: #6829AA;
    }
    .op-sort-dropdown {
        position: absolute;
        right: 0; top: calc(100% + 5px);
        z-index: 60;
        background: #fff;
        border: 1px solid #D8D4C8;
        border-radius: 0.6rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 170px;
        overflow: hidden;
        padding: 0.35rem 0;
    }
    .op-sort-label {
        display: block;
        padding: 0.3rem 0.85rem 0.2rem;
        font-family: 'Space Mono', monospace;
        font-size: 0.55rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: #B0A99F;
    }
    .op-sort-option {
        display: flex; align-items: center; gap: 0.5rem;
        width: 100%; text-align: left;
        padding: 0.45rem 0.85rem;
        font-size: 0.78rem; font-weight: 600;
        font-family: 'Outfit', sans-serif;
        color: #2c2826; background: transparent;
        border: none; cursor: pointer;
        transition: background 0.12s;
    }
    .op-sort-option:hover { background: #F7F5EE; }
    .op-sort-option.active { color: #6829AA; background: #F3ECFF; }

    /* ─── Date badge ────────────────────────────────────────── */
    .op-date-cell {
        font-family: 'Space Mono', monospace;
        font-size: 0.57rem; color: #9B9589;
        white-space: nowrap;
    }

    /* ─── Badges ─────────────────────────────────────────────── */
    .op-medium-badge {
        display: inline-flex; align-items: center;
        padding: 0.18rem 0.6rem; border-radius: 100px;
        font-size: 0.58rem; font-family: 'Space Mono', monospace;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
        background: #FFF0E5; color: #C2480A;
        border: 1px solid #FDD5B5;
    }
    .op-tag {
        padding: 0.18rem 0.55rem; border-radius: 100px;
        font-family: 'Space Mono', monospace; font-size: 0.57rem;
        text-transform: uppercase; letter-spacing: 0.05em;
        background: #F0E8FF; color: #6829AA;
        border: 1px solid #D8C0F8;
    }
    .op-featured-yes {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.6rem; border-radius: 100px;
        font-size: 0.58rem; font-family: 'Space Mono', monospace;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
        background: #E6FAF5; color: #0A8C5E;
        border: 1px solid #A3E6CF;
    }
    .op-featured-no {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.6rem; border-radius: 100px;
        font-size: 0.58rem; font-family: 'Space Mono', monospace;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
        background: #F5F3EE; color: #B0A99F;
        border: 1px solid #E2DDD3;
    }

    /* ─── RIGHT panel ────────────────────────────────────────── */
    .op-right {
        width: 300px; flex-shrink: 0;
        display: flex; flex-direction: column;
        background: #ffffff;
        border: 1px solid #D8D4C8;
        border-radius: 1rem; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .op-right-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.65rem 1rem;
        border-bottom: 1px solid #E2DDD3;
        background: #F7F5EE;
        flex-shrink: 0;
    }
    .op-right-header-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.58rem; text-transform: uppercase;
        letter-spacing: 0.12em; color: #9B9589;
    }

    /* ── Thumbnail: FIXED height, covers any ratio ── */
    .op-thumb-container {
        width: 100%;
        height: 168px;          /* fixed — works for portrait, landscape, square */
        flex-shrink: 0;
        overflow: hidden;
        background: #F0EDE6;
        display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    .op-thumb-container img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    .op-thumb-placeholder-icon { color: #C8C3BA; }

    /* meta */
    .op-preview-body {
        flex: 1; overflow-y: auto;
        display: flex; flex-direction: column;
    }
    .op-meta {
        padding: 0.9rem 1rem; flex: 1;
        display: flex; flex-direction: column; gap: 0.8rem;
    }
    .op-meta-title {
        font-family: 'Outfit', sans-serif; font-size: 0.95rem;
        font-weight: 700; color: #1a1207; line-height: 1.3;
    }
    .op-meta-slug {
        font-family: 'Space Mono', monospace; font-size: 0.58rem;
        color: #B0A99F; margin-top: 0.15rem;
        word-break: break-all;
    }
    .op-meta-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;
    }
    .op-meta-item label {
        display: block; font-family: 'Space Mono', monospace;
        font-size: 0.55rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: #9B9589; margin-bottom: 0.2rem;
    }
    .op-meta-item .val {
        font-size: 0.8rem; color: #2c2826; font-weight: 600;
    }
    .op-tags-wrap { display: flex; flex-wrap: wrap; gap: 0.3rem; }

    /* preview actions */
    .op-preview-actions {
        padding: 0.75rem 1rem;
        border-top: 1px solid #E2DDD3;
        display: flex; gap: 0.5rem;
        flex-shrink: 0; background: #F7F5EE;
    }
    .op-preview-actions a, .op-preview-actions button {
        flex: 1; display: inline-flex;
        align-items: center; justify-content: center; gap: 0.35rem;
        padding: 0.5rem 0.6rem; border-radius: 0.5rem;
        font-size: 0.75rem; font-weight: 700;
        font-family: 'Outfit', sans-serif;
        transition: all 0.15s; cursor: pointer;
        text-decoration: none;
    }
    .op-btn-edit {
        background: #6829AA; border: 1px solid #6829AA; color: #fff;
    }
    .op-btn-edit:hover { background: #5720A0; border-color: #5720A0; }

    .op-btn-view {
        background: #fff; border: 1px solid #D8D4C8; color: #5A5248;
    }
    .op-btn-view:hover { background: #F7F5EE; color: #1a1207; }

    .op-btn-del {
        background: #FFF1F1; border: 1px solid #FECACA;
        color: #dc2626; flex: 0; padding: 0.5rem 0.65rem;
    }
    .op-btn-del:hover { background: #FEE2E2; }

    /* empty */
    .op-empty-preview {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 0.65rem; padding: 2rem; text-align: center;
    }
    .op-empty-preview svg { color: #D8D4C8; }
    .op-empty-preview p {
        font-family: 'Space Mono', monospace; font-size: 0.62rem;
        text-transform: uppercase; letter-spacing: 0.1em; color: #B0A99F;
    }

    /* page header */
    .op-page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 0.85rem; flex-shrink: 0;
    }

    /* thumb in table */
    .op-row-thumb {
        width: 36px; height: 36px; border-radius: 0.4rem;
        object-fit: cover; border: 1px solid #E2DDD3; flex-shrink: 0;
    }
    .op-row-thumb-ph {
        width: 36px; height: 36px; border-radius: 0.4rem;
        background: #F0EDE6; border: 1px solid #E2DDD3;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: #C8C3BA;
    }

    /* responsive */
    @media (max-width: 900px) {
        .op-shell { flex-direction: column; height: auto; overflow: visible; }
        .op-right { width: 100%; }
        .op-left { height: 60vh; }
    }

    /* close dropdown on outside click (Alpine handles this) */
    [x-cloak] { display: none !important; }
</style>

<div x-data="{
    selectedProject: null,
    selectedIds: [],
    selectAll: false,
    search: '',
    activeFilter: 'all',
    openMenuId: null,
    sortBy: 'updated',
    sortOpen: false,
    filters: {{ collect($projects->pluck('medium')->filter()->unique()->values())->prepend('all')->toJson() }},
    allProjects: {{ $projects->map(fn($p) => [
        'id'             => $p->id,
        'title'          => $p->title,
        'slug'           => $p->slug,
        'medium'         => $p->medium,
        'year'           => $p->year,
        'tags'           => $p->tags,
        'featured'       => $p->featured,
        'updated_at'     => $p->updated_at ? $p->updated_at->toIso8601String() : null,
        'thumbnail_path' => $p->thumbnail_path ? asset('storage/'.$p->thumbnail_path) : null,
        'thumbnail_video_path' => $p->thumbnail_video_path ? asset('storage/'.$p->thumbnail_video_path) : ($p->main_media_type === 'video' && $p->main_video_path ? asset('storage/'.$p->main_video_path) : ($p->main_media_type === 'video' ? $p->video_url : null)),
        'main_media_type'=> $p->main_media_type,
        'edit_url'       => route('admin.projects.edit', $p->id),
        'delete_url'     => route('admin.projects.delete', $p->id),
        'view_url'       => route('portfolio.project.show', $p->slug),
    ])->toJson() }},
    get filtered() {
        let list = this.allProjects.filter(p => {
            const matchMedium = this.activeFilter === 'all' || p.medium === this.activeFilter;
            const q = this.search.toLowerCase();
            const matchSearch = !q
                || p.title.toLowerCase().includes(q)
                || (p.tags   && p.tags.toLowerCase().includes(q))
                || (p.medium && p.medium.toLowerCase().includes(q));
            return matchMedium && matchSearch;
        });
        if (this.sortBy === 'updated') list = [...list].sort((a,b) => new Date(b.updated_at||0) - new Date(a.updated_at||0));
        if (this.sortBy === 'oldest')  list = [...list].sort((a,b) => new Date(a.updated_at||0) - new Date(b.updated_at||0));
        if (this.sortBy === 'az')      list = [...list].sort((a,b) => a.title.localeCompare(b.title));
        if (this.sortBy === 'za')      list = [...list].sort((a,b) => b.title.localeCompare(a.title));
        if (this.sortBy === 'year')    list = [...list].sort((a,b) => (b.year||0) - (a.year||0));
        if (this.sortBy === 'featured')list = [...list].sort((a,b) => (b.featured ? 1 : 0) - (a.featured ? 1 : 0));
        return list;
    },
    formatDate(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        const now = new Date();
        const diff = Math.floor((now - d) / 1000);
        if (diff < 60)   return 'Just now';
        if (diff < 3600) return Math.floor(diff/60) + 'm ago';
        if (diff < 86400) return Math.floor(diff/3600) + 'h ago';
        if (diff < 604800) return Math.floor(diff/86400) + 'd ago';
        return d.toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
    },
    selectProject(p) { this.selectedProject = p; },
    toggleSelect(id) {
        if (this.selectedIds.includes(id)) {
            this.selectedIds = this.selectedIds.filter(i => i !== id);
        } else {
            this.selectedIds.push(id);
        }
    },
    toggleMenu(id, e) {
        e.stopPropagation();
        this.openMenuId = this.openMenuId === id ? null : id;
    },
    closeMenu() { this.openMenuId = null; this.sortOpen = false; }
}" @click="closeMenu" @keydown.escape="closeMenu">

    {{-- ════ PAGE HEADER ════ --}}
    <div class="op-page-header">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1a1207;letter-spacing:-0.02em;font-family:'Outfit',sans-serif;">Outputs</h1>
            <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.12em;color:#9B9589;margin-top:0.15rem;">Manage your projects</p>
        </div>

        <div style="display:flex;align-items:center;gap:0.75rem;">
            {{-- Bulk delete --}}
            <form x-show="selectedIds.length > 0" x-cloak
                  action="{{ route('admin.projects.bulk-delete') }}" method="POST"
                  onsubmit="return confirm('Permanently delete selected projects?');">
                @csrf
                <input type="hidden" name="ids" x-bind:value="selectedIds.join(',')">
                <button type="submit" style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.5rem 0.9rem;background:#FFF1F1;border:1px solid #FECACA;border-radius:0.5rem;color:#dc2626;font-size:0.75rem;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete (<span x-text="selectedIds.length"></span>)
                </button>
            </form>

            {{-- Add new --}}
            <a href="{{ route('admin.projects.create') }}"
               style="display:inline-flex;align-items:center;gap:0.45rem;padding:0.55rem 1.1rem;background:#6829AA;color:#fff;border-radius:0.6rem;font-size:0.8rem;font-weight:700;font-family:'Outfit',sans-serif;text-decoration:none;box-shadow:0 3px 12px rgba(104,41,170,0.28);transition:all .15s;"
               onmouseover="this.style.background='#5720A0'" onmouseout="this.style.background='#6829AA'">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Output
            </a>
        </div>
    </div>

    {{-- ════ SPLIT SHELL ════ --}}
    <div class="op-shell">

        {{-- ── LEFT: Table ── --}}
        <div class="op-left">

            {{-- Toolbar --}}
            <div class="op-toolbar">
                <div class="op-search-wrap">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Search by title, type, tags…" class="op-search">
                </div>
                <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                    <template x-for="f in filters" :key="f">
                        <button @click="activeFilter = f"
                                :class="activeFilter === f ? 'active' : ''"
                                class="op-filter-btn"
                                x-text="f === 'all' ? 'All' : f">
                        </button>
                    </template>
                </div>

                {{-- Sort button --}}
                <div class="op-sort-wrap" @click.stop>
                    <button class="op-sort-btn" :class="sortOpen ? 'open' : ''" @click="sortOpen = !sortOpen" title="Sort">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                    </button>
                    <div class="op-sort-dropdown" x-show="sortOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <span class="op-sort-label">Sort by</span>
                        <button class="op-sort-option" :class="sortBy==='updated' ? 'active':''" @click="sortBy='updated'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Recently Updated
                        </button>
                        <button class="op-sort-option" :class="sortBy==='oldest' ? 'active':''" @click="sortBy='oldest'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Oldest First
                        </button>
                        <button class="op-sort-option" :class="sortBy==='az' ? 'active':''" @click="sortBy='az'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6"/></svg>
                            Title A → Z
                        </button>
                        <button class="op-sort-option" :class="sortBy==='za' ? 'active':''" @click="sortBy='za'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6"/></svg>
                            Title Z → A
                        </button>
                        <button class="op-sort-option" :class="sortBy==='year' ? 'active':''" @click="sortBy='year'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Year (Newest)
                        </button>
                        <button class="op-sort-option" :class="sortBy==='featured' ? 'active':''" @click="sortBy='featured'; sortOpen=false">
                            <svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Featured First
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="op-table-scroll">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th style="width:2.2rem; padding-left:1rem;">
                                <input type="checkbox" x-model="selectAll"
                                       @change="if(selectAll){ selectedIds = filtered.map(p=>p.id) } else { selectedIds = [] }"
                                       style="width:14px;height:14px;accent-color:#6829AA;cursor:pointer;">
                            </th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Tags</th>
                            <th style="text-align:center;">Featured</th>
                            <th>Updated</th>
                            <th style="width:2.5rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="p in filtered" :key="p.id">
                            <tr @click="selectProject(p)"
                                :class="selectedProject && selectedProject.id === p.id ? 'selected' : ''">

                                {{-- Checkbox --}}
                                <td style="padding-left:1rem;" @click.stop>
                                    <input type="checkbox"
                                           :value="p.id"
                                           :checked="selectedIds.includes(p.id)"
                                           @change="toggleSelect(p.id)"
                                           style="width:14px;height:14px;accent-color:#6829AA;cursor:pointer;">
                                </td>

                                {{-- Thumbnail + title --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.65rem;min-width:0;">
                                        <template x-if="p.thumbnail_path">
                                            <img :src="p.thumbnail_path" class="op-row-thumb" style="object-fit:cover;">
                                        </template>
                                        <template x-if="!p.thumbnail_path && p.thumbnail_video_path">
                                            <video :src="p.thumbnail_video_path + '#t=0.1'" class="op-row-thumb" style="object-fit:cover;" muted playsinline preload="metadata"></video>
                                        </template>
                                        <template x-if="!p.thumbnail_path && !p.thumbnail_video_path">
                                            <div class="op-row-thumb-ph">
                                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        </template>
                                        <div style="min-width:0;">
                                            <p style="font-size:0.8rem;font-weight:600;color:#1a1207;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;" x-text="p.title"></p>
                                            <p style="font-family:'Space Mono',monospace;font-size:0.57rem;color:#B0A99F;margin-top:0.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;" x-text="p.slug"></p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Medium --}}
                                <td>
                                    <template x-if="p.medium">
                                        <span class="op-medium-badge" x-text="p.medium"></span>
                                    </template>
                                    <template x-if="!p.medium">
                                        <span style="color:#D8D4C8;font-size:0.75rem;">—</span>
                                    </template>
                                </td>

                                {{-- Tags --}}
                                <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:0.2rem;max-width:160px;">
                                        <template x-if="p.tags">
                                            <template x-for="tag in p.tags.split(',').map(t=>t.trim()).filter(t=>t)" :key="tag">
                                                <span class="op-tag" x-text="tag"></span>
                                            </template>
                                        </template>
                                    </div>
                                </td>

                                {{-- Featured --}}
                                <td style="text-align:center;">
                                    <template x-if="p.featured">
                                        <span class="op-featured-yes">
                                            <svg style="width:8px;height:8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            Yes
                                        </span>
                                    </template>
                                    <template x-if="!p.featured">
                                        <span class="op-featured-no">No</span>
                                    </template>
                                </td>

                                {{-- Last Updated --}}
                                <td>
                                    <span class="op-date-cell" x-text="formatDate(p.updated_at)"></span>
                                </td>

                                {{-- ··· 3-dot menu --}}
                                <td style="padding-right:0.75rem;" @click.stop>
                                    <div class="op-dots-wrap">
                                        <button class="op-dots-btn"
                                                :class="openMenuId === p.id ? 'open' : ''"
                                                @click="toggleMenu(p.id, $event)"
                                                title="Actions">
                                            <svg style="width:15px;height:15px;" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                                            </svg>
                                        </button>

                                        <div class="op-dropdown"
                                             x-show="openMenuId === p.id"
                                             x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             @click.stop>
                                            <a :href="p.view_url" target="_blank">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                View Live
                                            </a>
                                            <a :href="p.edit_url">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <div class="op-dd-divider"></div>
                                            <form :action="p.delete_url" method="POST"
                                                  @submit.prevent="if(confirm('Delete \'' + p.title + '\'?')) $el.submit()">
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
                        </template>

                        {{-- Empty --}}
                        <template x-if="filtered.length === 0">
                            <tr>
                                <td colspan="6" style="text-align:center;padding:3rem 1rem;">
                                    <svg style="width:2.5rem;height:2.5rem;color:#D8D4C8;margin:0 auto 0.75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p style="font-family:'Space Mono',monospace;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:#B0A99F;">No outputs found</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="op-table-footer">
                <span class="op-count-label">
                    <span x-text="filtered.length"></span>&nbsp;output<span x-show="filtered.length !== 1">s</span>
                    <template x-if="selectedIds.length > 0">
                        <span style="color:#6829AA;font-weight:700;">&nbsp;·&nbsp;<span x-text="selectedIds.length"></span> selected</span>
                    </template>
                </span>
                <span class="op-count-label" x-show="activeFilter !== 'all'">
                    Filtered: <span x-text="activeFilter" style="color:#6829AA;font-weight:700;"></span>
                </span>
            </div>
        </div>

        {{-- ── RIGHT: Preview ── --}}
        <div class="op-right">
            <div class="op-right-header">
                <span class="op-right-header-label">Preview</span>
                <template x-if="selectedProject">
                    <span style="font-family:'Space Mono',monospace;font-size:0.56rem;color:#6829AA;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Selected</span>
                </template>
            </div>

            {{-- Empty state --}}
            <template x-if="!selectedProject">
                <div class="op-empty-preview">
                    <svg style="width:3rem;height:3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                              d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <p>Click a row to preview</p>
                </div>
            </template>

            {{-- Project preview --}}
            <template x-if="selectedProject">
                <div class="op-preview-body">

                    {{-- Fixed-height thumbnail container — handles all ratios --}}
                    <div class="op-thumb-container">
                        <template x-if="selectedProject.thumbnail_path">
                            <img :src="selectedProject.thumbnail_path" :alt="selectedProject.title">
                        </template>
                        <template x-if="!selectedProject.thumbnail_path && selectedProject.thumbnail_video_path">
                            <video :src="selectedProject.thumbnail_video_path + '#t=0.1'" style="width:100%;height:100%;object-fit:cover;" muted playsinline preload="metadata"></video>
                        </template>
                        <template x-if="!selectedProject.thumbnail_path && !selectedProject.thumbnail_video_path">
                            <svg class="op-thumb-placeholder-icon" style="width:3rem;height:3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </template>
                    </div>

                    {{-- Meta --}}
                    <div class="op-meta">
                        <div>
                            <p class="op-meta-title" x-text="selectedProject.title"></p>
                            <p class="op-meta-slug" x-text="'/' + selectedProject.slug"></p>
                        </div>

                        <div class="op-meta-grid">
                            <div class="op-meta-item">
                                <label>Type</label>
                                <span class="val" x-text="selectedProject.medium || '—'"></span>
                            </div>
                            <div class="op-meta-item">
                                <label>Year</label>
                                <span class="val" x-text="selectedProject.year || '—'"></span>
                            </div>
                            <div class="op-meta-item">
                                <label>Featured</label>
                                <template x-if="selectedProject.featured">
                                    <span class="op-featured-yes" style="font-size:0.58rem;">
                                        <svg style="width:8px;height:8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Yes
                                    </span>
                                </template>
                                <template x-if="!selectedProject.featured">
                                    <span class="op-featured-no" style="font-size:0.58rem;">No</span>
                                </template>
                            </div>
                        </div>

                        {{-- Tags --}}
                        <div>
                            <p style="font-family:'Space Mono',monospace;font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:#9B9589;margin-bottom:0.35rem;">Tags</p>
                            <div class="op-tags-wrap">
                                <template x-if="selectedProject.tags && selectedProject.tags.trim() !== ''">
                                    <template x-for="tag in selectedProject.tags.split(',').map(t=>t.trim()).filter(t=>t)" :key="tag">
                                        <span class="op-tag" x-text="tag"></span>
                                    </template>
                                </template>
                                <template x-if="!selectedProject.tags || selectedProject.tags.trim() === ''">
                                    <span style="font-size:0.72rem;color:#C8C3BA;font-style:italic;">No tags</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="op-preview-actions">
                        <a :href="selectedProject.edit_url" class="op-btn-edit">
                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <a :href="selectedProject.view_url" target="_blank" class="op-btn-view">
                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Live
                        </a>
                        <form :action="selectedProject.delete_url" method="POST"
                              @submit.prevent="if(confirm('Delete \'' + selectedProject.title + '\'?')) $el.submit()">
                            @csrf
                            <button type="submit" class="op-btn-del">
                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>

    </div>{{-- /op-shell --}}

</div>{{-- /x-data --}}

@endsection
