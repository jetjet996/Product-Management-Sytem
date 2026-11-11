<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('products/list', [ProductController::class, 'getlist']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('products/bulk-delete', [ProductController::class, 'bulkDestroy']);
    Route::delete('/products', [ProductController::class, 'destroy']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products/export', [ProductController::class, 'export']);
    Route::apiResource('products', ProductController::class)->except(['destroy']);
});
