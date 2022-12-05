<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ProductImages;

class CartController extends Controller
{
    public function get_user_cart() {
        $cart = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select('carts.id as cart_id', 'products.id as product_id', 'quantity', 'size', 'price', 'product_name')
            ->where('user_id', Auth::user()->id)
            ->where('is_deleted', 0)->get()->all();
        $data = [];
        foreach ($cart as $item) {
            $img = ProductImages::where('product_id', $item->product_id)->get()->first();
            $detail = response()->json([
                'quantity' => $item->quantity,
                'size' => $item->size
            ]);
            $json = response()->json([
                'id' => $item->cart_id,
                'details' => $detail->original,
                'price' => $item->price,
                'image' => Storage::url($img->image_title),
                'name' => $item->product_name
            ]);
            array_push($data, $json->original);
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function delete_cart_item($id) {
        Cart::where('id',$id)->where('user_id', Auth::user()->id)->update(['is_deleted'=>1]);
        $allItem = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select('products.price', 'quantity')
            ->where('carts.is_deleted', 0)
            ->get()->all();
        $total = 0;
        foreach ($allItem as $item) {
            $total += $item->price * $item->quantity;
        }
        Order::where('users_id', Auth::user()->id)->where('status', 'Not Complete')->update(['total' => $total]);
        return response()->json([
            'message' => 'Cart Deleted'
        ]);
    }

    public function get_user_shipping_address() {
        $address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
        return response()->json([
            'id' => $address->id,
            'name' => $address->name,
            'phone_number' => $address->phone_number,
            'address' => $address->address,
            'city' => $address->city
        ]);
    }

    public function get_shipping_price(){
        $cartprices = Order::where('users_id',Auth::user()->id)->where('status', 'Not Complete')->get(['total']);
        $data = [];
        foreach($cartprices as $item){
            if ($item->total >= 200) {
                $regu = 0.2 * $item->total;
            }
            else{
                $regu = 0.15 * $item->total;
            }
            if ($item->total >= 300){
                $premi = 0.25 * $item->total;
            }
            else{
                $premi = 0.2 * $item->total;
            }
            $json = response()->json([
                'Name' => 'regular',
                'price' => round($regu, 2), 
            ]);
            $json1 = response()->json([
                'Name' => 'next day',
                'price' => round($premi, 2),  
            ]);
            array_push($data,$json->original);
            array_push($data,$json1->original);
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
