<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule commands
Schedule::command('test-drive:send-reminders')->dailyAt('08:00');
Schedule::command('review:send-requests')->dailyAt('10:00');
Schedule::command('cart:send-abandoned-reminders')->dailyAt('10:00');
Schedule::command('cart:clean-expired')->weekly();
Schedule::command('reports:generate-monthly')->monthlyOn(1, '00:00');
Schedule::command('currency:update-rates')->daily();
