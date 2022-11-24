<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;

class OrderController extends Controller
{
    public function create_order() {
        return "Halaman Create Order";
    }

    public function user_order() {
        $products = Cart::join('product_image','carts.product_id','=','product_image.product_id')->join('products','carts.product_id','=','products.id')->where('user_id',[Auth::user()->id])->get();
        $dataProduct=[];
        foreach ($products as $item){
            $json=response()->json([
                'id' => $item->product_id,
                'details' => [
                    'quantity' => $item->quantity,
                    'size' => $item->size,
                ],
                'price' => $item->price * $item->quantity,
                'image' => $item->image_file,
                'name' => $item->image_title,
        ]);
            array_push($dataProduct,$json->original);
        }
        $orders = Order::join('shipping_address', 'shipping_address_id' , '=', 'shipping_address.id')->where('users_id',[Auth::user()->id])->where('status','1')->get();        $data=[];
        foreach ($orders as $item) {
            $json=response()->json([
                'id' => $item->id,
                'created_at' => $item->created_at,
                'products' => [$dataProduct],
                'shipping_method' => $item->shipping_method,
                'shipping_address' => $item->address,
        ]);
            array_push($data,$json->original);
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
