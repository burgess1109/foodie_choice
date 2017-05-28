<?php

use Illuminate\Database\Seeder;

class RestaurantTablePart2Seeder extends Seeder
{
    protected $name=['美味米糕','人家火雞肉飯','我是傻瓜乾麵','道地日式拉麵','香酥排骨麵','大塊牛肉麵'];

    protected $detail=['厚德路OX號','','厚德路XO號','厚德街520巷','','厚德東街0號'];

    protected $tel=['02-22220000','02-21212323','02-23456789','','',''];

    protected $opentime=['','','11:00~20:00','','10:00~21:00','10:00~21:00'];

    protected $status=['enabled','enabled','enabled','disabled','enabled','enabled'];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<5;$i++){
            $address = array();
            $address[0]["city"] = "Taipei";
            $address[0]["detail"] = $this->detail[$i];
            $address = json_encode($address);
            DB::table('restaurant')->insert([
                'name' => $this->name[$i],
                'address' => $address,
                'tel' => $this->tel[$i],
                'opentime' => $this->opentime[$i],
                'status' => $this->status[$i]
            ]);

        }

    }
}
