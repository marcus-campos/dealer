<?php

namespace MarcusCampos\Dealer;

use Illuminate\Support\ServiceProvider;

class DealerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('dealer.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'dealer');

        // Register the main class to use with the facade
        $this->app->singleton('dealer', function () {
            return new Dealer;
        });
    }
}
