<?php

namespace Tests\Unit\Repository;

use App\Services\Foodie\FoodieFactory;
use DB;
use Tests\TestCase;

abstract class TestBase extends TestCase
{
    protected $db_connect;

    protected $fake_data;

    protected $foodie;

    protected $factory;

    protected $foodieRepository;

    protected $add_id = [];

    protected function setUp()
    {
        parent::setUp();
        $this->db_connect = config('database.default');

        $this->foodieRepository = FoodieFactory::create($this->db_connect);

        if ($this->db_connect != 'mongodb') {
            DB::beginTransaction();
        }
    }

    protected function tearDown()
    {
        switch ($this->db_connect) {
            case 'mongodb':
                foreach ($this->add_id as $id) {
                    $this->foodie->where('_id', $id)->delete();
                }
                $this->foodie->where('name', 'NEW0001')->delete();
                break;
            default:
                DB::rollBack();
                break;
        }
    }

    /**
     * Get fake date
     *
     * @param int $number
     */
    protected function getFakeData($number = 1)
    {
        $this->foodie = FoodieFactory::createModel($this->db_connect);
        $this->factory = factory(FoodieFactory::getModelPath($this->db_connect), $number);

        $key_name = $this->foodie->getKeyName();
        $max_sequence = $this->foodie->max($key_name);

        for ($i = 1; $i <= $number; $i++) {
            if ($this->db_connect == 'mongodb') {
                $data = ['_id' => $max_sequence + $i];
            } else {
                $data = ['id' => $max_sequence + $i];
            }
            $this->fake_data = $this->factory->create($data);
            $this->add_id[] = $max_sequence + $i;
        }
    }
}
