<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Response;
use App\Models\Category;
use App\Models\Banner;
use App\Models\ProductImages;

class HomeController extends Controller
{
    public function get_banner() {
        $banner=Banner::get();
        $data=[];
        foreach($banner as $item){
            $json=response()->json([
                'id' => $item->id,
                'image' => $item->path_to,
                'title' => $item->title,
            ]);
            array_push($data,$json->original);
        }
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
                    ->get()->first();
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
