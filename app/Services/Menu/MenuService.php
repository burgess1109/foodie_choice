<?php

namespace App\Services\Menu;

class MenuService implements MenuInterface
{
    protected $data = 'Menu Data';

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
