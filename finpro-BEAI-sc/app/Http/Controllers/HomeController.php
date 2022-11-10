<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function get_banner() {
        return "Halaman Get Banner";
    }

    public function get_category() {
        return "Halaaman Get Category";
    }

    public function get_image($imagefile){
        $path = public_path().'\\'.$imagefile;
        // dd($path);
        return Response::download($path); 
    }
}
