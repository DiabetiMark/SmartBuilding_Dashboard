<?php

use Faker\Generator as Faker;

$factory->define(App\DataRegister::class, function (Faker $faker) {
    return [
        'value' => $faker->numberBetween(0,100),
        'sensorModule_id' => $faker->numberBetween(1,5),
        'field_id' => $faker->numberBetween(1,2),
    ];
});


