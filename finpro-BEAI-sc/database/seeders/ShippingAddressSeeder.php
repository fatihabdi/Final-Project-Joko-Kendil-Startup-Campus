<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingAddress;
use Faker\Factory as Faker;

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
        for ($i = 0; $i < 10; $i++) {
            ShippingAddress::create([
                'user_id' => 1,
                'name' => $faker->streetAddress,
                'phone_number' => "081234567890",
                'address' => $faker->address,
                'city' => $faker->city
            ]);
        };
    }
}
