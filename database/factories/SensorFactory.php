<?php

use Faker\Generator as Faker;

$factory->define(App\Sensor::class, function (Faker $faker) {
    $sensorName = array();
    for($index = 0; $index < 2 ; $index++){
        array_push($sensorName, 'temperatuur' . $index);
        array_push($sensorName, 'luchtvochtigheid' . $index);
        array_push($sensorName, 'deur' . $index);
        array_push($sensorName, 'raam' . $index);
        array_push($sensorName, 'beweging' . $index);
    }
    return [
        'name' => $sensorName->numberBetween(0,5),
        'sensorModule_id' => $faker->numberBetween(1,50),
    ];
});
