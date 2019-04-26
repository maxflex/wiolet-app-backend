<?php

use App\Models\User\{User, UserPreference};
use App\Models\User\Enums\{BodyType, HairColor, EyeColor, Kids, Lives, Alcohol, Smoking};
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Geo\City;

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
    $gender = $faker->randomElement(['male', 'female']);
    return [
        'name' => $faker->name($gender),
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(Str::random(10)),
        'gender' => $gender,
        'birthdate' => $faker->dateTimeBetween('-30 years', 'now'),
        'about' => $faker->realText(),
        'phone' => '79001112233',
        'height' => $faker->numberBetween(160, 205),
        'weight' => $faker->numberBetween(45, 110),
        'city_id' => City::inRandomOrder()->value('id'),
        'body_type' => collect(BodyType::toArray())->random(),
        'hair_color' => collect(HairColor::toArray())->random(),
        'eye_color' => collect(EyeColor::toArray())->random(),
        'kids' => collect(Kids::toArray())->random(),
        'lives' => collect(Lives::toArray())->random(),
        'alcohol' => collect(Alcohol::toArray())->random(),
        'smoking' => collect(Smoking::toArray())->random(),
        'company' => $faker->company(),
        'occupation' => $faker->jobTitle(),
        'university' => collect([null, null, 'НИУ ВШЭ', 'МГУ', 'МГИМО', 'Архитектурный колледж'])->random(),
    ];
});

$factory->afterCreating(User::class, function($user, $faker) {
    $user->preferences()->update(factory(UserPreference::class)->make()->toArray());
});
