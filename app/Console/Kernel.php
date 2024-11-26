<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CleanUpDeletedCustomers::class,  // Register the command
    ];    

    protected function schedule(Schedule $schedule)
    {
        // Schedule the cleanup command to run daily
        $schedule->command('cleanup:deleted-customers')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}