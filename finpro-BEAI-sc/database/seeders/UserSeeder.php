<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Support\Facades\Auth;
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
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone_number' => '12463546256',
            'password' => Hash::make('password1234'),
            'role' => "seller",
            'token' => 'lkjdfkajsdfjlasf'
        ]);
        $token = Auth::login($user);
        User::where('id', 1)->update(['token' => $token]);
        Balance::create([
            'user_id' => 1,
            'balance' => 1500000
        ]);
        $user = User::create([
            'name' => 'Raihan Parluangan',
            'email' => 'raihan@gmail.com',
            'phone_number' => '081380737126',
            'password' => Hash::make('password1234'),
            'role' => "buyer",
            'token' => 'lkjdfkajsdfjlasf'
        ]);
        $token = Auth::login($user);
        User::where('id', 2)->update(['token' => $token]);
        Balance::create([
            'user_id' => 2,
            'balance' => 1000000
        ]);
    }
}
