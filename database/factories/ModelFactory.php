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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Eloquent\Address\Request::class, function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->title,
        'description' => $faker->paragraph,
        'address_type' => $faker->boolean ? 'cvs' : 'standard',
        'token' => $faker->uuid
    ];
});

$factory->state(App\Eloquent\Address\Request::class, 'cvs', function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->title,
        'description' => $faker->paragraph,
        'address_type' => 'cvs',
        'token' => $faker->uuid
    ];
});

$factory->state(App\Eloquent\Address\Request::class, 'standard', function (Faker\Generator $faker) {
    $faker->seed(time() . random_int(1, 99999));
    return [
        'title' => $faker->title,
        'description' => $faker->paragraph,
        'address_type' => 'standard',
        'token' => $faker->uuid
    ];
});
