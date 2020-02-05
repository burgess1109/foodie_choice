<?php

namespace App\Http\Controllers;

use App\Facades\Restaurant;
use App\Services\Menu\MenuService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class RestaurantController extends BaseController
{
    /**
     * The columns which can be accepted for update and insert
     * @var array
     */
    private $acceptedParameters = ['name', 'city', 'detail', 'status', 'tel', 'opentime'];

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            return response()->json(Restaurant::getData());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Display menu list.
     *
     * @return JsonResponse
     */
    public function menu()
    {
        try {
            $response = [
                'class' => get_class($this),
                'data' => app(MenuService::class)->getData()
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            return response()->json(Restaurant::getDataById($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id)
    {
        try {
            return response()->json(Restaurant::getDataById($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $input = $request->only($this->acceptedParameters); //array
            if (empty($input['name'])) {
                throw new \Exception('There is no name', 400);
            }

            return response()->json(Restaurant::updateData($input, $id));
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
     * @return Factory|View
     */
    public function create()
    {
        return view('edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only($this->acceptedParameters); //array
            if (empty($input['name'])) {
                throw new \Exception('There is no name', 400);
            }

            return response()->json(Restaurant::createData($input));
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            return response()->json(Restaurant::deleteData($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }
}
