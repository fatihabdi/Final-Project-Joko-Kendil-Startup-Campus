<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function get_user_cart() {
        return "Halaman User Cart";
    }

    public function delete_item_cart($id) {
        Cart::where('product_id',$id)->where('user_id', Auth::user()->id)->update(['is_deleted'=>1]);
        return "Halaman Delete Item Cart";
    }

    public function get_user_shipping_address() {
        return "Halaman User Shipping Address";
    }
}
