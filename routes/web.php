<?php

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
use App\Livewire\LottoResults;
use App\Livewire\Predictions;
use App\Livewire\RequestBet;
use App\Livewire\Settings;
use App\Livewire\Video;

Route::get('/', function () {
    return view('welcome');
});
Route::webhooks('paystack-webhooks', 'paystack');
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
        Route::get('/subscription-callback', [Plans::class, 'subscription_callback'])->name('subscription.callback')->middleware(['throttle:6,1']);;

        // Route::middleware(['subscribe', 'active'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('/request-bet', RequestBet::class)->name('request.bet');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/lotto-results', LottoResults::class)->name('lotto.results');
        Route::get('predictions', Predictions::class)->name('predictions');
        Route::get('/videos', Video::class)->name('videos');
    });

    Route::get('/email/verify', EmailVerification::class)->name('verification.notice');
    Route::post('/email/verification-notification', EmailVerification::class)->middleware(['throttle:6,1'])->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [EmailVerification::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', AdminLogin::class)->name('login');
    });


    Route::middleware('admin')->group(function () {
        Route::get('dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('forcast', Forcast::class)->name('forcast');
        Route::get('predictions', Prediction::class)->name('predictions');
        Route::get('results', Result::class)->name('results');
        Route::get('settings', AdminSettings::class)->name('settings');
        Route::get('videos', Index::class)->name('video.index');
    });
});
