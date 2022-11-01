<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get_user_cart() {
        return "Halaman User Cart";
    }

    public function delete_item_cart() {
        return "Halaman Delete Item Cart";
    }

    public function get_user_shipping_address() {
        return "Halaman User Shipping Address";
    }
}
