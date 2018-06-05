<?php

use Faker\Generator as Faker;

$factory->define(App\Sensor::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'sensorModule_id' => $faker->numberBetween(1,50),
    ];
});
