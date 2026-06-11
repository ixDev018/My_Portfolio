<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\IntroSlideController;
use App\Http\Middleware\AdminAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Visitor/Guest Frontend Routes
Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/outputs', [PortfolioController::class, 'outputs'])->name('portfolio.outputs');
Route::get('/project/{slug}', [PortfolioController::class, 'showProject'])->name('portfolio.project.show');
Route::post('/contact', [PortfolioController::class, 'contact'])->name('portfolio.contact');

// Admin Auth Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Secure Admin CMS Routes (Protected by Session Auth Middleware)
Route::middleware([AdminAuthMiddleware::class])->prefix('admin')->group(function () {
    // Dashboard Stats
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Profile CMS Settings (Hero page)
    Route::get('/profile', [AdminController::class, 'editProfile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Profile Settings page (Contact, Social, Avatar, CV)
    Route::get('/profile-settings', [AdminController::class, 'editProfileSettings'])->name('admin.profile_settings');
    Route::post('/profile-settings', [AdminController::class, 'updateProfileSettings'])->name('admin.profile_settings.update');

    // Projects CMS CRUD
    Route::get('/projects', [AdminController::class, 'projectsIndex'])->name('admin.projects.index');
    Route::get('/projects/create', [AdminController::class, 'projectsCreate'])->name('admin.projects.create');
    Route::post('/projects/store', [AdminController::class, 'projectsStore'])->name('admin.projects.store');
    Route::get('/projects/edit/{id}', [AdminController::class, 'projectsEdit'])->name('admin.projects.edit');
    Route::post('/projects/update/{id}', [AdminController::class, 'projectsUpdate'])->name('admin.projects.update');
    Route::post('/projects/delete/{id}', [AdminController::class, 'projectsDestroy'])->name('admin.projects.delete');
    Route::post('/projects/bulk-delete', [AdminController::class, 'projectsBulkDelete'])->name('admin.projects.bulk-delete');
    
    // Archive routes
    Route::get('/projects/archived', [AdminController::class, 'projectsArchiveIndex'])->name('admin.projects.archived');
    Route::post('/projects/archive-single', [AdminController::class, 'projectsArchiveSingle'])->name('admin.projects.archive-single');
    Route::post('/projects/restore-single', [AdminController::class, 'projectsRestoreSingle'])->name('admin.projects.restore-single');
    Route::post('/projects/bulk-archive', [AdminController::class, 'projectsBulkArchive'])->name('admin.projects.bulk-archive');
    Route::post('/projects/bulk-restore', [AdminController::class, 'projectsBulkRestore'])->name('admin.projects.bulk-restore');
    
    Route::post('/projects/upload-body-media', [AdminController::class, 'uploadBodyMedia'])->name('admin.projects.upload_body_media');

    // Skills CMS CRUD
    Route::get('/skills', [AdminController::class, 'skillsIndex'])->name('admin.skills.index');
    Route::post('/skills/store', [AdminController::class, 'skillsStore'])->name('admin.skills.store');
    Route::post('/skills/update/{id}', [AdminController::class, 'skillsUpdate'])->name('admin.skills.update');
    Route::post('/skills/delete/{id}', [AdminController::class, 'skillsDestroy'])->name('admin.skills.delete');

    // Messages / Inbox CMS
    Route::get('/messages', [AdminController::class, 'messagesIndex'])->name('admin.messages.index');
    Route::get('/messages/{id}', [AdminController::class, 'messagesShow'])->name('admin.messages.show');
    Route::post('/messages/delete/{id}', [AdminController::class, 'messagesDestroy'])->name('admin.messages.delete');

    // Achievements CRUD
    Route::get('/achievements', [AchievementController::class, 'index'])->name('admin.achievements.index');
    Route::post('/achievements/store', [AchievementController::class, 'store'])->name('admin.achievements.store');
    Route::get('/achievements/edit/{id}', [AchievementController::class, 'edit'])->name('admin.achievements.edit');
    Route::post('/achievements/update/{id}', [AchievementController::class, 'update'])->name('admin.achievements.update');
    Route::post('/achievements/delete/{id}', [AchievementController::class, 'destroy'])->name('admin.achievements.delete');

    // Work Experience CRUD
    Route::get('/experiences', [ExperienceController::class, 'index'])->name('admin.experiences.index');
    Route::post('/experiences/settings', [ExperienceController::class, 'updateSettings'])->name('admin.experiences.settings');
    Route::get('/experiences/create', [ExperienceController::class, 'create'])->name('admin.experiences.create');
    Route::post('/experiences/store', [ExperienceController::class, 'store'])->name('admin.experiences.store');
    Route::get('/experiences/edit/{id}', [ExperienceController::class, 'edit'])->name('admin.experiences.edit');
    Route::match(['post', 'put'], '/experiences/update/{id}', [ExperienceController::class, 'update'])->name('admin.experiences.update');
    Route::post('/experiences/delete/{id}', [ExperienceController::class, 'destroy'])->name('admin.experiences.delete');
    Route::post('/experiences/reorder', [ExperienceController::class, 'reorder'])->name('admin.experiences.reorder');
    Route::post('/experiences/upload-body-media', [ExperienceController::class, 'uploadBodyMedia'])->name('admin.experiences.upload_body_media');

    // Intro Slides CRUD
    Route::get('/intro-slides', [IntroSlideController::class, 'index'])->name('admin.intro_slides.index');
    Route::post('/intro-slides/store', [IntroSlideController::class, 'store'])->name('admin.intro_slides.store');
    Route::get('/intro-slides/edit/{id}', [IntroSlideController::class, 'edit'])->name('admin.intro_slides.edit');
    Route::post('/intro-slides/update/{id}', [IntroSlideController::class, 'update'])->name('admin.intro_slides.update');
    Route::post('/intro-slides/delete/{id}', [IntroSlideController::class, 'destroy'])->name('admin.intro_slides.delete');
    Route::post('/intro-slides/reorder', [IntroSlideController::class, 'reorder'])->name('admin.intro_slides.reorder');

    // Tool Items CRUD (Marquee Section)
    // Removed index since it's merged into skills: Route::get('/tools', [AdminController::class, 'toolItemsIndex'])->name('admin.tools.index');
    Route::post('/tools/store', [AdminController::class, 'toolItemsStore'])->name('admin.tools.store');
    Route::post('/tools/update/{id}', [AdminController::class, 'toolItemsUpdate'])->name('admin.tools.update');
    Route::post('/tools/delete/{id}', [AdminController::class, 'toolItemsDestroy'])->name('admin.tools.delete');
    Route::post('/tools/rename-row', [AdminController::class, 'toolItemsRenameRow'])->name('admin.tools.rename_row');
});
