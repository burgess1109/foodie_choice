<?php

namespace App\Models\Mysql\Restaurant;

use Illuminate\Database\Eloquent\Model;
use App\Models\RestaurantInterface;

class Restaurant extends Model implements RestaurantInterface
{
    public $timestamps = false;

    /**
     * DB_CONNECTION
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * table name
     *
     * @var string
     */
    protected $table = 'restaurant';
}
