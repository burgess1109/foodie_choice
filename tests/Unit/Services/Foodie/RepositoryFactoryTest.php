<?php

namespace Tests\Unit\Services\Foodie;

use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;
use App\Repositories\FoodieRepository;
use App\Services\Foodie\RepositoryFactory;
use PHPUnit\Framework\TestCase;

class RepositoryFactoryTest extends TestCase
{
    private function getTestCases()
    {
        $testCases = [];
        array_push(
            $testCases,
            ['db_connect' => 'default', 'excepted' => new FoodieRepository(new MysqlRestaurant())],
            ['db_connect' => 'mongodb', 'excepted' => new FoodieRepository(new MongoRestaurant())]
        );

        return $testCases;
    }

    public function testCreateMysqlRestaurantRepository()
    {
        $testCases = $this->getTestCases();

        foreach ($testCases as $testCase) {
            $model = RepositoryFactory::create($testCase['db_connect']);
            $this->assertEquals($testCase['excepted'], $model);
        }
    }
}
