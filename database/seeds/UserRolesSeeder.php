<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'admin',
            'owner',
            'driver'
        ];

        foreach ($roles as $role) {
            \App\Role::create([
                'name' => $role
            ]);
        }
    }
}
