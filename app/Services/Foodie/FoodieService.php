<?php

namespace App\Services\Foodie;

use Illuminate\Http\Request;

class FoodieService extends Service implements FoodieInterface
{
    /** @var object foodie repository */
    protected $foodieRepository;

    /** @var array The columns which can be  accepted for update and insert */
    protected $acceptedParameters = ['name', 'city', 'detail', 'status', 'tel', 'opentime'];

    public function __construct()
    {
        parent::__construct();

        $this->switchRepository();
    }

    /**
     * Get restaurantRepository
     *
     */
    protected function switchRepository()
    {
        FoodieFactory::create($this->db_connect);
        $this->setRepository(FoodieFactory::create($this->db_connect));

        $this->foodieRepository = $this->getRepository();
    }

    /**
     * Get single date by id
     *
     * @param $id
     * @return mixed
     */
    public function getDataById(int $id)
    {
        return $this->foodieRepository->getDataById($id);
    }

    /**
     * Get all data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->foodieRepository->getData();
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
        $updateData = $request->only($this->acceptedParameters); //array
        if (empty($updateData['name'])) {
            throw new \Exception('There is no name');
        }

        return $this->foodieRepository->updateData($updateData, $id);
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
        $createData = $request->only($this->acceptedParameters); //array
        if (empty($createData['name'])) {
            throw new \Exception('There is no name');
        }


        return $this->foodieRepository->createData($createData);
    }

    /**
     * Remove data by id
     *
     * @param int $id
     * @return mixed
     */
    public function deleteData(int $id)
    {
        return $this->foodieRepository->deleteData($id);
    }

    /**
     * Get $acceptedParameters
     *
     * @return array
     */
    public function getAcceptedParameters()
    {
        return $this->acceptedParameters;
    }
}
