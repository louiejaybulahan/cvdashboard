<?php

namespace App\Modules\Useraccounts\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'useraccounts');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'useraccounts');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'useraccounts');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
