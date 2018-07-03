<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Restaurant extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // 對應 RestaurantServiceProvider bind name
        return 'restaurant';
    }
}
