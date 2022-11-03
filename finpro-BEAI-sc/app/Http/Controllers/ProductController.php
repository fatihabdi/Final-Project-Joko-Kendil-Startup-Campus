<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProductController extends Controller
{
    public function get_product_list() {
        return "Halaman Get Product List";
    }

    public function categories(Request $request) {
        $user = User::where('email', $request->input('email'))->where('password', $request->input('password'))->first();
        // dd($user);
        return $user;
    }

    public function search_product() {
        return "Halaman Search Product";
    }

    public function get_product_detail($id) {
        return "Halaman Detail Product " + $id;
    }

    public function add_to_cart() {
        return "Halaman Add To Cart";
    }
}
