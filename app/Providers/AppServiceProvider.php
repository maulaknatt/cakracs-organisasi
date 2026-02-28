<?php

namespace App\Providers;

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
        // Force HTTPS if APP_URL is https (Cloudflare Tunnel)
        if (config('app.url') && str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Eager load role untuk user yang sedang login
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                auth()->user()->load('role');
            }
        });
    }
}
