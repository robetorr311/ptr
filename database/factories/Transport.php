<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Transport::class, function (Faker $faker) {

    return [
        'status' => \App\Transport::STATUS_OPEN,
        'user_id' => 1,
        'arrival_date' => $faker->dateTime,
        'arrival_type' => 'before',
        'biddable' => 1,
        'departure_address' => $faker->address,
        'departure_date' => $faker->dateTime,
        'departure_lat' => $faker->latitude,
        'departure_lng' => $faker->longitude,
        'departure_type' => 'on',
        'destination_address' => $faker->address,
        'destination_lat' => $faker->latitude,
        'destination_lng' => $faker->longitude,
        'first_bid_buy' => 0,
        'budget' => random_int(200, 5000)
    ];
});
