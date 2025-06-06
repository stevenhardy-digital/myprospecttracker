<?php

use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommissionsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\StripeOnboardingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/thank-you', function () {
    return view('thank-you');
});

Route::get('/r/{username}', function ($username) {
   session(['referrer' => $username]);
   return redirect()->route('register');
})->name('referral');

Route::view('/changelog', 'changelog')->name('changelog');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContactForm'])->name('contact.send');

// Admin blog routes
Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])->prefix('admin')->group(function () {
    Route::get('/posts', [BlogAdminController::class, 'index'])->name('admin.posts');
    Route::get('/posts/create', [BlogAdminController::class, 'create'])->name('admin.posts.create');
    Route::post('/posts', [BlogAdminController::class, 'store'])->name('admin.posts.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ProspectController::class, 'index'])->name('dashboard');
    Route::get('/billing', function () {
        return view('billing.history', [
            'invoices' => Auth::user()->invoices()
        ]);
    })->name('billing');
    Route::post('/subscribe/monthly', [SubscriptionController::class, 'subscribeMonthly'])->name('subscribe.monthly');
    Route::post('/subscribe/yearly', [SubscriptionController::class, 'subscribeYearly'])->name('subscribe.yearly');
    Route::get('/commissions', [CommissionsController::class, 'index'])->name('commissions.index');
    Route::get('/stripe/connect', [StripeOnboardingController::class, 'redirect'])->name('stripe.connect');
    Route::get('/stripe/refresh', [StripeOnboardingController::class, 'refresh'])->name('stripe.refresh');
    Route::get('/stripe/verification', [StripeOnboardingController::class, 'retryOnboarding'])->name('stripe.onboarding.retry');

});

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// Pro-only feature example
Route::middleware(['auth', \App\Http\Middleware\ProOnly::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Prospects
    Route::post('/prospects', [ProspectController::class, 'store'])->name('prospects.store');
    Route::put('/prospects/{prospect}', [ProspectController::class, 'update'])->name('prospects.update');
    Route::delete('/prospects/{prospect}', [ProspectController::class, 'destroy'])->name('prospects.destroy');

});

require __DIR__.'/auth.php';
