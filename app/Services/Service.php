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

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository(){
        return $this->repository;
    }

    public function setDBConnect($connect){
        $this->db_connect = $connect;
        if(method_exists($this,'switchRepository')){
            $this->switchRepository();
        }
    }

    public function getDBConnect(){
        return $this->db_connect;
    }
}
