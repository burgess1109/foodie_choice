<?php

namespace App\Models\Mongo\Restaurant;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\RestaurantInterface;

class Restaurant extends Model implements RestaurantInterface
{
    public $timestamps = false;

    /**
     * DB_CONNECTION
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * table
     *
     * @var string  name
     */
    protected $collection = 'restaurant';
}
