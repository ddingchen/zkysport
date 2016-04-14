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
        // 'nickname' => $faker->name,
        // 'head_image' => $faker->imageUrl(130, 130),
    ];
});

$factory->define(App\Activity::class, function (Faker\Generator $faker) {
    $date = Carbon::instance($faker->dateTimeThisMonth());
    $dateFrom = $date->copy()->subDays(15);
    $dateTo = $date->copy()->addDays(20);
    $expired = $dateTo->diffInSeconds(Carbon::now(), false) > 0;
    $published = Carbon::now()->gt($dateFrom);
    return [
        'title' => $faker->name,
        // 'banner' => 'RGeyCVRlGIxj7WEPWfYfTt380SZTvAXn.jpeg', //$faker->imageUrl(650, 300),
        'desc' => $faker->paragraph(),
        'ticket_price' => 0.01,
        'require_information' => true,
        'start_from' => $dateFrom,
        'end_to' => $dateTo,
        'expired' => $expired,
        'published' => $published,
    ];
});

$factory->define(App\Information::class, function (Faker\Generator $faker) {
    return [
        'user_id' => App\User::all()->random()->id,
        // 'payment_id' => null,
    ];
});

$factory->define(App\DetailInformation::class, function (Faker\Generator $faker) {
    return [
        'realname' => $faker->name,
        'tel' => $faker->phoneNumber,
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
        'sub_district_id' => App\SubDistrict::all()->random()->id,
        'name' => $faker->name,
    ];
});
