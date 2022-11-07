<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function get_product_list() {
        return "Halaman Get Product List";
    }

    public function categories() {
        $categories = Category::get()->all();
        $data = [];
        foreach($categories as $category) {
            $json = new Response([
                'id' => $category->id,
                'title' => $category->category_name
            ]);
            array_push($data, $json->original);
        };
        return new Response([
            'data' => $data
        ]);
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
