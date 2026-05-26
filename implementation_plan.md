# Admin CMS Panel — Updated Build Plan v2

We build **one module at a time**, sequentially. Admin panel will be re-themed using the portfolio's brand (purple `#512b81`, cyan `#4dd9f0`, cream `#FAF7E6`, orange `#ff6b00`) as part of Module 1.

---

## Module 1 — Hero Section + Admin Re-theme

**What's editable:**
- Background video upload — replaces the default `bg_showreel_loop.mp4`. Default video always used as fallback if none uploaded.

**Admin Re-theme (done alongside this module):**
- Sidebar: deep purple `#512b81` bg, cyan `#4dd9f0` active state
- Body bg: `#0f0a1a` with subtle purple tint
- Cards: subtle glass-style `rgba(255,255,255,0.04)` with 1px border
- Primary action buttons: orange `#ff6b00`
- Fonts: Outfit (headings), Inter (body) — already loaded

**DB Changes:**
- `[NEW]` Migration: add `hero_video_path` (nullable string) to `profiles`

**Files:**
- `[NEW]` `database/migrations/..._add_hero_video_to_profiles_table.php`
- `[MODIFY]` [Profile.php](file:///d:/repoD/myPortfolio/app/Models/Profile.php) — add `hero_video_path` to `$fillable`
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — handle video upload in `updateProfile()`
- `[MODIFY]` [admin/profile.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/profile.blade.php) — add video upload input with current video preview
- `[MODIFY]` [admin/layout.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/layout.blade.php) — **full re-theme** of sidebar + layout
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — video src becomes `$profile->hero_video_path ?? asset('videos/bg_showreel_loop.mp4')`

---

## Module 2 — Introduction Slides

**What's editable:**
- **Slide 1 is fixed/locked** — its layout (text left, floating portrait image right) is preserved exactly as-is and remains the standard template. Only its content is editable (chapter label, title, subtitle/roles, description paragraphs, profile image).
- Slides 2+ can be created, edited, deleted. They share a flexible layout.
- **Drag-to-reorder** using SortableJS (CDN, no npm). Slide 1 is always pinned at position 0 — it cannot be reordered or deleted.
- Per slide: chapter label (e.g. "I am" / "Chapter 2"), title, subtitle, description (multi-paragraph), image upload.

**DB Changes:**
- `[NEW]` `intro_slides` table: `id`, `chapter_label`, `title`, `subtitle`, `description`, `image_path`, `sort_order` (int), `is_locked` (bool, default false), `timestamps`
- Slide 1 is seeded with `is_locked = true` using current hardcoded content as defaults

**New Model:** `IntroSlide`

**Files:**
- `[NEW]` `database/migrations/..._create_intro_slides_table.php`
- `[NEW]` `app/Models/IntroSlide.php`
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — CRUD + reorder endpoint (locked slides skip delete)
- `[MODIFY]` [web.php](file:///d:/repoD/myPortfolio/routes/web.php) — new intro slide routes
- `[NEW]` `resources/views/admin/intro_slides/index.blade.php` — drag-to-reorder list; Slide 1 shown with a lock badge; inline edit forms per slide; image upload per slide with preview
- `[MODIFY]` [admin/layout.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/layout.blade.php) — add "Intro Slides" sidebar link
- `[MODIFY]` [PortfolioController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/PortfolioController.php) — pass `$introSlides` ordered by `sort_order`
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — replace hardcoded slides with `@foreach($introSlides)` loop; Slide 1 (`is_locked`) uses the exact existing layout template; Alpine `total` set from count

---

## Module 3 — Skills Section (Tool Marquee Rows with PNG Upload + CropperJS)

**Two parts:**

### Part A — Core/External Skills Cards
- Skill cards (name + category) already exist in the admin. We rename categories from `Frontend/Backend/Tools` → `CORE` / `EXTERNAL` to match the frontend display.
- Seed the existing hardcoded skills data from `home.blade.php` into the DB automatically via a seeder.
- The admin skills page already has add/delete. We clean it up and align category labels.

### Part B — Tool Marquee Rows (Marquee PNG items)
- Each marquee row (e.g. "Programming Languages", "Editing Tools", "General Tools") has a **row label** and a list of **tool items**.
- Each tool item has: a **label** (text name) and an **image** (PNG upload via CropperJS).
- CropperJS is loaded via CDN. When user uploads a PNG, a cropper modal opens. The crop is **fixed to a square** (1:1 ratio) and exported at a consistent size (e.g. 80×80px) for display uniformity.
- The marquee container has `overflow: hidden` on each item wrapper so any accidental overflow from PNGs underlaps rather than overlaps neighbors.
- The marquee rows themselves use the **existing height** — the item wrapper enforces a fixed height (matching the current `min-h-[14vh] md:min-h-[11vh]` row height) with `overflow: hidden`.
- Rows are stored as JSON in a `tool_rows` column on `profiles`, seeded with current hardcoded data.

**DB Changes:**
- `[NEW]` Migration: add `tool_rows` (JSON, nullable) to `profiles` table
- Existing `skills` table: update category validation to accept `CORE` / `EXTERNAL`

**Files:**
- `[NEW]` `database/migrations/..._add_tool_rows_to_profiles_table.php`
- `[NEW]` `database/seeders/SkillsSeeder.php` — seeds `Frontend` → `CORE`, `Backend` → `EXTERNAL` items from current hardcoded list
- `[MODIFY]` [Profile.php](file:///d:/repoD/myPortfolio/app/Models/Profile.php) — add `tool_rows` to `$fillable`, cast as `array`
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — `skillsStore` updated to accept `CORE`/`EXTERNAL`; `updateProfile` saves `tool_rows`; new `toolItemStore`/`toolItemDestroy` endpoints
- `[MODIFY]` [web.php](file:///d:/repoD/myPortfolio/routes/web.php) — add tool row item routes
- `[MODIFY]` `resources/views/admin/skills/index.blade.php` — update category dropdown; add separate "Tool Marquee" section with CropperJS PNG upload per item; `overflow: hidden` wrapper on each item
- `[MODIFY]` [PortfolioController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/PortfolioController.php) — pass `$profile->tool_rows` to home view
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — render tool marquee rows dynamically from `$profile->tool_rows`; each item wrapper has `overflow: hidden` + fixed height

---

## Module 4 — Outputs / Projects CRUD + Project Detail Page CMS

### Part A — Projects CRUD (Admin)

**What's editable per project:**
- **Category**: `UI/UX` (value: `ui`) or `Creative Visual Outputs` (value: `visual`)
- **Subcategory / Medium**: free text (e.g. SaaS, Motion, Illustration, Video Edit)
- **Thumbnail**: any size image upload — stored as-is; the masonry/bento grid naturally adapts to the image's native aspect ratio (no forced crop)
- **Video**: video URL OR direct video file upload (`media_type = 'video'`, `video_url` column)
- **Metadata**: title, subtitle, client/source, year, role, collaborators (comma-separated), tags (comma-separated), demo URL, GitHub URL
- **Body content**: multi-paragraph CMS story text (textarea, split on double newline for paragraphs)
- **Gallery slides**: optional image/video slide gallery — up to N items, each uploadable (see Part B)

**Existing fields in DB** (already migrated):
- `category`, `title`, `subtitle`, `slug`, `description`, `body_content`, `thumbnail_path`, `media_type`, `video_url`, `tags`, `client`, `role`, `year`, `medium`, `collaborators`, `demo_url`, `github_url`, `featured`

**New DB Changes:**
- `[NEW]` `project_gallery_items` table: `id`, `project_id` (FK), `file_path`, `media_type` (`image`/`video`), `video_url` (nullable), `sort_order` (int), `caption` (nullable), `timestamps`

**New Model:** `ProjectGalleryItem`

**Files:**
- `[NEW]` `database/migrations/..._create_project_gallery_items_table.php`
- `[NEW]` `app/Models/ProjectGalleryItem.php`
- `[MODIFY]` [Project.php](file:///d:/repoD/myPortfolio/app/Models/Project.php) — add `hasMany(ProjectGalleryItem::class)` relationship
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — update `projectsCreate/Store/Edit/Update` to handle category, media type toggle (image vs video), gallery item upload/delete/reorder
- `[MODIFY]` `resources/views/admin/projects/create.blade.php` — full form: category select, medium input, thumbnail (image or video toggle), all metadata fields, body content textarea, gallery section
- `[MODIFY]` `resources/views/admin/projects/edit.blade.php` — same, with existing data pre-filled; gallery items listed with drag-to-reorder + delete per item + add more
- `[MODIFY]` [web.php](file:///d:/repoD/myPortfolio/routes/web.php) — add gallery item routes (store, delete, reorder)

### Part B — Project Detail Page CMS

The `project-show.blade.php` already has the correct layout. The current **gallery slider is hardcoded** with picsum placeholder images. We wire it to real `ProjectGalleryItem` records.

**What gets wired up from DB:**
| Section | Field |
|---|---|
| Top badge | `medium` |
| Title | `title` |
| Subtitle | `subtitle` |
| Hero media (video or image) | `media_type` + `video_url` / `thumbnail_path` |
| Source | `client` |
| Date Published | `year` |
| Tags | `tags` (comma-split) |
| The Story (overview) | `description` |
| CMS Body (paragraphs) | `body_content` |
| Gallery Slider | `project->galleryItems` ordered by `sort_order` — **optional**: slider section only renders if items exist |
| My Role | `role` |
| Collaborators | `collaborators` (comma-split) |
| View Live button | `demo_url` |
| Repository button | `github_url` |

**Files:**
- `[MODIFY]` [project-show.blade.php](file:///d:/repoD/myPortfolio/resources/views/project-show.blade.php) — replace picsum placeholder gallery array with `$project->galleryItems`; gallery section wrapped in `@if($project->galleryItems->count())` check; each gallery item can be image or video
- `[MODIFY]` [PortfolioController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/PortfolioController.php) — eager load `galleryItems` in `showProject()`

---

## Module 5 — Achievements CRUD

**What's editable:**
- Title, issuer/organization, year, description, type (`award` or `certificate`)
- Categories match the frontend's tabs exactly: Awards | Certificates

**DB Changes:** None — `achievements` table already has all needed columns.

**Files:**
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — add `achievementsIndex`, `achievementsStore`, `achievementsEdit`, `achievementsUpdate`, `achievementsDestroy`
- `[MODIFY]` [web.php](file:///d:/repoD/myPortfolio/routes/web.php) — add achievement CRUD routes
- `[NEW]` `resources/views/admin/achievements/index.blade.php` — list with type badges; inline quick-add form; edit modal or inline edit; delete button per row
- `[MODIFY]` [admin/layout.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/layout.blade.php) — add Achievements sidebar link
- `[MODIFY]` [PortfolioController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/PortfolioController.php) — pass `$achievementsByType` to home view
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — loop `$achievementsByType->flatten()` instead of static data

---

## Module 6 — Work Experience CRUD + Draggable Timeline

**What's editable per entry:**
- Duration (year range, e.g. "2022 - 2025")
- Title / Role (displayed as timeline list item bullet point)
- Company / Issuer (shown as the modal heading)
- Description (shown in the desktop info card + mobile modal)
- Image (shown in the desktop slider + mobile modal image)
- Sort order (drag-to-reorder with SortableJS)

**DB Changes:**
- `[NEW]` Migration: add `sort_order` (int, default 0), `image_path` (nullable string), `title` (nullable string) to `experiences` table

**How timeline stretches dynamically:**
- The vertical line is an SVG `<line>` that uses `flex-1` — it stretches between the top dot and bottom circle automatically regardless of how many items exist
- Each DB experience entry generates one dot + label in the timeline list
- When a new experience is added in the CMS, a new bullet point appears in the timeline and the vertical line stretches accordingly

**Files:**
- `[NEW]` `database/migrations/..._add_fields_to_experiences_table.php`
- `[MODIFY]` [Experience.php](file:///d:/repoD/myPortfolio/app/Models/Experience.php) — add to `$fillable`
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — add full CRUD + POST reorder endpoint
- `[MODIFY]` [web.php](file:///d:/repoD/myPortfolio/routes/web.php) — experience CRUD + reorder routes
- `[NEW]` `resources/views/admin/experiences/index.blade.php` — SortableJS drag handles; create form; edit per row; delete per row
- `[MODIFY]` [admin/layout.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/layout.blade.php) — add Work Experience sidebar link
- `[MODIFY]` [PortfolioController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/PortfolioController.php) — pass `$experiences` ordered by `sort_order`
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — replace hardcoded Alpine `experiences` JS array with `{{ Js::from($experiences->map(...)) }}`; timeline bullet list loops DB items

---

## Module 7 — Collaboration Section

**What's editable:**
- Contact email (already uses `$profile->email` ✓)
- Location string (currently hardcoded as "Silicon Valley, CA, USA")

**DB Changes:**
- `[NEW]` Migration: add `location` (nullable string) to `profiles` table

**Files:**
- `[NEW]` `database/migrations/..._add_location_to_profiles_table.php`
- `[MODIFY]` [Profile.php](file:///d:/repoD/myPortfolio/app/Models/Profile.php) — add `location` to `$fillable`
- `[MODIFY]` [AdminController.php](file:///d:/repoD/myPortfolio/app/Http/Controllers/AdminController.php) — add `location` to validation + `fill()`
- `[MODIFY]` [admin/profile.blade.php](file:///d:/repoD/myPortfolio/resources/views/admin/profile.blade.php) — add Location text input
- `[MODIFY]` [home.blade.php](file:///d:/repoD/myPortfolio/resources/views/home.blade.php) — render `$profile->location ?? 'Silicon Valley, CA, USA'`

---

## Build Order Summary

| # | Module | Key Feature |
|---|---|---|
| 1 | **Hero + Re-theme** | Video upload + themed admin UI |
| 2 | **Intro Slides** | CRUD, locked Slide 1, drag-reorder |
| 3 | **Skills** | Seed data, CropperJS PNG marquee items |
| 4 | **Outputs/Projects** | Full CRUD, gallery, detail page wired |
| 5 | **Achievements** | CRUD, award/certificate categories |
| 6 | **Work Experience** | CRUD, draggable, dynamic timeline |
| 7 | **Collaboration** | Email + location editable |

> [!IMPORTANT]
> Each module is built, wired to the frontend, and verified before moving to the next. Approval needed before starting.
