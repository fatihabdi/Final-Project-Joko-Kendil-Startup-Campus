<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function get_banner()
    {
        return "Halaman Get Banner";
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
}
