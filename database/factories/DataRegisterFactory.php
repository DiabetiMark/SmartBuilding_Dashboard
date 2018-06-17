<?php

use Faker\Generator as Faker;

$factory->define(App\DataRegister::class, function (Faker $faker) {
    $field_id = $faker->numberBetween(1,5);
    if($field_id == 1){
        $value = $faker->numberBetween(20,30);
    }else if($field_id == 2){
        $value = $faker->numberBetween(0,100);
    }else if($field_id == 3){
        $value = $faker->numberBetween(0,100);
    }else if($field_id == 4){
        $value = $faker->numberBetween(0,1);
    }else if($field_id == 5){
        $value = $faker->numberBetween(0,1);

    }
    $sensor_id = $faker->numberBetween(1,100);

    $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
    
    return [
        'value' =>  $value,
        'sensor_id' => $sensor_id,
        'field_id' => $field_id,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});


