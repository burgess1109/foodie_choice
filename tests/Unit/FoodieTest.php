<?php

namespace Tests\Unit;

use Tests\TestCase;
use Config;
use App\Services\RestaurantFactory;

class FoodieTest extends TestCase
{
    protected $key_name = 'id';

    protected $restaurantRepository;

    protected function setUp()
    {
        parent::setUp();
        $db_connect= Config::get('database.default');

        $this->restaurantRepository=RestaurantFactory::Create($db_connect);

        $this->key_name = $this->restaurantRepository->getKeyName();
    }

    /**
     * Test function Index of FoodieController.
     *
     */
    public function testIndex()
    {
        $response = $this->get('/foodie');

        $response->assertStatus(200);
    }

    /**
     * Test function Show of FoodieController.
     *
     */
    public function testShow()
    {
        $response = $this->get('/foodie/1');

        $response->assertStatus(200)
            ->assertJson([
                $this->key_name => 1,
            ]);
    }

    /**
     *  Test function Store of FoodieController with Exception
     *
     */
    public function testStoreWithException()
    {
        $response =$this->json('POST', '/foodie', ['name' => '']);
        $this->assertEquals('There is no name',$response->exception->getMessage());
    }

    /**
     * Test function Store of FoodieController.
     *
     */
    public function testStore()
    {
        $response =$this->json('POST', '/foodie', ['name' => 'TEST123']);
        $this->assertEquals('true',$response->original);
    }

    /**
     *  Test function Update of FoodieController with Exception
     *
     */
    public function testUpdateWithException()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->json('PATCH', '/foodie/'.$max_sequence, [
            'name' => '',
            'tel' => '02-'.rand(20000000,29999999)]);
        $this->assertEquals('There is no name',$response->exception->getMessage());
    }

    /**
     * Test function Update of FoodieController.
     *
     */
    public function testUpdate()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->json('PATCH', '/foodie/'.$max_sequence, [
            'name' => 'TEST0002',
            'tel' => '02-'.rand(20000000,29999999)]);

        $this->assertEquals(1,$response->original);
    }

    /**
     * Test function Delete of FoodieController.
     *
     */
    public function testDelete()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $response = $this->call('DELETE', '/foodie/'.$max_sequence);

        $this->assertEquals(1,$response->original);
    }
}
