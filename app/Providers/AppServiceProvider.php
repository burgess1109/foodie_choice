<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Mongodb\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Service 綁定 Interface
        $this->app->bind('App\Services\Foodie\FoodieInterface', 'App\Services\Foodie\FoodieService');
        $this->app->bind('App\Services\Menu\MenuInterface', 'App\Services\Menu\MenuService');
    }
}
