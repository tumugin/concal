<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(\App\Models\AdminUser::class, function (Faker $faker) {
    return [
        'user_name' => $faker->unique()->userName,
        'name' => $faker->name,
        'password' => Hash::make('uju_macha_milk'),
        'email' => $faker->unique()->safeEmail,
        'user_privilege' => 'admin',
        'remember_token' => Str::random(10),
    ];
});
