<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
        Paginator::useBootstrap();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            
            // Trust all proxies for reverse proxy situations (e.g., Render, cloud providers)
            Request::setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_ALL);
        }
    }
}
