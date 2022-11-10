<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Exception;
use Throwable;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function get_product_list() {
        return "Halaman Get Product List";
    }

    public function categories() {
        try {
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
        } catch (Throwable $th) {
            return response([
                'message' => 'Error'
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'Error'
            ]);
        }
    }

    public function search_product() {
        return "Halaman Search Product";
    }

    public function get_product_detail($id) {
        $product = Product::join('categories', 'products.category', '=', 'categories.id')
            ->select('products.id as pid', 'product_name', 'description', 'price', 'category', 'category_name')
            ->where('products.id', $id)
            ->get()->first();
        return response([
            'id' => $product->pid,
            'title' => $product->product_name,
            'size' => ["S", "M", "L"],
            'product_detail' => $product->description,
            'price' => $product->price,
            'images_url' => 'Not Found',
            'category_id' => $product->category,
            'category_name' =>$product->category_name
        ]);
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
}
