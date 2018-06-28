<?php

namespace Tests\Unit;

use \Mockery;
use Tests\TestCase;
use App\Services\RestaurantService;
use App\Http\Controllers\FoodieController;

class FoodieControllerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Test Method  index
     *
     * 執行 index 時, 預期 Service 端至少會執行一次 method getData
     */
    public function testIndex()
    {
        $mock = Mockery::mock(RestaurantService::class);

        $mock->shouldReceive('getData')->atLeast()
            ->times(1);

        $RouletteController = new FoodieController($mock);

        $RouletteController->index();
    }
}
