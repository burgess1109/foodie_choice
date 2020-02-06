<?php

namespace App\Repositories;

trait DataAssemble
{
    /**
     * @param string $city
     * @param string $detail
     * @return string $address
     */
    protected function setAddress($city, $detail)
    {
        $address = [];

        if (!empty($city)) {
            $address[0]['city'] = $city;
        }

        if (!empty($detail)) {
            $address[0]['detail'] = $detail;
        }

        $address = json_encode($address);

        return $address;
    }
}
