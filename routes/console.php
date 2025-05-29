<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('users:downgrade-expired-grace')->dailyAt('03:00');
Schedule::command('pay:commissions')->weeklyOn(1, '04:00');;
