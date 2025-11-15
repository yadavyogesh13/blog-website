<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

// Legal and Contact Pages
Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy.policy');

Route::get('/terms-of-service', function () {
    return view('pages.terms-of-service');
})->name('terms.service');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Sitemap Route
Route::get('/sitemap.xml', function() {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Authentication
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Posts - AJAX CRUD routes
        Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
        Route::get('/posts/data', [AdminPostController::class, 'getPostsData'])->name('posts.data');
        Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit')->where('post', '[0-9]+');
        Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
        Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
        Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update')->where('post', '[0-9]+');
        Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy')->where('post', '[0-9]+');
        Route::post('/posts/{post}/restore', [AdminPostController::class, 'restore'])->name('posts.restore')->where('post', '[0-9]+');
        Route::delete('/posts/{post}/force-delete', [AdminPostController::class, 'forceDestroy'])->name('posts.forceDelete')->where('post', '[0-9]+');
        
        // Admin Routes - Add this inside your admin middleware group
        Route::post('/upload', [AdminPostController::class, 'upload'])->name('upload');

        // Categories - AJAX CRUD routes
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{category}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/categories/{category}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('categories.forceDelete');
        Route::post('/categories/multiple', [AdminCategoryController::class, 'storeMultiple'])->name('categories.store-multiple');
        Route::post('/categories/bulk-delete', [AdminCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    });
});

// API Routes for AJAX calls
Route::prefix('api')->group(function () {
    Route::get('/posts', [PostController::class, 'getPosts'])->name('api.posts');
    Route::get('/categories', [CategoryController::class, 'getCategories'])->name('api.categories');
    Route::get('/featured-posts', [HomeController::class, 'getFeaturedPosts'])->name('api.featured-posts');
    Route::get('/recent-posts', [HomeController::class, 'getRecentPosts'])->name('api.recent-posts');
    Route::get('/popular-posts', [HomeController::class, 'getPopularPosts'])->name('api.popular-posts');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Like routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/bookmark', [LikeController::class, 'bookmark'])->name('posts.bookmark');
});