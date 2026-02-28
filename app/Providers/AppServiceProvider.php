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
        // Force HTTPS in production / Vercel
        if (config('app.env') === 'production' || str_contains(request()->getHost(), 'vercel.app')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Eager load role untuk user yang sedang login (Safe for migration)
        view()->composer('*', function ($view) {
            try {
                if (auth()->check() && auth()->user()) {
                    auth()->user()->loadMissing('role');
                }
            } catch (\Exception $e) {
                // Silently fail if database is not ready
            }
        });
    }
}
