<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Raihan Parluangan',
            'email' => 'raihan@gmail.com',
            'phone_number' => '081380737126',
            'password' => Hash::make('password1234'),
            'is_admin' => 1
        ]);
    }
}
