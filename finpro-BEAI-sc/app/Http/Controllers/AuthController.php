<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function sign_up() {
        return "Halaman Sign Up";
    }

    public function sign_in(Request $request) {
        $user = User::where('email', $request->input('email'))->where('password', $request->input('password'));
    }
}
