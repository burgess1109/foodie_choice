<?php

namespace Tests\Feature;

use App\Http\Controllers\RestaurantController;
use App\Services\BasicInterface;
use App\Services\Foodie\Service;
use App\Services\Menu\MenuService;
use Tests\TestCase;
use Mockery;

class FoodieTest extends TestCase
{
    public function testIndex()
    {
        $mockReturn = ['test' => 'This is a mock data'];
        $mockFoodieService = Mockery::mock(BasicInterface::class);
        $mockFoodieService->shouldReceive('getData')->once()->andReturn($mockReturn);
        $mockMenuService = Mockery::mock(MenuService::class);

        $this->app->instance(Service::class, $mockFoodieService);
        $this->app->instance(MenuService::class, $mockMenuService);

        $response = $this->get('/foodie');
        $response->assertStatus(200)->assertJson($mockReturn);
    }

    public function testMenu()
    {
        $mockReturn = 'This is a mock data';
        $mock = Mockery::mock(MenuService::class);
        $mock->shouldReceive('getData')->once()->andReturn($mockReturn);
        $this->app->instance(MenuService::class, $mock);

        $response = $this->get('/restaurant/menu');
        $response->assertStatus(200)->assertJson([
            'class' => RestaurantController::class,
            'data' => $mockReturn
        ]);
    }
}
