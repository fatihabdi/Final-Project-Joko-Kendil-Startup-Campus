<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function sign_up() {
        return "Halaman Sign Up";
    }

    public function sign_in(Request $request) {
        // $user = User::where('email', $request->input('email'))->where('password', $request->input('password'))->first();
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        
        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60*24);
        
        $detail_user = response([
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number
        ]);
        return response([
            'user_information' => $detail_user->original,
            'token' => $token,
            'message' => "Login success"
        ], 200)->withCookie($cookie);
    }
}
