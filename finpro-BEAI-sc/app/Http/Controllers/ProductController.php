<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get_product_list() {
        return "Halaman Get Product List";
    }

    public function categories() {
        return "Halaman Categories";
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
