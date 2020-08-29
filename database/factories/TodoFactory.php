<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Todo;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Todo::class, function (Faker $faker, $params) {
    $title = $faker->realText(20);
    return [
        'user_id' => $params['user_id'],
        'title' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->text
    ];
});
