<?php

namespace App\Models\Mongo\Restaurant;

use DB;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Models\RestaurantInterface;

class Restaurant extends Model implements RestaurantInterface
{
    public $timestamps = false;
    /** @var string DB_CONNECTION */
    protected $connection = 'mongodb';
    /** @var string table name。 */
    protected $collection = 'restaurant';
}
