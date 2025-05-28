<?php

use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/thank-you', function () {
    return view('thank-you');
});

Route::view('/changelog', 'changelog')->name('changelog');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Admin blog routes
Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])->prefix('admin')->group(function () {
    Route::get('/posts', [BlogAdminController::class, 'index'])->name('admin.posts');
    Route::get('/posts/create', [BlogAdminController::class, 'create'])->name('admin.posts.create');
    Route::post('/posts', [BlogAdminController::class, 'store'])->name('admin.posts.store');
});

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
Route::get('/dashboard', [ProspectController::class, 'index'])->name('dashboard');

// Pro-only feature example
Route::middleware(['auth', \App\Http\Middleware\ProOnly::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
