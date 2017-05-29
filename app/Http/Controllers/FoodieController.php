<?php

namespace App\Http\Controllers;

use Config;
use DB;
use Illuminate\Routing\Controller as BaseController;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class FoodieController extends BaseController
{
    /** @var object RouletteService */
    protected $restaurantService;

    /**
     * RouletteController constructor.
     * @param RestaurantService $restaurantService
     */
    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //If you want to switch database, you can use "setDBConnect"
        //$this->restaurantService->setDBConnect('mongodb');
        return json_encode($this->restaurantService->getData());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return json_encode($this->restaurantService->getDataById((int)$id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return json_encode($this->restaurantService->getDataById((int)$id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->restaurantService->updateData($request,(int)$id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return json_encode($this->restaurantService->createData($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->restaurantService->deleteData((int)$id);
    }
}
