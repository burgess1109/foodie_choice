<?php

namespace App\Repositories;

use App\Models\RestaurantInterface;

abstract class Repository implements RepositoryInterface
{
    /** @var object model */
    protected $model;

    /** @var string  The primary key name */
    protected $keyName;

    public function __construct(RestaurantInterface $restaurant)
    {
        $this->model = $restaurant;

        $this->keyName = $this->model->getKeyName();
    }

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        return $this->model->where($this->keyName, $id)->first();
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->model->get();
    }

    /**
     * Update data by id
     *
     * @param $updateData
     * @param $id
     * @return mixed
     */
    public function updateData($updateData, $id)
    {
        return $this->model->where($this->keyName, $id)->update($updateData);
    }

    /**
     * Insert a new data
     *
     * @param $createData
     * @return mixed
     */
    public function createData($createData)
    {
        return $this->model->insert($createData);
    }

    /**
     * Remove single data by id
     *
     * @param $id
     * @return mixed
     */
    public function deleteData($id)
    {
        return $this->model->where($this->keyName, $id)->delete();
    }

    /**
     * Get the next primary key number
     *
     * @return int $next_sequence
     */
    public function getNextSequence()
    {
        $max_sequence = $this->getMaxSequence();
        $next_sequence = $max_sequence ? $max_sequence + 1 : 1;
        return (int)$next_sequence;
    }

    /**
     * Get the max primary key number
     *
     * @return int $max_sequence
     */
    public function getMaxSequence()
    {
        $max_sequence = $this->model->max($this->keyName);
        return (int)$max_sequence;
    }
}
