<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set https
        if ($this->app['config']['app.force_https']) {
            $this->app['request']->server->set('HTTPS', 'on');
        }

    }
}
