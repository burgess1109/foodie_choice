<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Mysql\Restaurant\Restaurant::class, function (Faker\Generator $faker) {
    return [
        'name' => "TEST0001",
        'tel' => '0' . rand(1, 9) . '-' . rand(20000000, 29999999),
    ];
});

$factory->define(App\Models\Mongo\Restaurant\Restaurant::class, function (Faker\Generator $faker) {
    return [
        'name' => "TEST0001",
        'tel' => '0' . rand(1, 9) . '-' . rand(20000000, 29999999),
    ];
});
