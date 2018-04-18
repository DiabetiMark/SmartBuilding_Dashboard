<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create();
        factory(App\Field::class, 100)->create();
        factory(App\DataRegister::class, 100)->create();
        factory(App\SensorModule::class, 100)->create();
        factory(App\Room::class, 100)->create();

        for ($x = 1; $x < 10; $x++) {
            for ($y = 1; $y < 5; $y++) {
                DB::table('room_user')->insert([
                    'user_id' => $x,
                    'room_id' => $y,
                ]);
            }
        }

        for ($x = 1; $x < 10; $x++) {
            for ($y = 1; $y < 5; $y++) {
                DB::table('room_sensor_module')->insert([
                    'sensor_module_id' => $x,
                    'room_id' => $y,
                ]);
            }
        }

        for ($x = 1; $x < 10; $x++) {
            for ($y = 1; $y < 5; $y++) {
                DB::table('data_register_sensor_module')->insert([
                    'field_id' => 1,
                    'sensor_module_id' => $x,
                    'data_register_id' => $y,
                ]);
            }
        }
    }
}
