<?php

use Faker\Generator as Faker;
use App\Models\User\UserPreference;

$factory->define(UserPreference::class, function (Faker $faker) {
    $ageFrom = $faker->numberBetween(18, 35);
    return [
        'age_from' => $ageFrom,
        'age_to' => $faker->numberBetween($ageFrom, $ageFrom + 15),
    ];
});
