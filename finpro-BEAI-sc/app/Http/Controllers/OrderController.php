<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create_order() {
        return "Halaman Create Order";
    }

    public function user_order() {
        return "Halaman User Order";
    }
}
