<?php

namespace App\Http\Controllers;

use App\Services\BasicInterface;
use App\Services\Menu\MenuService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FoodieController extends BaseController
{
    /**
     * @var array The columns which can be accepted for update and insert
     */
    private $acceptedParameters = ['name', 'city', 'detail', 'status', 'tel', 'opentime'];

    /** @var BasicInterface */
    protected $foodieService;

    /** @var MenuService */
    protected $menuService;

    /**
     * FoodieController constructor.
     *
     * @param BasicInterface $foodieService
     * @param MenuService $menuService
     */
    public function __construct(BasicInterface $foodieService, MenuService $menuService)
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
        try {
            return response()->json($this->foodieService->getData());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Display menu list.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu()
    {
        try {
            return response()->json($this->menuService->getData());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            return response()->json($this->foodieService->getDataById($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            return response()->json($this->foodieService->getDataById($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
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
        try {
            $input = $request->only($this->acceptedParameters); //array
            if (empty($input['name'])) {
                throw new \Exception('There is no name', 400);
            }

            return response()->json($this->foodieService->updateData($input, $id));
        } catch (\Exception $e) {
            if (empty($e->getCode())) {
                return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
            } else {
                return response()->json(['error' => $e->getMessage()])->setStatusCode($e->getCode());
            }
        }
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
        try {
            $input = $request->only($this->acceptedParameters); //array
            if (empty($input['name'])) {
                throw new \Exception('There is no name', 400);
            }

            return response()->json($this->foodieService->createData($input));
        } catch (\Exception $e) {
            if (empty($e->getCode())) {
                return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
            } else {
                return response()->json(['error' => $e->getMessage()])->setStatusCode($e->getCode());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            return response()->json($this->foodieService->deleteData($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }
}
