<?php

namespace App\Http\Controllers;

use App\Services\Foodie\FoodieInterface;
use App\Services\Menu\MenuInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FoodieController extends BaseController
{
    /** @var object FoodieService */
    protected $foodieService;

    /** @var object MenuService */
    protected $menuService;

    /**
     * FoodieController constructor.
     *
     * @param FoodieInterface $foodieService
     * @param MenuInterface $menuService
     */
    public function __construct(FoodieInterface $foodieService, MenuInterface $menuService)
    {
        $this->foodieService = $foodieService;
        $this->menuService = $menuService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_encode($this->foodieService->getData());
    }

    /**
     * Display menu list.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu()
    {
        return json_encode($this->menuService->getData());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return json_encode($this->foodieService->getDataById((int)$id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return json_encode($this->foodieService->getDataById((int)$id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        return $this->foodieService->updateData($request, (int)$id);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return json_encode($this->foodieService->createData($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->foodieService->deleteData((int)$id);
    }
}
