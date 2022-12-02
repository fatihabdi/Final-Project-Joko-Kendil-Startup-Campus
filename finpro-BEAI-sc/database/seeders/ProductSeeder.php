<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i = 1; $i < 30; $i++) {
            $cat = $faker->randomElement([1, 2, 3, 4, 5]);
            $con = $faker->randomElement(["new", "used"]);
            Product::create([
                'product_name' => "Product " . $i,
                'description' => "Lorem ipsum",
                'condition' => $con,
                'category' => $cat,
                'price' => $faker->numberBetween(1000, 10000),
                'active' => 1
            ]);
        };
    }
}
