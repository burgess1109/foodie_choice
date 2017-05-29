<?php

namespace App\Repositories\Restaurant;

use DB;
use App\Models\RestaurantInterface;
use App\Repositories\Repository;

class RestaurantRepository extends Repository
{
    public function __construct(RestaurantInterface $restaurant)
    {
        parent::__construct();
        $this->repository = $restaurant;

        $this->key_name = $restaurant->getKeyName();
    }

    /**
     *  Update data by id
     *
     * @param $updateData
     * @param $id
     * @return mixed
     */
    public function updateData($updateData,$id){
        $updateData = $this->setAddress($updateData);

        return $this->repository->where($this->key_name,$id)->update($updateData);
    }

    /**
     *  Insert a new data
     *
     * @param $createData
     * @return mixed
     */
    public function createData($createData)
    {
        $createData = $this->setAddress($createData);

        $id = $this->getNextSequence();
        $createData[$this->key_name]=$id;
        return $this->repository->insert($createData);
    }

    /**
     * Assemble address
     *
     * @param $data
     * @return mixed
     */
    protected function setAddress($data){
        if(!empty($data['city']) || !empty($data['detail'])){
            $data['address'][0]['city']=empty($data['city'])?'':$data['city'];
            $data['address'][0]['detail']=empty($data['detail'])?'':$data['detail'];

            $data['address'] = json_encode($data['address']);
        }

        unset($data['city']);
        unset($data['detail']);

        return $data;
    }
}
