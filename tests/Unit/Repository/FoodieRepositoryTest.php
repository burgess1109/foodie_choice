<?php

namespace Tests\Unit\Repository;

use Tests\Unit\Repository\TestBase;

class FoodieRepositoryTest extends TestBase
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
        $repository_data = $this->foodieRepository->getDataById($this->fake_data[0]->id);

        $this->assertEquals($this->fake_data[0]->name, $repository_data->name);
    }

    /**
     * Test Method  getMaxSequence
     *
     */
    public function testMaxSequence()
    {
        $max_sequence = $this->foodieRepository->getMaxSequence();
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
        $this->foodieRepository->updateData($updateData, $this->fake_data[0]->id);

        $repository_data = $this->foodieRepository->getDataById($this->fake_data[0]->id);

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
        $this->foodieRepository->createData($createData);

        $max_sequence = $this->foodieRepository->getMaxSequence();
        $repository_data = $this->foodieRepository->getDataById($max_sequence);

        $address = json_decode($repository_data->address);

        $this->assertEquals($name, $repository_data->name);
        $this->assertEquals($tel, $repository_data->tel);
        $this->assertEquals($city, $address[0]->city);
    }
}
