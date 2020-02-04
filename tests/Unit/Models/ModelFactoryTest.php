<?php

namespace Tests\Unit\Models;

use App\Models\ModelFactory;
use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;
use PHPUnit\Framework\TestCase;

class ModelFactoryTest extends TestCase
{
    private function getTestCases()
    {
        $testCases = [];
        array_push(
            $testCases,
            ['db_connect' => 'default', 'excepted' => new MysqlRestaurant()],
            ['db_connect' => 'mongodb', 'excepted' => new MongoRestaurant()]
        );

        return $testCases;
    }

    public function testCreateMysqlRestaurantModel()
    {
        $testCases = $this->getTestCases();

        foreach ($testCases as $testCase) {
            $model = ModelFactory::create($testCase['db_connect']);
            $this->assertEquals($testCase['excepted'], $model);
        }
    }
}
