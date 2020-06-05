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

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name'=> $faker->catchPhrase(),
        'description'=> $faker->sentence(6,true),
        'qrcode' => 'https://chart.googleapis.com/chart?cht=qr&chs=150&chl=http://itemsmsapi.test:8080/items/&choe=UTF-8',
        'imgItem' => url('/') . '/itemsphotos/itemdefault.png',
        'laboratory_id' => $faker->numberBetween(1,3)
    ];
});
