<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        factory(App\User::class, 100)->create();
        factory(App\DataRegister::class, 1000)->create();
        factory(App\SensorModule::class, 50)->create();
        factory(App\Room::class, 30)->create();
        factory(App\Sensor::class, 100)->create();

        $fieldName = [
            'temperatuur',
            'luchtvochtigheid',
            'methaan',
            'deur',
            'beweging', 
        ];
    
        $fieldType = [
            'integer',
            'integer',
            'integer',
            'boolean',
            'boolean',
        ];

        for ($y = 0; $y < 5; $y++) {
            DB::table('fields')->insert([
                'name' => $fieldName[$y],
                'type' => $fieldType[$y],
            ]);
        }
        
        for ($x = 0; $x < 100; $x++) {
            DB::table('room_user')->insert([
                'user_id' => $faker->numberBetween(1,100),
                'room_id' => $faker->numberBetween(1,30),
            ]);
        }

        DB::table('roles')->insert([
            'role' => 'default',
        ]);
        DB::table('roles')->insert([
            'role' => 'facilitor',
        ]);
        DB::table('roles')->insert([
            'role' => 'admin',
        ]);
    }
}
