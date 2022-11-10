<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
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

    public function change_shipping_address() {
        return "Halaman Change Shipping Address";
    }

    public function top_up(Request $request) {
        try {
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
        return "Halaman Balance";
    }
}
