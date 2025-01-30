<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Sparepart;
use App\Observers\SparepartObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
// use App\Http\Middleware\LevelMiddleware;


class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Sparepart::observe(SparepartObserver::class);
    
        // Daftarkan middleware global
        // $this->app['router']->aliasMiddleware('role', LevelMiddleware::class);
    
        Gate::define('isAdminOrEngineer', function(User $user) {
            return $user->level == 'admin' xor $user->level == 'engineer';
        });
    
        
    
        Gate::define('isEngineer', function(User $user) {
            return $user->level == 'engineer';
        });
    
        Gate::define('isBendahara', function(User $user) {
            return $user->level == 'bendahara';
        });
    
        Gate::define('isKasir', function(User $user) {
            return $user->level == 'kasir';
        });        
    
        Gate::define('isSameJurusan', function (User $user, ?Model $model) {
            if ($model) {
                if($user->jurusan == 'General') {
                    return true;
                }
                else {
                    return $user->jurusan == $model->jurusan;
                }
            }
        });
}
}