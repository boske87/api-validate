<?php

namespace Performance\Validate;

use Illuminate\Support\ServiceProvider;

class ValidateServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Config file path.
        $dist = __DIR__.'/../config/performance.php';

        // Merge config.
        $this->mergeConfigFrom($dist, 'performance');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/performance.php', 'performance');

        // Register the service the package provides.
        $this->app->singleton('performance', function ($app) {
            return new PerformanceValidate;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['performance'];
    }

}