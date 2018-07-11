<?php

namespace App\Http\Controllers;

use App\Facades\Restaurant;
use App\Services\Menu\MenuService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class RestaurantController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function menu()
    {
        try {
            return response()->json(app(MenuService::class)->getData());
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
            return response()->json(Restaurant::getDataById($id));
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
            return response()->json(Restaurant::getDataById($id));
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
            return response()->json(Restaurant::updateData($request, $id));
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
            return response()->json(Restaurant::createData($request));
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
            return response()->json(Restaurant::deleteData($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }
}
