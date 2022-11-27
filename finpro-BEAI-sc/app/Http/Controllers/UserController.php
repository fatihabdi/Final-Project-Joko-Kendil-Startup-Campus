<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Exception;

class UserController extends Controller
{
    public function get_user_detail() {
        return response()->json([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone_number' => Auth::user()->phone_number
        ]);
    }

    public function change_shipping_address(Request $request) {
        $request->validate([
            'name' => 'string',
            'phone_number' => 'string|min:12',
            'address' => 'string',
            'city' => 'string'
        ]);
        $address = ShippingAddress::where('user_id', Auth::user()->id)->get()->first();
        $address->name = $request->input('name') ? $request->input('name') : $address->name;
        $address->phone_number = $request->input('phone_number') ? $request->input('phone_number') : $address->phone_number;
        $address->address = $request->input('address') ? $request->input('address') : $address->address;
        $address->city = $request->input('city') ? $request->input('city') : $address->city;
        $address->save();
        return response()->json([
            'name' => $address->name,
            'phone_number' => $address->phone_number,
            'address' => $address->address,
            'city' => $address->city
        ]);
    }

    public function top_up(Request $request) {
        try {
            $request->validate([
                'amount' => 'required|integer'
            ]);
            $balance = Balance::firstOrNew(['user_id' => Auth::user()->id]);
            $balance->balance += $request->input('amount');
            $balance->save();
            return response()->json([
                'message' => 'Top Up balance success'
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

    public function get_balance() {
        try {
            $balance = Balance::where('user_id', Auth::user()->id)->get()->first();
            $data = response()->json([
                'balance' => $balance->balance
            ]);
            return response()->json([
                'data' => $data->original
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
