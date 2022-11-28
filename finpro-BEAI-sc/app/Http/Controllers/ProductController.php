<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\ShippingAddress;
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
                $json = response()->json([
                    'id' => $product->id,
                    'title' => $product->product_name,
                    'description' => $product->description,
                    'is_new' => $product->is_new,
                    'category' => $product->category,
                    'price' => $product->price,
                ]);
                array_push($produk, $json->original);
            };
            return response()->json([
                'status' => 'success',
                'data' => $produk
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function categories() {
        $categories = Category::get()->all();
        $data = [];
        foreach($categories as $category) {
            $json = response()->json([
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
            $item = Cart::firstOrNew([
                'user_id' => Auth::user()->id,
                'product_id' => $request->input('id'),
                'size' => $request->input('size')
            ]);
            $item->quantity += $request->input('quantity');
            $item->save();
            
            $address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
            $allItem = Cart::join('products', 'carts.product_id', '=', 'products.id')
                ->select('products.price', 'quantity')
                ->get()->all();
            $total = 0;
            foreach($allItem as $item) {
                $total += $item->price*$item->quantity;
            }

            $order = Order::firstOrNew([
                'users_id' => Auth::user()->id
            ]);
            $order->shipping_address_id = $address->id;
            $order->status = "Not Complete";
            $order->total = $total;
            $order->save();

            return response()->json([
                'message' => 'Item added to cart'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Failed because '.$th->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error because '.$e->getMessage()
            ]);
        }
    }

    public function get_product_detail($id)
    {
        try {
            $product = Product::join('categories', 'products.category', '=', 'categories.id')
                ->select('products.id', 'product_name', 'description', 'price', 'category', 'category_name')
                ->where('products.id', $id)->get()->first();
            $json = response()->json([
                'id' => $product->id,
                'title' => $product->product_name,
                'size' => ['S', 'M', 'L'],
                'product_detail' => $product->description,
                'price' => $product->price,
                'images_url' => "Not found",
                'category_id' => $product->category,
                'category_name' => $product->category_name
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $json->original
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
