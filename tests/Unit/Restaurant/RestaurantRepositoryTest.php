<?php

namespace Tests\Unit;

use Tests\Unit\Restaurant\TestBase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RestaurantRepositoryTest extends TestBase
{

    protected function setUp()
    {
        parent::setUp();
        $this->getFakeData(1);
    }

    /**
     * Test Method  getDataById
     * fake資料與使用repository method 取得的資料做比對
     *
     */
    public function testDataById()
    {
        $repository_data = $this->restaurantRepository->getDataById($this->fake_data[0]->id);

        $this->assertEquals($this->fake_data[0]->name, $repository_data->name);
    }

    /**
     * Test Method  getMaxSequence
     *
     */
    public function testMaxSequence()
    {
        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $this->assertEquals($this->fake_data[0]->id, $max_sequence);
    }

    /**
     * Test Method updateData
     *
     */
    public function testUpdateData()
    {
        $name = 'Update0001';
        $city = 'UpdateCity';
        $updateData = ['name' => $name, 'city' => $city];
        $this->restaurantRepository->updateData($updateData, $this->fake_data[0]->id);

        $repository_data = $this->restaurantRepository->getDataById($this->fake_data[0]->id);

        $address = json_decode($repository_data->address);

        $this->assertEquals($name, $repository_data->name);
        $this->assertEquals($city, $address[0]->city);
    }

    /**
     * Test Method createData
     *
     */
    public function testCreateData()
    {
        $name = 'NEW0001';
        $tel = '02-11111111';
        $city = 'NewCity';
        $createData = ['name' => $name, 'tel' => $tel, 'city' => $city];
        $this->restaurantRepository->createData($createData);

        $max_sequence = $this->restaurantRepository->getMaxSequence();
        $repository_data = $this->restaurantRepository->getDataById($max_sequence);

        $address = json_decode($repository_data->address);

        $this->assertEquals($name, $repository_data->name);
        $this->assertEquals($tel, $repository_data->tel);
        $this->assertEquals($city, $address[0]->city);
    }
}
