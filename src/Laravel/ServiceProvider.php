<?php

namespace Olssonm\Roaring\Laravel;

use Olssonm\Roaring\Roaring;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any package services.
     * 
     * @return void
     */
    public function register()
    {
        $this->app->bind('Roaring', function ($app) {
            return new Roaring(config('services.roaring.key'), config('services.roaring.secret'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Roaring'];
    }
}
