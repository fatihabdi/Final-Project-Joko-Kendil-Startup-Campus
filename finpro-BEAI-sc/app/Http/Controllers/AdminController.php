<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class AdminController extends Controller
{
    public function get_order()
    {
        return "Halaman Get Order";
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

    public function get_total_sales()
    {
        return "Halaman Get Total Sales";
    }
}
