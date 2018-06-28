<?php

namespace Tests\Unit\Service;

use App\Services\RestaurantService;
use Tests\TestCase;

class RestaurantServiceTest extends TestCase
{
    protected $service;

    protected $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->service = new RestaurantService();
    }

    /**
     * Test acceptedParameters
     *
     */
    public function testAcceptedParameters()
    {
        $acceptedParameters = $this->service->getAcceptedParameters();

        $this->assertContains('name', $acceptedParameters);
    }

    /**
     * Test Method  setDBConnect
     *
     */
    public function testSetDBConnect()
    {
        $db_connect = 'mysql';
        $this->service->setDBConnect($db_connect);

        $this->assertEquals($db_connect, $this->service->db_connect);
    }
}
