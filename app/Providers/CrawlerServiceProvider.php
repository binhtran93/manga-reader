<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CrawlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Crawler\ICrawlerManga', 'App\Services\Crawler\TruyenTranhDotNet');
    }
}
