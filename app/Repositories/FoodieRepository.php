<?php

namespace App\Repositories;

use App\Models\RestaurantInterface;

class FoodieRepository extends Repository
{
    use DataAssemble;

    public function __construct(RestaurantInterface $restaurant)
    {
        parent::__construct($restaurant);
    }

    /**
     *  Update data by id
     *
     * @param $updateData
     * @param $id
     * @return mixed
     */
    public function updateData($updateData, $id)
    {
        $updateData['address'] = $this->setAddress($updateData['city'], $updateData['detail']);
        unset($updateData['city']);
        unset($updateData['detail']);

        return parent::updateData($updateData, $id);
    }

    /**
     *  Insert a new data
     *
     * @param $createData
     * @return mixed
     */
    public function createData($createData)
    {
        $createData['address'] = $this->setAddress($createData['city'], $createData['detail']);
        unset($createData['city']);
        unset($createData['detail']);

        $id = $this->getNextSequence();
        $createData[$this->keyName] = $id;

        return parent::createData($createData);
    }
}
