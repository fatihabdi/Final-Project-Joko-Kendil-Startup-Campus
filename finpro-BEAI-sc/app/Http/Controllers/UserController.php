<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get_user_detail() {
        return "Halaman User Detail";
    }

    public function change_shipping_address() {
        return "Halaman Change Shipping Address";
    }

    public function top_up() {
        return "Halaman Top Up";
    }

    public function get_balance() {
        return "Halaman Balance";
    }
}
