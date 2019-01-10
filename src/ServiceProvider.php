<?php

namespace Olssonm\Roaring;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register any package services.
     * @return void
     */
    public function register()
    {
        $this->app->bind(Roaring::class, function ($app) {
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
        return ['roaring'];
    }
}
