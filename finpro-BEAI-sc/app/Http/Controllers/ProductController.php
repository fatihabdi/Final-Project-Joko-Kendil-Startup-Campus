<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImages;
use App\Models\ShippingAddress;
use Carbon\Carbon;
use Throwable;
use Exception;

class ProductController extends Controller
{
    public function get_product_list(Request $request)
    {
        try {
            $query = explode('&', $request->server->get('QUERY_STRING'));
            $params = [];
            if ($query[0] != "") {
                foreach ($query as $param) {
                    if (strpos($param, '=') === false) $param += '=';
                    list($name, $value) = explode('=', $param,2);
                    $expValue = explode(',', $value);
                    foreach ($expValue as $value) {
                        $params[urldecode($name)][] = urldecode($value);
                    }
                }
            }
            $produk = [];
            $products = Product::select('products.id', 'product_name', 'price', 'category', 'condition')
                ->where('products.active', 1)
                ->get()->all();
            foreach ($products as $product) {
                $image = ProductImages::join('products', 'product_image.product_id', '=', 'products.id')
                    ->where('products.id', $product->id)->get()->first();
                $json = response()->json([
                    'id' => $product->id,
                    'title' => $product->product_name,
                    'price' => $product->price,
                    'category' => $product->category,
                    'condition' => $product->condition,
                    'image' => Storage::url($image->image_title)
                ]);
                array_push($produk, $json->original);
            }

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
        $categories = Category::where('active', 1)->get()->all();
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

    public function search_product(Request $request)
    {
        $imagefile = $request->image;
        $categories = Category::get()->all();
        $data = [];
        foreach($categories as $category) {
            $json = response()->json([
                'id' => $category->id,
            ]);
            array_push($data, $json->original);
        };
        return new Response([
            'data' => $data
        ]);
    }

    public function add_to_cart(Request $request) {
        try {
            $item = Cart::firstOrNew([
                'user_id' => Auth::user()->id,
                'product_id' => $request->input('id'),
                'size' => $request->input('size'),
                'is_deleted' => 0
            ]);
            $item->quantity += $request->input('quantity');
            $item->save();
            
            $address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
            $allItem = Cart::join('products', 'carts.product_id', '=', 'products.id')
                ->select('products.price', 'quantity')
                ->where('carts.is_deleted', 0)
                ->get()->all();
            $total = 0;
            foreach($allItem as $item) {
                $total += $item->price*$item->quantity;
            }

            $order = Order::firstOrNew([
                'users_id' => Auth::user()->id,
                'status' => 'Not Complete'
            ]);
            $order->shipping_address_id = $address->id;
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
            $images = ProductImages::join('products', 'product_image.product_id', '=', 'products.id')
                ->select('image_title')
                ->where('product_id', $product->id)
                ->get()->all();
            $imageUrl = [];
            foreach ($images as $image) {
                array_push($imageUrl, Storage::url($image->image_title));
            }
            $json = response()->json([
                'id' => $product->id,
                'title' => $product->product_name,
                'size' => ['S', 'M', 'L'],
                'product_detail' => $product->description,
                'price' => $product->price,
                'images_url' => $imageUrl,
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
