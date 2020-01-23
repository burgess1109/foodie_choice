<?php

namespace App\Repositories;

abstract class Repository implements RepositoryInterface
{
    /** @var object model */
    protected $model;

    /** @var string  The primary key name */
    protected $key_name;

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        $row = $this->model->where($this->key_name, $id)->first();

        return $row;
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        $rows = $this->model->get();

        return $rows;
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
        return $this->model->where($this->key_name, $id)->update($updateData);
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
        return $this->model->where($this->key_name, $id)->delete();
    }

    /**
     * Get Key Name
     *
     * @return string $key_name
     */
    public function getKeyName()
    {
        return $this->key_name;
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
        $max_sequence = $this->model->max($this->key_name);
        return (int)$max_sequence;
    }
}
