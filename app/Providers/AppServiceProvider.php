<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sparepart;
use App\Observers\SparepartObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Sparepart::observe(SparepartObserver::class);
    }
}