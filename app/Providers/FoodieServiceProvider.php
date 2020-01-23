<?php

namespace App\Providers;

use App\Services\Foodie\Service;
use App\Services\BasicInterface;
use Illuminate\Support\ServiceProvider;

class FoodieServiceProvider extends ServiceProvider
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
        $this->app->bind(BasicInterface::class,Service::class);
    }
}
