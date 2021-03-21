<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\User $owner */
        $owner = factory(\App\User::class)->create([
            'email' => 'owner@owner.com'
        ]);

        $owner->assignRole('owner');

        /** @var \App\User $driver */
        $driver = factory(\App\User::class)->create([
            'email' => 'driver@driver.com'
        ]);

        $driver->assignRole('driver');

        factory(\App\Pet::class, 3)->create(['user_id' => $owner->id]);
        factory(\App\Transport::class, 15)->create(['user_id' => $owner->id])->each(function ($u) use ($owner) {
            $u->addPet($owner->pets[0]);
        });
        factory(\App\Transport::class, 25)->create(['user_id' => $owner->id])->each(function ($u) use ($owner) {
            $u->addPet($owner->pets[0]);
            $u->addPet($owner->pets[1]);
        });

        factory(\App\DriverDetails::class)->create(['user_id' => $driver->id]);
        factory(\App\VehiclePhoto::class)->create(['user_id' => $driver->id]);

    }
}
