<?php

namespace App\Repositories;

class RestaurantRepository extends Repository implements RepositoryInterface
{
    public function __construct()
    {
        $this->model = app('restaurant.model');

        $this->key_name = $this->model->getKeyName();
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
        $updateData = $this->setAddress($updateData);

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
        $createData = $this->setAddress($createData);

        $id = $this->getNextSequence();
        $createData[$this->key_name] = $id;

        return parent::createData($createData);
    }

    /**
     * Assemble address
     *
     * @param $data
     * @return mixed
     */
    private function setAddress($data)
    {
        $data['address'] = [];

        if (!empty($data['city'])) {
            $data['address'][0]['city'] = $data['city'];
        }

        if (!empty($data['detail'])) {
            $data['address'][0]['detail'] = $data['detail'];
        }

        $data['address'] = json_encode($data['address']);

        unset($data['city']);
        unset($data['detail']);

        return $data;
    }
}
