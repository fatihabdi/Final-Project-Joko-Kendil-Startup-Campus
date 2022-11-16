<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::post('/sign-up', [AuthController::class, 'register'])
    ->name('sign-up');
Route::post('/sign-in', [AuthController::class, 'login'])
    ->name('sign-in');
Route::get('/image/{imagefile}',[HomeController::class,'get_image'])
    ->name('image.get_image');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/admin/orders', [AdminController::class, 'get_order'])
    //     ->name('admin.order');
    Route::post('/products', [AdminController::class, 'create_product'])
        ->name('admin.product');
    Route::post('/categories', [AdminController::class, 'create_category'])
        ->name('admin.category');
    Route::get('/sales', [AdminController::class, 'get_total_sales'])
        ->name('admin.sale');
});    

Route::get('/admin/orders', [AdminController::class, 'get_order'])
->name('admin.order');

Route::get('/categories', [ProductController::class, 'categories'])
    ->name('categories');
Route::post('/order', [OrderController::class, 'create_order'])
    ->name('order.create');
Route::get('/order', [OrderController::class, 'user_order'])
    ->name('order.user');

Route::prefix('home')->group(function () {
    Route::get('/banner', [HomeController::class, 'get_banner'])
        ->name('home.banner');
    Route::get('/category', [HomeController::class, 'get_category'])
        ->name('home.category');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'get_product_list'])
        ->name('product');
    Route::post('/search_image', [ProductController::class, 'search_product'])
        ->name('product.search_image');
    Route::get('{id}', [ProductController::class, 'get_product_detail'])
        ->name('product.detail');
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'get_user_cart'])
        ->name('cart.user_cart');
    Route::post('/', [productController::class, 'add_to_cart'])
        ->name('cart.add');
    Route::delete('/{cart_id}', [CartController::class, 'delete_cart_item'])
        ->name('cart.delete');
});

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'get_user_detail'])
        ->name('user.detail');
    Route::get('/shipping_address', [CartController::class, 'get_user_shipping_address'])
        ->name('cart.shipping_address');
    Route::post('/shipping_address', [UserController::class, 'change_shipping_address'])
        ->name('user.change_shipping_address');
    Route::post('/balance', [UserController::class, 'top_up'])
        ->name('user.top_up');
    Route::get('/balance', [UserController::class, 'get_balance'])
        ->name('user.balance');
});
Route::get('/shipping_price', [CartController::class, 'get_shipping_price'])
->name('get_shipping_price');
