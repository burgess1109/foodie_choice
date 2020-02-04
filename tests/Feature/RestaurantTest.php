<?php

namespace Tests\Feature;

use App\Http\Controllers\RestaurantController;
use App\Services\Menu\MenuService;
use Tests\TestCase;
use Mockery;

class RestaurantTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/facade');
        $response->assertStatus(200);
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
