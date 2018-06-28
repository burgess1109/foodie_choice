<?php

namespace App\Models\Mysql\Restaurant;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\RestaurantInterface;

class Restaurant extends Model implements RestaurantInterface
{
    public $timestamps = false;
    /** @var string DB_CONNECTION */
    protected $connection = 'mysql';
    /** @var string table name。 */
    protected $table = 'restaurant';
}
