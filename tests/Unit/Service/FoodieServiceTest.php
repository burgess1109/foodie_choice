<?php

namespace Tests\Unit\Service;

use App\Services\Foodie\FoodieService;
use Tests\TestCase;

class FoodieServiceTest extends TestCase
{
    protected $service;

    protected $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->service = new FoodieService();
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
    public function testDBConnectWithConfigSetting()
    {
        $dbConnect = ['mysql', 'mongodb'];
        foreach ($dbConnect as $database) {
            config(['database.default' => $database]);

            $service = new FoodieService();
            $this->assertEquals($database, $service->getDBConnect());
        }
    }
}
