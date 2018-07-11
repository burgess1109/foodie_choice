<?php

namespace App\Services\Restaurant;

use App;
use Illuminate\Http\Request;

class RestaurantService implements RestaurantInterface
{
    /** @var object restaurant repository */
    protected $restaurantRepository;

    /** @var  string ENV DB_CONNECT */
    protected $db_connect;

    /** @var array The columns which can be  accepted for update and insert */
    protected $acceptedParameters = ['name', 'city', 'detail', 'status', 'tel', 'opentime'];

    public function __construct()
    {
        $this->db_connect = config('database.default');
        $this->restaurantRepository = app('restaurant.repository');
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

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById(int $id)
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
    public function updateData(Request $request, int $id)
    {
        $input = $request->only($this->acceptedParameters); //array
        if (empty($input['name'])) {
            throw new \Exception('There is no name', 400);
        }

        return $this->restaurantRepository->updateData($input, $id);
    }

    /**
     * Insert a new data
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function createData(Request $request)
    {
        $input = $request->only($this->acceptedParameters); //array
        if (empty($input['name'])) {
            throw new \Exception('There is no name', 400);
        }

        return $this->restaurantRepository->createData($input);
    }

    /**
     * Remove data by id
     *
     * @param int $id
     * @return mixed
     */
    public function deleteData(int $id)
    {
        return $this->restaurantRepository->deleteData($id);
    }

    /**
     * Get $acceptedParameters
     *
     * @return array acceptedParameters
     */
    public function getAcceptedParameters()
    {
        return $this->acceptedParameters;
    }
}
