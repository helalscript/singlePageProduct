<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('admin')->group(function () {
Route::resource('products', ProductsController::class)->names('products');
Route::resource('photos', PhotoController::class)->names('photos');
});
