<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        factory(App\DataRegister::class, 10)->create();
        factory(App\SensorModule::class, 5)->create();
        factory(App\Room::class, 10)->create();

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
        
        for ($x = 1; $x < 2; $x++) {
            for ($y = 1; $y < 5; $y++) {
                DB::table('room_user')->insert([
                    'user_id' => $x,
                    'room_id' => $y,
                ]);
            }
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
    }
}
