<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\Category;
use App\Models\Banner;

class HomeController extends Controller
{
    public function get_banner() {
        $banner=Banner::get();
        // dd($data);
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
            $categories = Category::get()->all();
            $data = [];
            foreach ($categories as $category) {
                $json = new Response([
                    'id' => $category->id,
                    'title' => $category->category_name
                ]);
                array_push($data, $json->original);
            };
            return new Response([
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return new Response([
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return new Response([
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
