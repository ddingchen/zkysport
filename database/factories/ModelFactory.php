<?php

use Carbon\Carbon;

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
    return [
        'open_id' => str_random(10),
        'nickname' => $faker->name,
        'head_image' => $faker->imageUrl(130, 130),
    ];
});

$factory->define(App\Activity::class, function (Faker\Generator $faker) {
    $date = Carbon::instance($faker->dateTimeThisMonth());
    $dateFrom = $date->copy()->subDays(7);
    $dateTo = $date->copy()->addDays(7);
    $expired = $dateTo->diffInDays() > 0;
    return [
        'title' => $faker->name,
        'banner' => $faker->imageUrl(650, 300),
        'desc' => $faker->paragraph(),
        'ticket_price' => 0.01,
        'require_information' => true,
        'start_from' => $date->copy()->subDays(7),
        'end_to' => $date->copy()->addDays(7),
        'expired' => $faker->boolean,
    ];
});

$factory->define(App\Information::class, function (Faker\Generator $faker) {
    return [
        'user_id' => App\User::all()->random()->id,
        'paid' => $faker->boolean,
    ];
});

$factory->define(App\DetailInformation::class, function (Faker\Generator $faker) {
    return [
        'realname' => $faker->name,
        'tel' => $faker->name,
        'sub_district_id' => App\SubDistrict::all()->random()->id,
        'housing_estate_id' => App\HousingEstate::all()->random()->id,
    ];
});

$factory->define(App\SubDistrict::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\HousingEstate::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
