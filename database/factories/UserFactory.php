<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'user_name' => $faker->unique()->userName,
        'name' => $faker->name,
        'password' => Hash::make('uju_macha_milk'),
        'email' => $faker->unique()->safeEmail,
        'user_privilege' => 'user',
        'remember_token' => Str::random(10),
    ];
});
