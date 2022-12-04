<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Order;
use Carbon\Carbon;
use Throwable;
use Exception;

class AdminController extends Controller
{
    public function get_order(Request $request)
    {
        if(Auth::user()->role != "seller"){
            return response()->json([
                'message' => 'Forbidden',
            ],403);
        }
        else{
            if($request->sort_by == 'Price a_z' ){
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
            $product = Product::insertGetId([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'condition' => $request->condition,
                'category' => $request->category,
                'price' => $request->price,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $base64_image = $request->images;
            foreach ($base64_image as $b64_image) {
                $image = str_replace('data:image/jpeg;base64,', '', $b64_image);
                $image = str_replace('data:image/jpg;base64,', '', $image);
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace('data:image/webp;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = time()."image.png";
                $path = \File::put(storage_path().'/app/public/'.$imageName, base64_decode($image));

                ProductImages::create([
                    'product_id' => $product,
                    'image_title' => $imageName,
                    'image_file' => $path
                ]);
            }


            return response()->json([
                'status' => 'success',
                'message' => 'Product added'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function update_product(Request $request, $id) {
        try {
            $request->validate([
                'product_name' => 'required|string',
                'description' => 'required|string',
                'condition' => 'required|string',
                'category' => 'required|integer',
                'price' => 'required|integer'
            ]);
            $category = Category::where('id', $request->input('category'))->get()->first();
            if($category->active == 0) {
                return response()->json([
                    'message' => 'Category not found'
                ]);
            }

            $path = Storage::disk('public')->putFile('photos', $request->file('images'));

            Product::where('id', $id)->update([
                'product_name' => $request->input('product_name'),
                'description' => $request->input('description'),
                'condition' => $request->input('condition'),
                'category' => $request->input('category'),
                'price' => $request->input('price')
            ]);



            return response()->json([
                'message' => 'Product updated'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function delete_product($id) {
        try {
            Product::where('id', $id)->update([
                'active' => 0
            ]);
            return response()->json([
                'message' => 'Product Deleted'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function create_category(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string',
            ]);

            $category = Category::create([
                'category_name' => $request->category_name,
                'active' => 1
            ]);
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function update_category(Request $request, $id) {
        try {
            $request->validate([
                'category_name' => 'required|string',
            ]);
            Category::where('id', $id)->update([
                'category_name' => $request->input('category_name')
            ]);
            return response()->json([
                'message' => 'Category updated'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function delete_category($id) {
        try {
            Category::where('id', $id)->update([
                'active' => 0
            ]);
            return response()->json([
                'message' => 'Category Deleted'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function get_total_sales()
    {
        $order = Order::where('status', 'Complete')->get()->all();
        $total = 0;
        foreach ($order as $item) {
            $total += $item->total;
        }
        $data = response()->json([
            'total' => $total
        ]);

        return response()->json([
            'data' => $data->original
        ]);
    }
}
