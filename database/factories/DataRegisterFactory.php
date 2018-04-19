<?php

use Faker\Generator as Faker;

$factory->define(App\DataRegister::class, function (Faker $faker) {
    return [
        'value' => $faker->numberBetween(0,1000),
    ];
});
