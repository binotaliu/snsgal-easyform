<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Facades\Hash as Hash;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('password'),
        'remember_token' => '',
    ];
});

$factory->define(\App\Models\Shipment\AddressTicket::class, function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->company,
        'description' => $faker->paragraph,
        'address_type' => $faker->boolean ? 'cvs' : 'standard',
        'token' => $faker->uuid
    ];
});

$factory->state(\App\Models\Shipment\AddressTicket::class, 'cvs', function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->company,
        'description' => $faker->paragraph,
        'address_type' => 'cvs',
        'token' => $faker->uuid
    ];
});

$factory->state(\App\Models\Shipment\AddressTicket::class, 'standard', function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->company,
        'description' => $faker->paragraph,
        'address_type' => 'standard',
        'token' => $faker->uuid
    ];
});

$factory->define(App\Models\Procurement\Item\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'value' => $faker->randomFloat(2, 0, 100),
        'lower' => $faker->numberBetween(5, 20)
    ];
});

$factory->define(App\Models\Procurement\Ticket\ShipmentMethod\Japan::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(0, 999)
    ];
});

$factory->define(App\Models\Procurement\Ticket\ShipmentMethod\Local::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->boolean ? 'cvs' : 'standard',
        'price' => $faker->numberBetween(0, 999),
        'show' => $faker->boolean
    ];
});

$factory->define(App\Models\Procurement\Item\ExtraService::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomNumber(),
        'show' => $faker->boolean
    ];
});
