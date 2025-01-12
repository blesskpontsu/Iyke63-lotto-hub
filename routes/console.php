<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:deactivate-expired-subscriptions')->daily();
Schedule::command('app:bet-command')->dailyAt('13:45');
Schedule::command('app:bet-command')->dailyAt('14:10');
Schedule::command('app:bet-command')->dailyAt('18:45');
