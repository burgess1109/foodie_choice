<?php

namespace Tests\Unit\Services\Foodie;

use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;
use App\Repositories\FoodieRepository;
use App\Repositories\RepositoryInterface;
use App\Services\Foodie\RepositoryFactory;
use PHPUnit\Framework\TestCase;

class RepositoryFactoryTest extends TestCase
{
    /**
     * @dataProvider dbConnectProvider
     * @param string $db_connect
     * @param RepositoryInterface $excepted
     */
    public function testCreateMysqlRestaurantRepository($db_connect, $excepted)
    {
        $model = RepositoryFactory::create($db_connect);
        $this->assertEquals($excepted, $model);
    }

    public function dbConnectProvider()
    {
        return [
            'Model Should be MysqlRestaurant if db connect is default' =>
                ['default', new FoodieRepository(new MysqlRestaurant())],
            'Model Should be MongoRestaurant if db connect is mongodb' =>
                ['mongodb', new FoodieRepository(new MongoRestaurant())],
        ];
    }
}
