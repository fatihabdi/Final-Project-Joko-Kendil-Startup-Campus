<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function get_user_cart() {
        $cart = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select('carts.id', 'quantity', 'size', 'price', 'product_name')
            ->where('user_id', Auth::user()->id)->get()->all();
        $data = [];
        foreach ($cart as $item) {
            $detail = response()->json([
                'quantity' => $item->quantity,
                'size' => $item->size
            ]);
            $json = response()->json([
                'id' => $item->id,
                'details' => $detail->original,
                'price' => $item->price,
                'image' => "Not found",
                'name' => $item->product_name
            ]);
            array_push($data, $json->original);
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function delete_item_cart() {
        return "Halaman Delete Item Cart";
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
}
