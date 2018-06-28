<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        factory(App\DataRegister::class, 5000)->create();
        factory(App\SensorModule::class, 50)->create();
        factory(App\Room::class, 30)->create();

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

        DB::table('users')->insert([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'name' => 'admin test',
            'email' => 'admin@aareon.nl',
            'phone' => '0612345678',
            'role_id' => '3',
        ]);

        DB::table('users')->insert([
            'username' => 'user',
            'password' => Hash::make('user123'),
            'name' => 'user test',
            'email' => 'user@aareon.nl',
            'phone' => '0612345678',
            'role_id' => '1',
        ]);

        $sensorName = array();
        for($index = 0; $index < 2 ; $index++){
            array_push($sensorName, 'temperatuur' . $index);
            array_push($sensorName, 'luchtvochtigheid' . $index);
            array_push($sensorName, 'deur' . $index);
            array_push($sensorName, 'raam' . $index);
            array_push($sensorName, 'beweging' . $index);
        }

        for($index2 = 0; $index2 < 50; $index2++){
            for($index = 0; $index < count($sensorName); $index++){
                $data = [
                    'name' => $sensorName[$index],
                    'sensorModule_id' => $index2,
                ];
                DB::table('sensors')->insert($data);
            }
        }
    }
}
