<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function sign_up() {
        return "Halaman Sign Up";
    }

    public function sign_in() {
        return "Halaman Sign In";
    }
}
