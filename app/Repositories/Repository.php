<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /** @var object repository */
    protected $repository;

    /** @var string  The primary key name */
    protected $key_name;

    public function __construct()
    {
    }

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        $row = $this->repository->where($this->key_name,$id)->first();

        return $row;
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        $rows = $this->repository->get();

        return $rows;
    }

    /**
     * Update data by id
     *
     * @param $updateData
     * @param $id
     * @return mixed
     */
    public function updateData($updateData,$id)
    {
        return $this->repository->where($this->key_name,$id)->update($updateData);
    }

    /**
     * Insert a new data
     *
     * @param $createData
     * @return mixed
     */
    public function createData($createData)
    {
        return $this->repository->insert($createData);
    }

    /**
     * Remove single data by id
     *
     * @param $id
     * @return mixed
     */
    public function deleteData($id)
    {
        return $this->repository->where($this->key_name,$id)->delete();
    }

    /**
     * Get the next primary key number
     *
     * @return int $next_sequence
     */
    protected function getNextSequence()
    {
        $max_sequence = $this->getMaxSequence();
        $next_sequence = $max_sequence?$max_sequence+1:1;
        return (int)$next_sequence;
    }

    /**
     * Get the max primary key number
     *
     * @return int $max_sequence
     */
    public function getMaxSequence()
    {
        $max_sequence = $this->repository->max($this->key_name);
        return (int)$max_sequence;
    }
}
