<?php

use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/products/addNew', function () {
    return view('addNewProduct');
})->name('addNewProduct');

Route::get('/products/ProductDetails', function () {
    return view('productDetail');
})->name('productDetail');