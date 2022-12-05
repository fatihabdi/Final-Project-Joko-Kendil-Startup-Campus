<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Balance;
use App\Models\ShippingAddress;
use App\Models\ProductOrder;
use App\Models\ProductImages;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function create_order(Request $request) {
        $order = Order::where('users_id', Auth::user()->id)->where('status', 'Not Complete')->get()->first();
        $balance = Balance::where('user_id', Auth::user()->id)->get()->first();
        $shipping_price = 0;
        if ($request->input('shipping_method') == "Regular" or $request->input('shipping_method') == "regular") {
            if ($order->total < 200) {
                $shipping_price = 0.15 * $order->total;
            } else {
                $shipping_price = 0.2 * $order->total;
            }
        } else if ($request->input('shipping_method') == "Next day" or $request->input('shipping_method') == "next day") {
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

        $cart = Cart::where('user_id', Auth::user()->id)->where('is_deleted', 0)->get()->all();
        foreach ($cart as $item) {
            ProductOrder::create([
                'user_id' => Auth::user()->id,
                'cart_id' => $item->id,
                'order_id' => $order->id
            ]);
            $item->is_deleted = 1;
            $item->save();
        }

        $order->status = "Complete";
        $order->shipping_method = $request->input('shipping_method');
        $order->shipping_price = $shipping_price;
        $order->created_at = Carbon::now();
        $order->save();
        

        return response()->json([
            'message' => 'Order success'
        ]);
    }

    public function user_order() {
        $orders = Order::where('users_id', Auth::user()->id)
            ->where('orders.status', 'Complete')
            ->get()->all();
        $data=[];
        foreach ($orders as $order) {
            $dataProduct=[];
            $products = Order::join('product_order', 'orders.id', '=', 'product_order.order_id')
                ->join('carts', 'product_order.cart_id', '=', 'carts.id')
                ->join('products','carts.product_id', '=', 'products.id')
                ->select('products.id as product_id', 'quantity', 'size', 'price', 'product_name')
                ->where('orders.id', $order->id)
                ->get()->all();
                foreach ($products as $item){
                    $img = ProductImages::where('product_id', $item->product_id)->get()->first();
                    $json = response()->json([
                        'id' => $item->product_id,
                        'details' => [
                            'quantity' => $item->quantity,
                            'size' => $item->size,
                        ],
                        'price' => $item->price * $item->quantity,
                        'image' => Storage::url($img->image_title),
                    'name' => $item->product_name,
                ]);
                array_push($dataProduct,$json->original);
            }
            $shipping_address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
            $address = response()->json([
                "name" => $shipping_address->name,
                "phone_number" => $shipping_address->phone_number,
                "address" => $shipping_address->address,
                "city" => $shipping_address->city
            ]);
            $json = response()->json([
                'id' => $order->id,
                'created_at' => $order->created_at,
                'products' => $dataProduct,
                'shipping_method' => $order->shipping_method,
                'shipping_address' => $address->original
            ]);
            array_push($data,$json->original);
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
