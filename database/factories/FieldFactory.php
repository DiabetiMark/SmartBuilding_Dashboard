<?php

use Faker\Generator as Faker;



$factory->define(App\Field::class, function (Faker $faker) {
    $fieldName = [
        'temperatuur',
        'luchtvochtigheid',
        'methaan',
    ];

    $fieldType = [
        'integer',
        'string',
        'json',
    ];
    
    return [
        'fieldName' => $datatypes[$faker->numberBetween(0, (count($fieldName)-1))],
        'fieldType' => $datatypes[$faker->numberBetween(0, (count($fieldType)-1))],
    ];
});
