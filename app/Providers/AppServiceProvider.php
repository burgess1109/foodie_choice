<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Mongodb\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\RepositoryInterface',
            'App\Repositories\Repository');

        $this->app->bind('App\Models\RestaurantInterface',
            'App\Models\Mongo\Restaurant\Restaurant');

        $this->app->bind('App\Models\RestaurantInterface',
            'App\Models\Mysql\Restaurant\Restaurant');

        Builder::macro('getName', function() {
            return $this->getModel()->getConnectionName();
        });
    }
}
