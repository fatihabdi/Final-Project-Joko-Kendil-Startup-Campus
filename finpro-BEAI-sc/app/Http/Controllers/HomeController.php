<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function get_banner() {
        return "Halaman Get Banner";
    }

    public function get_category() {
        return "Halaaman Get Category";
    }
}
