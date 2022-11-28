<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Balance;

class OrderController extends Controller
{
    public function create_order(Request $request) {
        $order = Order::where('users_id', Auth::user()->id)->get()->first();
        $balance = Balance::where('user_id', Auth::user()->id)->get()->first();
        $shipping_price = 0;
        if ($request->input('shipping_method') == "Regular" or $request->input('shipping_method') == "regular") {
            if ($order->total < 200) {
                $shipping_price = 0.15 * $order->total;
            } else {
                $shipping_price = 0.2 * $order->total;
            }
        } else if ($request->input('shipping_method') == "Same day" or $request->input('shipping_method') == "same day") {
            if ($order->total < 300) {
                $shipping_price = 0.2 * $order->total;
            } else {
                $shipping_price = 0.25 * $order->total;
            }
        } else {
            return response()->json([
                'message' => "Shipping method is not valid"
            ]);
        }
        $total = $order->total + $shipping_price;

        if ($balance->balance < $total) {
            return response()->json([
                'message' => "Balance lower than price"
            ]);
        }

        $balance->balance -= $total;
        $balance->save();
        Cart::where('user_id', Auth::user()->id)->delete();
        $order->status = "Complete";
        $order->shipping_method = $request->input('shipping_method');
        $order->shipping_price = $shipping_price;
        $order->save();

        return response()->json([
            'message' => 'Order success'
        ]);
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
