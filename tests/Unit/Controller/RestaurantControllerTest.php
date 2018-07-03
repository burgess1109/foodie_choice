<?php

namespace Tests\Unit\Controller;

use App\Facades\Restaurant;
use App\Http\Controllers\RestaurantController;
use Tests\TestCase;

class RestaurantControllerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Test Method  index
     *
     * 執行 index 時, 預期 RestaurantService 端至少會執行一次 method getData
     */
    public function testIndex()
    {
        //laravel facades 會自行 mock
        Restaurant::shouldReceive('getData')->atLeast()
            ->times(1);

        $RouletteController = new RestaurantController();
        $RouletteController->index();
    }
}
