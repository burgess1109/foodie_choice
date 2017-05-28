<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /** @var model */
    protected $repository;

    protected $key_name;

    public function __construct()
    {
    }

    public function getDataById($id)
    {
        $row = $this->repository->where($this->key_name,$id)->first();

        return $row;
    }

    public function getData()
    {
        $rows = $this->repository->get();

        return $rows;
    }

    public function updateData($updateData,$id)
    {
        return $this->repository->where($this->key_name,$id)->update($updateData);
    }

    public function createData($createData)
    {
        return $this->repository->insert($createData);
    }

    public function deleteData($id)
    {
        return $this->repository->where($this->key_name,$id)->delete();
    }

    protected function getNextSequence()
    {
        $max_sequence = $this->getMaxSequence();
        $next_sequence = $max_sequence?$max_sequence+1:1;
        return (int)$next_sequence;
    }

    public function getMaxSequence()
    {
        $max_sequence = $this->repository->max($this->key_name);
        return (int)$max_sequence;
    }
}
