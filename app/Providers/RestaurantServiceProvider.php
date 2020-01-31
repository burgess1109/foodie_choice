<?php

namespace App\Providers;

use App;
use App\Models\ModelFactory;
use App\Repositories\RestaurantRepository;
use App\Services\Menu\MenuService;
use App\Services\Restaurant\Service;
use Illuminate\Support\ServiceProvider;

class RestaurantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 設定相依
        $this->app->bind('restaurant', function () {
            return new Service;
        });

        $this->app->bind(MenuService::class);

        $this->app->bind('restaurant.repository', function () {
            return new RestaurantRepository;
        });


        $this->app->singleton('restaurant.model', function () {
            $dbConnect = config('database.default');
            return ModelFactory::create($dbConnect);
        });
    }
}
