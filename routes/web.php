<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ExportController;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================================
// FRONTEND ROUTES (PUBLIC)
// =============================================

// Frontend Routes (Public)
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/berita', [FrontendController::class, 'posts'])->name('frontend.posts');
Route::get('/berita/populer', [FrontendController::class, 'popularPosts'])->name('frontend.posts.popular');
Route::get('/berita/terkini', [FrontendController::class, 'recentUpdatedPosts'])->name('frontend.posts.recent');
Route::get('/berita/{post:slug}', [FrontendController::class, 'show'])->name('frontend.posts.show');
Route::get('/kategori/{category:slug}', [FrontendController::class, 'category'])->name('frontend.categories.show');
Route::get('/tag/{tag:slug}', [FrontendController::class, 'tag'])->name('frontend.tags.show');

// Route untuk live search
Route::get('/api/live-search', [FrontendController::class, 'liveSearch'])->name('frontend.live-search');

// =============================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// =============================================

require __DIR__.'/auth.php';

// =============================================
// DASHBOARD ROUTES (PROTECTED)
// =============================================

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Routes untuk Editor dan Admin
Route::middleware(['auth', 'isEditor'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::get('/export/posts/pdf', [ExportController::class, 'exportPostsPDF'])->name('export.posts.pdf');
    Route::get('/export/posts/excel', [ExportController::class, 'exportPostsExcel'])->name('export.posts.excel');
    Route::get('/export/posts/{id}/excel', [ExportController::class, 'exportPostExcel'])->name('export.posts.excel.single');
    Route::get('/export/posts/{id}/pdf', [ExportController::class, 'exportPostPDF'])->name('export.posts.pdf.single');
    Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');
});

// Routes hanya untuk Admin
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Categories CRUD
    Route::resource('categories', CategoryController::class);
    
    // Tags CRUD
    Route::resource('tags', TagController::class);
});