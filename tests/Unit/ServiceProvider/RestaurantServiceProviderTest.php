<?php

namespace Tests\Unit\ServiceProvider;

use App\Models\ModelFactory;
use App\Providers\RestaurantServiceProvider;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Services\Menu\MenuService;
use App\Services\Restaurant\RestaurantService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Tests\TestCase;

class RestaurantServiceProviderTest extends TestCase
{
    public function testConstruct()
    {
        $app = new Application();
        $this->assertInstanceOf(ServiceProvider::class, new RestaurantServiceProvider($app));
    }

    public function testCreateInstance()
    {
        $dbConnect = config('database.default');
        $model = ModelFactory::create($dbConnect);

        $this->assertInstanceOf(RestaurantService::class, app()->get('restaurant'));
        $this->assertInstanceOf(MenuService::class, app()->get(MenuService::class));
        $this->assertInstanceOf(RestaurantRepository::class, app()->get('restaurant.repository'));
        $this->assertInstanceOf(get_class($model), app()->get('restaurant.model'));
    }
}
