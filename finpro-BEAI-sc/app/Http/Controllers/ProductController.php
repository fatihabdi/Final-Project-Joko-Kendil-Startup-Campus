<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function get_product_list()
    {
        try {
            $products = Product::get()->all();
            $produk = [];
            foreach ($products as $product) {
                $json = new Response([
                    'id' => $product->id,
                    'title' => $product->product_name,
                    'description' => $product->description,
                    'is_new' => $product->is_new,
                    'category' => $product->category,
                    'price' => $product->price,
                ]);
                array_push($produk, $json->original);
            };
            return new Response([
                'status' => 'success',
                'data' => $produk
            ]);
        } catch (\Throwable $th) {
            return new Response([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return new Response([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function categories()
    {
        return "Halaman Categories";
    }

    public function search_product()
    {
        return "Halaman Search Product";
    }

    public function get_product_detail($id)
    {
        // $route = Routes::find($request->input('route_id'));

        // if ($route == null) {
        //     return new Response([
        //         'status' => 'failed',
        //         'code' => 404,
        //         'message' => 'Product not found',
        //     ], 404);
        // }
        try {
            $product = Product::find($id);
            $json = new Response([
                'id' => $product->id,
                'title' => $product->product_name,
                'description' => $product->description,
                'is_new' => $product->is_new,
                'category' => $product->category,
                'price' => $product->price,
            ]);
            return new Response([
                'status' => 'success',
                'data' => $json->original
            ]);
        } catch (\Throwable $th) {
            return new Response([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return new Response([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function add_to_cart()
    {
        return "Halaman Add To Cart";
    }
}
