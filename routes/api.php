<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::name('api.')->group(function () {
    Route::prefix('v1')->name('v1.')->group(function () {
        Route::prefix('products')->name('product.')->group(function () {
            Route::post('', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
            Route::put('', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');
        });

        Route::prefix('orders')->name('order.')->group(function () {
            Route::post('', [\App\Http\Controllers\Api\OrderController::class, 'store'])->name('store');
            Route::delete('', [\App\Http\Controllers\Api\OrderController::class, 'destroy'])->name('destroy');
        });
    });
});
