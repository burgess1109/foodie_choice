<?php

namespace Tests\Unit\Restaurant;

use Tests\TestCase;
use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Config;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Models\Mongo\Restaurant\Restaurant as Mongo_Restaurant;
use App\Models\Mysql\Restaurant\Restaurant as Mysql_Restaurant;

abstract class TestBase extends TestCase
{
    protected $db_connect;

    protected $fake_data;

    protected $restaurant;

    protected $factory;

    protected $restaurantRepository;

    protected $add_id=[];

    protected function setUp()
    {
        parent::setUp();
        $this->db_connect = Config::get('database.default');

        switch($this->db_connect)
        {
            case 'mongodb':
                $this->restaurantRepository=new RestaurantRepository(new Mongo_Restaurant());
                break;
            default:
                $this->restaurantRepository=new RestaurantRepository(new Mysql_Restaurant());
                DB::beginTransaction();
                break;
        }
    }

    protected function getFakeData($number=1){
        switch($this->db_connect )
        {
            case 'mongodb':
                $this->restaurant = new Mongo_Restaurant();
                $this->factory = factory(Mongo_Restaurant::class,$number);
                break;
            default:
                $this->restaurant = new Mysql_Restaurant();
                $this->factory = factory(Mysql_Restaurant::class,$number);
                break;
        }

        $key_name = $this->restaurant->getKeyName();
        $max_sequence = $this->restaurant->max($key_name);

        for($i=1;$i<=$number;$i++){
            if($this->db_connect == 'mongodb'){
                $data= ['_id' => $max_sequence+$i];
            }else{
                $data= ['id' => $max_sequence+$i];
            }
            $this->fake_data = $this->factory->create($data);
            $this->add_id[]=$max_sequence+$i;
        }
    }

    protected function tearDown()
    {
        switch($this->db_connect)
        {
            case 'mongodb':
                foreach ($this->add_id as $id){
                    $this->restaurant->where('_id',$id)->delete();
                }
                $this->restaurant->where('name','NEW0001')->delete();
                break;
            default:
                DB::rollBack();
                break;
        }

    }
}
