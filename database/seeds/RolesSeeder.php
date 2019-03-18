<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Empty the roles table
        DB::table('roles')->delete();

        $roles = [
            [
                'id' => 1,
                'name' => 'administrator',
            ],
            [
                'id' => 2,
                'name' => 'attendee',
            ]
        ];
        DB::table('roles')->insert($roles);
    }
}
