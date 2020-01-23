<?php

namespace App\Services\Foodie;

use App\Repositories\FoodieRepository;
use App\Models\Mongo\Restaurant\Restaurant as MongoRestaurant;
use App\Models\Mysql\Restaurant\Restaurant as MysqlRestaurant;

class RepositoryFactory
{
    /**
     * Create repository
     *
     * @param string $db_connect
     * @return FoodieRepository
     */
    public static function create($db_connect = "")
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

    /**
     * Create model
     *
     * @param string $db_connect
     * @return MongoRestaurant|MysqlRestaurant
     */
    public static function createModel($db_connect = "")
    {
        switch ($db_connect) {
            case 'mongodb':
                return new MongoRestaurant();
            default:
                return new MysqlRestaurant();
        }
    }

    /**
     * Get model path
     *
     * @param string $db_connect
     * @return string
     */
    public static function getModelPath($db_connect = "")
    {
        switch ($db_connect) {
            case 'mongodb':
                return MongoRestaurant::class;
            default:
                return MysqlRestaurant::class;
        }
    }
}
