<?php

namespace App\Services\Foodie;

use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;
use App\Repositories\FoodieRepository;

class RepositoryFactory
{
    /**
     * Create repository
     *
     * @param string $db_connect
     * @return FoodieRepository
     */
    public static function create($db_connect = '')
    {
        switch ($db_connect) {
            case 'mongodb':
                return new FoodieRepository(new MongoRestaurant());
                break;
            default:
                return new FoodieRepository(new MysqlRestaurant());
                break;
        }
    }
}
