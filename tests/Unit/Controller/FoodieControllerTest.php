<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\FoodieController;
use App\Services\Foodie\FoodieService;
use App\Services\Menu\MenuService;
use Mockery;
use Tests\TestCase;

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
        $foodieService = Mockery::mock(FoodieService::class);

        $foodieService->shouldReceive('getData')->atLeast()
            ->times(1);

        $menuService = Mockery::mock(MenuService::class);

        $RouletteController = new FoodieController($foodieService, $menuService);

        $RouletteController->index();
    }
}
