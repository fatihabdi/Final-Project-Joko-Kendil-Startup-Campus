<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Throwable;
use Exception;

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

    public function search_product()
    {
        return "Halaman Search Product";
    }

    public function add_to_cart(Request $request) {
        try {
            $item = Cart::where('product_id', $request->input('id'))
                ->where('size', $request->input('size'))
                ->get('quantity')->first();
            if ($item == null) {
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->input('id'),
                    'quantity' => $request->input('quantity'),
                    'size' => $request->input('size'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                Cart::where('product_id', $request->input('id'))
                    ->where('size', $request->input('size'))
                    ->update([
                        'quantity' => ($item->quantity + $request->input('quantity')),
                        'updated_at' => Carbon::now()
                    ]);
            }
            return response([
                'message' => 'Item added to cart'
            ]);
        } catch (Throwable $th) {
            return response([
                'message' => 'Failed because '.$th->getMessage()
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'Error because '.$e->getMessage()
            ]);
        }
    }

    public function get_product_detail($id)
    {
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
}
