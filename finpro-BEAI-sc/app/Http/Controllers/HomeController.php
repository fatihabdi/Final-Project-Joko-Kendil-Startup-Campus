<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function get_banner(Request $request) {
        // return "Halaman Get Banner";
        return Auth::user();
    }

    public function get_category() {
        return "Halaaman Get Category";
    }
}
