<?php

namespace App\Providers;

use App;
use App\Models\ModelFactory;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Services\Menu\MenuService;
use App\Services\Restaurant\RestaurantService;
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
            return new RestaurantService;
        });

        $this->app->bind(MenuService::class, function () {
            return new MenuService;
        });

        $this->app->bind('restaurant.repository', function () {
            return new RestaurantRepository;
        });


        $this->app->singleton('restaurant.model', function () {

            /* ioc container 取法
            $config = $this->app->get('config');
            $dbConnect = $config->get('database.default');
            */

            /* laravel facade 取法
            $dbConnect = Config::get('database.default');
             */

            // laravel helper 取法
            $dbConnect = config('database.default');
            return ModelFactory::create($dbConnect);
        });
    }
}
