<?php

namespace App\Services\Foodie;

use App\Repositories\RepositoryInterface;

abstract class Service
{
    /** @var  string ENV DB_CONNECT */
    protected $db_connect;
    /** @var  object repository */
    protected $repository;

    public function __construct()
    {
        if (empty($this->db_connect)) {
            $this->db_connect = config('database.default');
        }
    }

    /**
     * Get repository
     *
     * @return object
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set repository
     *
     * @param RepositoryInterface $repository
     */
    protected function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get db_connect
     *
     * @return string
     */
    public function getDBConnect()
    {
        return $this->db_connect;
    }
}
