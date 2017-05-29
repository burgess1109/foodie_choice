<?php

namespace App\Services;

use Config;
use App\Repositories\RepositoryInterface;

abstract class Service
{
    /** @var  object repository */
    protected $repository;

    /** @var  string ENV DB_CONNECT */
    public $db_connect;

    public function __construct()
    {
        if(empty($this->db_connect))
            $this->db_connect= Config::get('database.default');
    }

    /**
     * Set repository
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository
     *
     * @return object
     */
    public function getRepository(){
        return $this->repository;
    }

    /**
     * Set db_connect
     *
     * @param $connect
     */
    public function setDBConnect($connect){
        $this->db_connect = $connect;
        if(method_exists($this,'switchRepository')){
            $this->switchRepository();
        }
    }

    /**
     * Get db_connect
     *
     * @return string
     */
    public function getDBConnect(){
        return $this->db_connect;
    }
}
