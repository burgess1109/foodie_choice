<?php

namespace App\Models;

use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;

class ModelFactory
{
    /**
     * Switch model
     *
     * @param string $db_connect
     * @return MongoRestaurant|MysqlRestaurant
     */
    public static function create($db_connect = '')
    {
        switch ($db_connect) {
            case 'mongodb':
                return new MongoRestaurant();
            default:
                return new MysqlRestaurant();
        }
    }
}
