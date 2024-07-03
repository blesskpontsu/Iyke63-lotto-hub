<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EarlyAccess;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/early-access', EarlyAccess::class)->name('early-access');
