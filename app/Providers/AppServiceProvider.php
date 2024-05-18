<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Band;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('album', function (string $value) {
            return Album::where('uuid', $value)->firstOrFail();
        });

        Route::bind('band', function (string $value) {
            return Band::where('uuid', $value)->firstOrFail();
        });
    }
}
