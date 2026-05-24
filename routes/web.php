<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Visitor/Guest Frontend Routes
Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');
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

    // Profile CMS Settings
    Route::get('/profile', [AdminController::class, 'editProfile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Projects CMS CRUD
    Route::get('/projects', [AdminController::class, 'projectsIndex'])->name('admin.projects.index');
    Route::get('/projects/create', [AdminController::class, 'projectsCreate'])->name('admin.projects.create');
    Route::post('/projects/store', [AdminController::class, 'projectsStore'])->name('admin.projects.store');
    Route::get('/projects/edit/{id}', [AdminController::class, 'projectsEdit'])->name('admin.projects.edit');
    Route::post('/projects/update/{id}', [AdminController::class, 'projectsUpdate'])->name('admin.projects.update');
    Route::post('/projects/delete/{id}', [AdminController::class, 'projectsDestroy'])->name('admin.projects.delete');

    // Skills CMS CRUD
    Route::get('/skills', [AdminController::class, 'skillsIndex'])->name('admin.skills.index');
    Route::post('/skills/store', [AdminController::class, 'skillsStore'])->name('admin.skills.store');
    Route::post('/skills/delete/{id}', [AdminController::class, 'skillsDestroy'])->name('admin.skills.delete');

    // Messages / Inbox CMS
    Route::get('/messages', [AdminController::class, 'messagesIndex'])->name('admin.messages.index');
    Route::get('/messages/{id}', [AdminController::class, 'messagesShow'])->name('admin.messages.show');
    Route::post('/messages/delete/{id}', [AdminController::class, 'messagesDestroy'])->name('admin.messages.delete');
});
