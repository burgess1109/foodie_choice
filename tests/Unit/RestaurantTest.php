<?php

namespace Tests\Unit;

use App\Services\Restaurant\RestaurantFactory;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    protected $key_name = 'id';

    protected $restaurantRepository;

    protected function setUp()
    {
        parent::setUp();
        $this->restaurantRepository = app('restaurant.repository');

        $this->key_name = $this->restaurantRepository->getKeyName();
    }

    /**
     * Test function Index of RestaurantController.
     *
     */
    public function testIndex()
    {
        $response = $this->get('/restaurant');

        $response->assertStatus(200);
    }

    /**
     * Test function Show of RestaurantController.
     *
     */
    public function testShow()
    {
        $response = $this->get('/restaurant/1');

        $response->assertStatus(200)
            ->assertJson([
                $this->key_name => 1,
            ]);
    }

    /**
     *  Test function Store of RestaurantController with  empty name
     *
     */
    public function testStoreWithEmptyName()
    {
        $response = $this->json('POST', '/restaurant', ['name' => '']);
        $response->assertStatus(400)->assertJson(['error' => 'There is no name']);
    }

    /**
     * Test function Store of RestaurantController.
     *
     */
    public function testStore()
    {
        $response = $this->json('POST', '/restaurant', ['name' => 'TEST123']);

        $response->assertStatus(200);
        $this->assertTrue($response->original);
    }

    /**
     *  Test function Update of RestaurantController with empty name
     *
     */
    public function testUpdateWithEmptyName()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->json('PATCH', '/restaurant/' . $max_sequence, [
            'name' => '',
            'tel' => '02-' . rand(20000000, 29999999)
        ]);
        $response->assertStatus(400)->assertJson(['error' => 'There is no name']);
    }

    /**
     * Test function Update of RestaurantController.
     *
     */
    public function testUpdate()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->json('PATCH', '/restaurant/' . $max_sequence, [
            'name' => 'TEST0002',
            'tel' => '02-' . rand(20000000, 29999999)
        ]);

        $response->assertStatus(200);
        $this->assertEquals(true, $response->original);
    }

    /**
     * Test function Delete of RestaurantController.
     *
     */
    public function testDelete()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->call('DELETE', '/restaurant/' . $max_sequence);

        $response->assertStatus(200);
        $this->assertEquals(true, $response->original);
    }
}
