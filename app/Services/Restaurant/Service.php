<?php

namespace App\Services\Restaurant;

use App\Repositories\RestaurantRepository;
use App\Services\BasicInterface;

class Service implements BasicInterface
{
    /**
     * @var RestaurantRepository repository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = app('restaurant.repository');
    }

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById(int $id)
    {
        return $this->repository->getDataById($id);
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->repository->getData();
    }

    /**
     * Update data by id
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateData(array $data, int $id)
    {
        return $this->repository->updateData($data, $id);
    }

    /**
     * Insert a new data
     *
     * @param array $data
     * @return mixed
     */
    public function createData(array $data)
    {
        return $this->repository->createData($data);
    }

    /**
     * Remove data by id
     *
     * @param int $id
     * @return mixed
     */
    public function deleteData(int $id)
    {
        return $this->repository->deleteData($id);
    }
}
