<?php

namespace Tests\Unit;

use Tests\TestCase;
use Config;

class FoodieTest extends TestCase
{
    protected $key_name = 'id';
    protected function setUp()
    {
        parent::setUp();
        $db_connect= Config::get('database.default');
        if($db_connect == 'mongodb') $this->key_name = '_id';

    }

    /**
     * Test function Index of RouletteController.
     *
     */
    public function testIndex()
    {
        $response = $this->get('/foodie');

        $response->assertStatus(200);
    }

    /**
     * Test function Show of RouletteController.
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
     *  Test function Store of RouletteController with Exception
     *
     */
    public function testStoreWithException()
    {
        $response =$this->json('POST', '/foodie', ['name' => '']);
        $this->assertEquals('There is no name',$response->exception->getMessage());
    }

    /**
     * Test function Store of RouletteController.
     *
     */
    public function testStore()
    {
        $response =$this->json('POST', '/foodie', ['name' => 'TEST123']);
        $this->assertEquals('true',$response->original);
    }

    /**
     *  Test function Update of RouletteController with Exception
     *
     */
    public function testUpdateWithException()
    {
        $response = $this->json('PATCH', '/foodie/6', [
            'name' => '',
            'tel' => '02-'.rand(20000000,29999999)]);
        $this->assertEquals('There is no name',$response->exception->getMessage());
    }

    /**
     * Test function Update of RouletteController.
     *
     */
    public function testUpdate()
    {
        $response = $this->json('PATCH', '/foodie/6', [
            'name' => 'TEST0002',
            'tel' => '02-'.rand(20000000,29999999)]);

        $this->assertEquals(1,$response->original);
    }

    /**
     * Test function Delete of RouletteController.
     *
     */
    public function testDelete()
    {
        $response = $this->call('DELETE', '/foodie/6');

        $this->assertEquals(1,$response->original);
    }
}
