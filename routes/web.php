<?php

use App\Http\Controllers\Callbacks\CardSubscriptionCallback;
use App\Http\Controllers\Callbacks\MomoSubscriptionCallback;
use App\Http\Controllers\Callbacks\PaystackSubscriptionCallback;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Forcast;
use App\Livewire\Admin\Login as AdminLogin;
use App\Livewire\Admin\Prediction;
use App\Livewire\Admin\Result;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\Video\Index;
use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\EmailVerification;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Plans;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\Subscribe;
use App\Livewire\Auth\VerifyInvoice;
use App\Livewire\LottoResults;
use App\Livewire\Predictions;
use App\Livewire\Profile;
use App\Livewire\RecentBets;
use App\Livewire\RequestBet;
use App\Livewire\Settings;
use App\Livewire\Video;

Route::get('/', function () {
    return view('welcome');
});
Route::webhooks('paystack-webhooks', 'paystack');
Route::webhooks('paystack2-webhooks', 'paystack-2');
Route::post('/momo-subscription-callback', [MomoSubscriptionCallback::class, 'handle'])->name('momo.subscription.callback');
// Route::post('/card-subscription-callback', [CardSubscriptionCallback::class, 'handle'])->name('card.subscription.callback');
Route::get('/card-subscription-callback', [PaystackSubscriptionCallback::class, 'handle'])->name('paystack.subscription.callback');

Route::middleware('guest')->group(function () {
    // Route::get('/early-access', EarlyAccess::class)->name('early-access');
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password-request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});


Route::middleware('auth')->group(function () {

    Route::middleware(['verified'])->group(function () {
        Route::get('/plans', Plans::class)->name('plans');
        Route::get('/subscription-callback', [Plans::class, 'subscription_callback'])->name('subscription.callback')->middleware(['throttle:6,1']);
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('/request-bet', RequestBet::class)->name('request.bet');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/lotto-results', LottoResults::class)->name('lotto.results');
        Route::get('bet-request-callback', [RequestBet::class, 'callback'])->name('request.bet.callback');
        Route::get('/recent-bets', RecentBets::class)->name('recent.bets');
        Route::get('/subscribe', Subscribe::class)->name('subscribe');
        Route::get('/verify-invoice', VerifyInvoice::class)->name('verify.invoice');
        Route::get('/profile', Profile::class)->name('profile');

        Route::middleware(['subscribe', 'active'])->group(function () {
            Route::get('predictions', Predictions::class)->name('predictions');
            Route::get('/videos', Video::class)->name('videos');
        });
    });

    Route::get('/email/verify', EmailVerification::class)->name('verification.notice');
    Route::post('/email/verification-notification', EmailVerification::class)->middleware(['throttle:6,1'])->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [EmailVerification::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
});
