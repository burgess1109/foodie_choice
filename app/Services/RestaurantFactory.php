<?php

namespace App\Services;

use App\Repositories\Restaurant\RestaurantRepository;
use App\Models\Mongo\Restaurant\Restaurant as Mongo_Restaurant;
use App\Models\Mysql\Restaurant\Restaurant as Mysql_Restaurant;

class RestaurantFactory
{
    /**
     * Switch repository
     *
     * @param string $db_connect
     * @return RestaurantRepository
     */
    public static function create($db_connect = "")
    {
        switch ($db_connect) {
            case 'mongodb':
                return new RestaurantRepository(new Mongo_Restaurant());
                break;
            default:
                return new RestaurantRepository(new Mysql_Restaurant());
                break;
        }
    }

    /**
     * Switch model
     *
     * @param string $db_connect
     * @return object model
     */
    public static function createModel($db_connect = "")
    {
        switch ($db_connect) {
            case 'mongodb':
                return new Mongo_Restaurant();
                break;
            default:
                return new Mysql_Restaurant();
                break;
        }
    }

    /**
     * Get model path
     *
     * @param string $db_connect
     * @return string model path
     */
    public static function getModelPath($db_connect = "")
    {
        switch ($db_connect) {
            case 'mongodb':
                return Mongo_Restaurant::class;
                break;
            default:
                return Mysql_Restaurant::class;
                break;
        }
    }
}
