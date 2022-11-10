<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingAddress;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 2; $i++) {
            ShippingAddress::create([
                'user_id' => $i+1,
                'name' => $faker->streetAddress,
                'phone_number' => '081228090265',
                'address' => $faker->address,
                'city' => $faker->city,
            ]);
        }
    }
}
