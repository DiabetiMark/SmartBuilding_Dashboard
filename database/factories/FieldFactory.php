<?php

use Faker\Generator as Faker;



$factory->define(App\Field::class, function (Faker $faker) {
    $datatypes = [
        'temperatuur',
        'luchtvochtigheid',
        'methaan',
    ];
    
    return [
        'fieldName' => $datatypes[$faker->numberBetween(0, (count($datatypes)-1))],
    ];
});
