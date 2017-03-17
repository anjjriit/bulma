<?php

namespace rustymulvaney\bulma;

use Illuminate\Support\ServiceProvider;
use rustymulvaney\bulma\Commands\InstallCommand;

class BulmaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');

        $this->publishes([
            __DIR__.'/../stubs/js'    => resource_path('assets/js'),
            __DIR__.'/../stubs/sass'  => resource_path('assets/sass'),
            __DIR__.'/../stubs/views' => resource_path('views'),
        ], 'install');

        $this->publishes([
            __DIR__.'/../config/bulma.php' => config_path('bulma.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
