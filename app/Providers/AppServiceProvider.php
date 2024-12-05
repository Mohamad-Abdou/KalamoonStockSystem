<?php

namespace App\Providers;

use App\Models\Item;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('middleware', function ($middleware) {
            return in_array($middleware, request()->route()->middleware());
        });
    }
}
