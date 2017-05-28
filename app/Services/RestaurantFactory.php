<?php

namespace App\Services;

use App\Repositories\Restaurant\RestaurantRepository;
use App\Models\Mongo\Restaurant\Restaurant as Mongo_Restaurant;
use App\Models\Mysql\Restaurant\Restaurant as Mysql_Restaurant;

class RestaurantFactory
{
    public static function Create($db_connect="")
    {
        switch($db_connect)
        {
            case 'mongodb':
                return new RestaurantRepository(new Mongo_Restaurant());
                break;
            default:
                return new RestaurantRepository(new Mysql_Restaurant());
                break;
        }
    }
}
