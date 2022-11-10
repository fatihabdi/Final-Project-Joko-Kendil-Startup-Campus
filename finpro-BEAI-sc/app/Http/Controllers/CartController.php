<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Exception;

class CartController extends Controller
{
    public function get_user_cart() {
        try {
            $cart = Cart::join('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.user_id', Auth::user()->id)
                ->select('carts.id', 'quantity', 'size', 'price', 'product_name')
                ->get()->all();
            $data = [];
            foreach($cart as $item) {
                $detail = response([
                    'quantity' => $item->quantity,
                    'size' => $item->size
                ]);
                $json = response([
                    'id' => $item->id,
                    'details' => $detail->original,
                    'price' => ($item->quantity*$item->price),
                    'image' => "Not found",
                    'name' => $item->product_name
                ]);
                array_push($data, $json->original);
            }

            return $data;
        } catch (Throwable $th) {
            return response([
                'message' => 'Failed because ' . $th->getMessage()
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'Error because ' . $e->getMessage()
            ]);
        }
    }

    public function delete_item_cart() {
        return "Halaman Delete Item Cart";
    }

    public function get_user_shipping_address() {
        try {
            $address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
            return response([
                'id' => $address->id,
                'name' => $address->name,
                'phone_number' => $address->phone_number,
                'address' => $address->address,
                'city' => $address->city
            ]);
        } catch (Throwable $th) {
            return response([
                'message' => 'Failed because ' . $th->getMessage()
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'Error because ' . $e->getMessage()
            ]);
        }
    }
}
