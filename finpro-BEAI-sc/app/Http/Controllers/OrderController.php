<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Balance;
use App\Models\ShippingAddress;
use Carbon\Carbon;

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
        Cart::where('user_id', Auth::user()->id)->update([
            'is_deleted' => 1
        ]);
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
        $products = Order::join('carts', 'orders.users_id', '=', 'carts.user_id')
            ->join('products','carts.product_id', '=', 'products.id')
            // ->join('product_image', 'carts.product_id', '=', 'product_image.product_id')
            ->select('carts.id as cart_id', 'quantity', 'size', 'price', 'product_name')
            ->where('orders.status', 'Complete')
            ->where('carts.is_deleted', 1)
            ->get()->all();
        $dataProduct=[];
        foreach ($products as $item){
            $json = response()->json([
                'id' => $item->cart_id,
                'details' => [
                    'quantity' => $item->quantity,
                    'size' => $item->size,
                ],
                'price' => $item->price * $item->quantity,
                // 'image' => $item->image_file,
                'name' => $item->product_name,
            ]);
            array_push($dataProduct,$json->original);
        }
        $orders = Order::where('users_id',[Auth::user()->id])
            ->where('status','Complete')
            ->get();
        $data=[];
        $shipping_address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
        $address = response()->json([
            "name" => $shipping_address->name,
            "phone_number" => $shipping_address->phone_number,
            "address" => $shipping_address->address,
            "city" => $shipping_address->city
        ]);
        foreach ($orders as $item) {
            $json = response()->json([
                'id' => $item->id,
                'created_at' => $item->created_at,
                'products' => [$dataProduct],
                'shipping_method' => $item->shipping_method,
                'shipping_address' => $address->original
            ]);
            array_push($data,$json->original);
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
