<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Response;
use App\Models\Category;
use App\Models\ProductImages;

class HomeController extends Controller
{
    public function get_banner() {
        // $banner = ProductImages::inRandomOrder()->limit(5)->get();
        $banner = ProductImages::get()->first();
        $data = [];
        // foreach($banner as $item){
            $title = explode('.', $banner->image_title)[0];
            $json=response()->json([
                'id' => $banner->id,
                'image' => Storage::url($banner->image_title),
                'title' => $title,
            ]);
            array_push($data,$json->original);
        // }
        return response()->json([
            'data' => $data
        ]);
    }

    public function get_category()
    {
        try {
            $categories = Category::where('active', 1)->get();
            $data = [];
            foreach ($categories as $item) {
                $img = ProductImages::join('products', 'product_image.product_id', '=', 'products.id')
                    ->join('categories', 'products.category', '=', 'categories.id')
                    ->select('image_title')
                    ->where('categories.id', $item->id)
                    ->get()[1];
                if (isset($img)) {
                    $json = response()->json([
                        'id' => $item->id,
                        'title' => $item->category_name,
                        'image' => Storage::url($img->image_title)
                    ]);
                } else {
                    $json = response()->json([
                        'id' => $item->id,
                        'title' => $item->category_name
                    ]);
                }
                array_push($data, $json->original);
            };
            return response()->json([
                'data' => $data
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

    public function get_image($imagefile){
        $path = public_path().'\\'.$imagefile;
        return Response::download($path); 
    }
}
