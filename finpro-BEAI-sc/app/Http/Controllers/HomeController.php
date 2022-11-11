<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
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

    public function get_category() {
        return "Halaaman Get Category";
    }

    public function get_image($imagefile){
        $path = public_path().'\\'.$imagefile;
        return Response::download($path); 
    }
}
