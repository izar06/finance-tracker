<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Jalankan setiap hari tengah malam
Schedule::command('transactions:process-recurring')
    ->dailyAt('00:05')
    ->withoutOverlapping()
    ->runInBackground();
