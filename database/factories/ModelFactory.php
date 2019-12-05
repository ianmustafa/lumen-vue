<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
// $factory->define(App\Models\User::class, function (Faker\Generator $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->email,
//     ];
// });

$factory->define(App\Models\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(2),
        'description' => $faker->sentence(6),
        'is_done' => $faker->boolean(),
    ];
});
