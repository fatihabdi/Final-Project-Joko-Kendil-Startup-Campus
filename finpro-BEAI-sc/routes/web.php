<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/sign-up', [AuthController::class, 'sign_up'])
    ->name('sign-up');
Route::post('/sign-in', [AuthController::class, 'sign_in'])
    ->name('sign-in');

Route::prefix('home')->group(function () {
    Route::get('/banner', [HomeController::class, 'get_banner'])
        ->name('home.banner');
    Route::get('/category', [HomeController::class, 'get_category'])
        ->name('home.category');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'get_product_list'])
        ->name('product');
});