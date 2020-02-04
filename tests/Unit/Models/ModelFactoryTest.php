<?php

namespace Tests\Unit\Models;

use App\Models\ModelFactory;
use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;
use PHPUnit\Framework\TestCase;

class ModelFactoryTest extends TestCase
{
    /**
     * @dataProvider dbConnectProvider
     * @param $db_connect
     * @param $excepted
     */
    public function testCreateMysqlRestaurantModel($db_connect, $excepted)
    {
        $model = ModelFactory::create($db_connect);
        $this->assertEquals($excepted, $model);
    }

    public function dbConnectProvider()
    {
        return [
            'Model Should be MysqlRestaurant if db connect is default' => ['default', new MysqlRestaurant()],
            'Model Should be MongoRestaurant if db connect is mongodb' => ['mongodb', new MongoRestaurant()],
        ];
    }
}
