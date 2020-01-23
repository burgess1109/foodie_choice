<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getDataById(int $id)
 * @method static mixed getData()
 * @method static mixed updateData(array $data, int $id)
 * @method static mixed createData(array $data)
 * @method static mixed deleteData(int $id)
 *
 * @see \App\Services\Restaurant\Service
 */
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
