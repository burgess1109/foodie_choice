<?php

namespace Tests\Feature;

use App\Facades\Restaurant;
use App\Http\Controllers\RestaurantController;
use App\Services\Menu\MenuService;
use Tests\TestCase;
use Mockery;

class RestaurantTest extends TestCase
{
    public function testIndex()
    {
        $mockReturn = ['test' => 'This is a mock data'];
        Restaurant::shouldReceive('getData')
            ->once()
            ->andReturn($mockReturn);

        $response = $this->get('/restaurant');
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

    public function testShow()
    {
        $mockReturn = ['test' => 'This is a mock data'];
        Restaurant::shouldReceive('getDataById')
            ->once()
            ->with(1)
            ->andReturn($mockReturn);

        $response = $this->get('/restaurant/1');
        $response->assertStatus(200)->assertJson($mockReturn);
    }

    public function testEdit()
    {
        $mockReturn = ['test' => 'This is a mock data'];
        Restaurant::shouldReceive('getDataById')
            ->once()
            ->with(1)
            ->andReturn($mockReturn);

        $response = $this->get('/restaurant/1/edit');
        $response->assertStatus(200)->assertJson($mockReturn);
    }

    /**
     * @dataProvider updateDataProvider
     * @param array $input
     * @param int $exceptedStatusCode
     * @param array $exceptedReturn
     */
    public function testUpdate($input, $exceptedStatusCode, $exceptedReturn)
    {
        Restaurant::shouldReceive('updateData')
            ->with($input, 1)
            ->andReturn($exceptedReturn);

        $response = $this->patch('/restaurant/1', $input);
        $response->assertStatus($exceptedStatusCode)->assertJson($exceptedReturn);
    }

    public function updateDataProvider()
    {
        return [
            'Should be throw error if name is empty' => [
                ['name' => ''],
                400,
                ['error' => 'There is no name'],
            ],
            'Should be OK' => [
                ['name' => 'test'],
                200,
                ['test' => 'This is a mock data'],
            ]
        ];
    }

    /**
     * @dataProvider storeDataProvider
     * @param array $input
     * @param int $exceptedStatusCode
     * @param array $exceptedReturn
     */
    public function testStore($input, $exceptedStatusCode, $exceptedReturn)
    {
        Restaurant::shouldReceive('createData')
            ->with($input)
            ->andReturn($exceptedReturn);

        $response = $this->post('/restaurant', $input);
        $response->assertStatus($exceptedStatusCode)->assertJson($exceptedReturn);
    }

    public function storeDataProvider()
    {
        return [
            'Should be throw error if name is empty' => [
                ['name' => ''],
                400,
                ['error' => 'There is no name'],
            ],
            'Should be OK' => [
                ['name' => 'test'],
                200,
                ['test' => 'This is a mock data'],
            ]
        ];
    }

    public function testDestroy()
    {
        $mockReturn = ['test' => 'This is a mock data'];
        Restaurant::shouldReceive('deleteData')
            ->once()
            ->with(1)
            ->andReturn($mockReturn);

        $response = $this->delete('/restaurant/1');
        $response->assertStatus(200)->assertJson($mockReturn);
    }
}
