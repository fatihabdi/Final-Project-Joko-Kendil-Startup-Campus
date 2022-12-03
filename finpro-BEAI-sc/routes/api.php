<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::put('/products/{id}', [AdminController::class, 'update_product'])
        ->name('admin.update_product');
    Route::delete('/products/{id}', [AdminController::class, 'delete_product'])
        ->name('admin.delete_product');
    Route::put('/categories/{id}', [AdminController::class, 'update_category'])
        ->name('admin.update_category');
    Route::delete('/categories/{id}', [AdminController::class, 'delete_category'])
        ->name('admin.delete_category');
});

// Route::post('/sign-in', [AuthController::class, 'login'])
//     ->name('sign-in');