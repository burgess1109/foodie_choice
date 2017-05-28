<?php

namespace App\Services;

class RestaurantService extends Service
{
    /** @var restaurant repository */
    protected $restaurantRepository;

    protected $acceptedParameters = ['name','city','detail', 'status', 'tel','opentime'];

    public function __construct()
    {
        parent::__construct();

        $this->switchRepository();

        return $this->restaurantRepository;
    }

    protected function switchRepository()
    {
        RestaurantFactory::Create($this->db_connect);
        $this->setRepository(RestaurantFactory::Create($this->db_connect));

        $this->restaurantRepository = $this->getRepository();
    }

    public function getDataById($id)
    {
        return $this->restaurantRepository->getDataById($id);
    }

    public function getData()
    {
        return $this->restaurantRepository->getData();
    }

    public function updateData($request,$id)
    {
        $updateData=$request->only($this->acceptedParameters); //array
        if(empty($updateData['name']))
            throw new \Exception('There is no name');

        return $this->restaurantRepository->updateData($updateData,$id);
    }

    public function createData($request)
    {
        $createData=$request->only($this->acceptedParameters); //array
        if(empty($createData['name']))
            throw new \Exception('There is no name');


        return $this->restaurantRepository->createData($createData);
    }

    public function deleteData($id)
    {
        return $this->restaurantRepository->deleteData($id);
    }

    public function getAcceptedParameters()
    {
        return $this->acceptedParameters;
    }

}
