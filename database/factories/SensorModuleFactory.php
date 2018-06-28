<?php

use Faker\Generator as Faker;

$factory->define(App\SensorModule::class, function (Faker $faker) {
    return [
        'moduleName' => $faker->word,
        'room_id' => $faker->numberBetween(1,30),
    ];
});
