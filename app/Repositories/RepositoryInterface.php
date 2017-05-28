<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function getDataById($id);

    public function getData();

    public function updateData($updateData,$id);

    public function createData($createData);

    public function deleteData($id);
}