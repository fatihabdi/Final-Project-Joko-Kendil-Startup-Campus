<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function get_order(Request $request)
    {
        if(Auth::user()->is_admin != 1){
            return response()->json([
                'message' => 'Forbidden',
            ],403);
        }
        else{
            if($request->sort_by == 'Prize a_z' ){
                $order=Order::join('users', 'users_id', '=', 'users.id')->orderBy('total','desc')->paginate($request->page_size);
            }
            else{
                $order=Order::join('users', 'users_id', '=', 'users.id')->orderBy('total','asc')->paginate($request->page_size);
            }
            // dd($order);
            $data= [];
            foreach ($order as $item) {
                $json=response()->json([
                    'id' => $item->id,
                    'user_name' => $item->name,
                    'created_at' => $item->created_at,
                    'user_id' => $item->users_id,
                    'user_email' => $item->email,
                    'total' => $item->total,
                ]);
                array_push($data,$json->original);
            }
            return response()->json([
                'data' => $data
            ]);
        }
    }

    public function create_product(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'is_new' => 'required',
                'category' => 'required',
                'price' => 'required'
            ]);

            $product = Product::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'is_new' => $request->is_new,
                'category' => $request->category,
                'price' => $request->price
            ]);
            $product->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function update_product(Request $request, $id) {
        try {
            return $id;
            // $request->validate([
            //     'product_name' => 'required|string',
            //     'description' => 'required|string',
            //     'condition' => 'required|integer',
            //     'category' => 'required|integer',
            //     'price' => 'required|integer'
            // ]);
            // Product::where('id', $id)->update([
            //     'product_name' => $request->input('product_name'),
            //     'description' => $request->input('description'),
            //     'is_new' => $request->input('condition'),
            //     'category' => $request->input('category'),
            //     'price' => $request->input('price')
            // ]);
            // return response()->json([
            //     'message' => 'Product updated'
            // ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function delete_product() {

    }

    public function create_category(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string',
            ]);

            $category = Category::create([
                'category_name' => $request->category_name
            ]);
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function update_category() {

    }

    public function delete_category() {

    }

    public function get_total_sales()
    {
        return "Halaman Get Total Sales";
    }
}
