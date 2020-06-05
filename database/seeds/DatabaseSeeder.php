<?php

use Illuminate\Database\Seeder;
use App\Item;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            Item::create([
                'name'=> $faker->catchPhrase(),
                'description'=> $faker->sentence(6,true),
                'qrcode' => 'https://chart.googleapis.com/chart?cht=qr&chs=150&chl=http://itemsmsapi.test:8080/items/'.$i.'&choe=UTF-8',
                'imgItem' => url('/') . '/itemsphotos/itemdefault.png',
                'laboratory_id' => $faker->numberBetween(1,4)
            ]);
        }

    }
}
