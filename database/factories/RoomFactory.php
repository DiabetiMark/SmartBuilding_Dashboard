<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
        'roomName' => $faker->word,
        'roomDescription' => $faker->paragraph(4, true),
    ];
});
