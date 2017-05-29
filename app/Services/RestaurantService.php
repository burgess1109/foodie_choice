<?php

namespace App\Services;

class RestaurantService extends Service
{
    /** @var object restaurant repository */
    protected $restaurantRepository;

    /** @var array The columns which can be  accepted for update and insert */
    protected $acceptedParameters = ['name','city','detail', 'status', 'tel','opentime'];

    public function __construct()
    {
        parent::__construct();

        $this->switchRepository();

        return $this->restaurantRepository;
    }

    /**
     * Get restaurantRepository
     *
     */
    protected function switchRepository()
    {
        RestaurantFactory::Create($this->db_connect);
        $this->setRepository(RestaurantFactory::Create($this->db_connect));

        $this->restaurantRepository = $this->getRepository();
    }

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        return $this->restaurantRepository->getDataById($id);
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->restaurantRepository->getData();
    }

    /**
     * update data by id
     *
     * @param $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateData($request,$id)
    {
        $updateData=$request->only($this->acceptedParameters); //array
        if(empty($updateData['name']))
            throw new \Exception('There is no name');

        return $this->restaurantRepository->updateData($updateData,$id);
    }

    /**
     * Insert a new data
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function createData($request)
    {
        $createData=$request->only($this->acceptedParameters); //array
        if(empty($createData['name']))
            throw new \Exception('There is no name');


        return $this->restaurantRepository->createData($createData);
    }

    /**
     * Remove data by id
     *
     * @param $id
     * @return mixed
     */
    public function deleteData($id)
    {
        return $this->restaurantRepository->deleteData($id);
    }

    /**
     * Get $acceptedParameters
     *
     * @return $this->acceptedParameters
     */
    public function getAcceptedParameters()
    {
        return $this->acceptedParameters;
    }

}
