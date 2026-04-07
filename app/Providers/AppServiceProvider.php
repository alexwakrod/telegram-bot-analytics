<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        
        // Force HTTPS for all URLs (including ngrok)
        if (env('APP_ENV') !== 'local' || request()->isSecure()) {
            URL::forceScheme('https');
        }
    }
}