<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:deactivate-expired-subscriptions')->daily();
Schedule::command('app:bet-command')->dailyAt('09:48');
Schedule::command('app:bet-command')->dailyAt('12:55');
Schedule::command('app:bet-command')->dailyAt('18:45');
