<?php

namespace App\Services\Foodie;

use Illuminate\Http\Request;

interface FoodieInterface
{
    public function getDataById(int $id);

    public function getData();

    public function updateData(Request $request, int $id);

    public function createData(Request $request);

    public function deleteData(int $id);
}
