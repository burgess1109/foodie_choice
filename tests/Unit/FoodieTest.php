<?php

namespace Tests\Unit;

use App\Services\Foodie\FoodieFactory;
use Tests\TestCase;

class FoodieTest extends TestCase
{
    protected $key_name = 'id';

    protected $foodieRepository;

    protected function setUp()
    {
        parent::setUp();
        $db_connect = config('database.default');
        $this->foodieRepository = FoodieFactory::create($db_connect);

        $this->key_name = $this->foodieRepository->getKeyName();
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
     *  Test function Store of FoodieController with  empty name
     *
     */
    public function testStoreWithEmptyName()
    {
        $response = $this->json('POST', '/foodie', ['name' => '']);
        $response->assertStatus(400)->assertJson(['error' => 'There is no name']);
    }

    /**
     * Test function Store of FoodieController.
     *
     */
    public function testStore()
    {
        $response = $this->json('POST', '/foodie', ['name' => 'TEST123']);

        $response->assertStatus(200);
        $this->assertTrue($response->original);
    }

    /**
     *  Test function Update of FoodieController with empty name
     *
     */
    public function testUpdateWithEmptyName()
    {
        $max_sequence = $this->foodieRepository->getMaxSequence();
        $response = $this->json('PATCH', '/foodie/' . $max_sequence, [
            'name' => '',
            'tel' => '02-' . rand(20000000, 29999999)
        ]);
        $response->assertStatus(400)->assertJson(['error' => 'There is no name']);
    }

    /**
     * Test function Update of FoodieController.
     *
     */
    public function testUpdate()
    {
        $max_sequence = $this->foodieRepository->getMaxSequence();
        $response = $this->json('PATCH', '/foodie/' . $max_sequence, [
            'name' => 'TEST0002',
            'tel' => '02-' . rand(20000000, 29999999)
        ]);

        $response->assertStatus(200);
        $this->assertEquals(true, $response->original);
    }

    /**
     * Test function Delete of FoodieController.
     *
     */
    public function testDelete()
    {
        $max_sequence = $this->foodieRepository->getMaxSequence();
        $response = $this->call('DELETE', '/foodie/' . $max_sequence);

        $response->assertStatus(200);
        $this->assertEquals(true, $response->original);
    }
}
