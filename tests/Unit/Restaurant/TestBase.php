<?php

namespace Tests\Unit\Restaurant;

use Tests\TestCase;
use DB;
use Config;
use App\Services\RestaurantFactory;

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

        $this->restaurantRepository=RestaurantFactory::Create($this->db_connect);

        if($this->db_connect != 'mongodb')  DB::beginTransaction();
    }

    /**
     * Get fake date
     *
     * @param int $number
     */
    protected function getFakeData($number=1){
        $this->restaurant = RestaurantFactory::CreateModel($this->db_connect);
        $this->factory = factory(RestaurantFactory::GetModelPath($this->db_connect),$number);

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
