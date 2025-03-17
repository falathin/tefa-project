<?php

use App\Console\Commands\PrintMessageScheduler;
use App\Console\Commands\SendServiceReminder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(new SendServiceReminder)->dailyAt('05:50');
// Schedule::call(new SendServiceReminder)->dailyAt('05:00');