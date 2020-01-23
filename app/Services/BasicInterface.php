<?php

namespace App\Services;

interface BasicInterface
{
    public function getDataById(int $id);

    public function getData();

    public function updateData(array $data, int $id);

    public function createData(array $data);

    public function deleteData(int $id);
}
