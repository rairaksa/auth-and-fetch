<?php

use App\Http\Controllers\ProductController;
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

Route::group(['prefix' => 'product', 'middleware' => 'jwt'], function() {
    Route::post('/fetch', [ProductController::class, 'fetch'])->name('product.fetch');
    Route::post('/aggregate', [ProductController::class, 'aggregate'])->name('product.aggregate');
    Route::post('/check', [ProductController::class, 'check'])->name('product.check');
});
