<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Faker\Factory as Faker;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $role = ['seller', 'buyer'];
        for ($i = 0; $i < 2; $i++) {
            Role::create([
                'role' => $role[$i]
            ]);
        }
    }
}
