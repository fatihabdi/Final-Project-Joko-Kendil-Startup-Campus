<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function sign_up() {
        return "Halaman Sign Up";
    }

    public function sign_in(Request $request) {
        $user = User::where('email', $request->input('email'))->where('password', $request->input('password'))->first();
        $detail_user = new Response([
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number
        ]);
        return new Response([
            'user_information' => $detail_user->original,
            'token' => $user->token,
            'message' => "Login success"
        ], 200);
    }
}
