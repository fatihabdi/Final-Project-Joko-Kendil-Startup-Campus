<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function get_order() {
        return "Halaman Get Order";
    }

    public function create_product() {
        return "Halaman Create Product";
    }

    public function create_category() {
        return "Halaman Create Category";
    }

    public function get_total_sales() {
        return "Halaman Get Total Sales";
    }
}
