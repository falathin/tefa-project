<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PrintMessageScheduler extends Command
{
    protected $signature = 'print:message';
    protected $description = 'Menampilkan pesan di terminal setiap menit';

    public function handle()
    {
        $this->info('Scheduler berjalan: ' . now());

        // Log untuk memastikan berjalan dengan baik
        Log::info('Scheduler berjalan setiap menit: ' . now());
    }
}
