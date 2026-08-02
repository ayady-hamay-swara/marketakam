<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/home', function () {
    return view('index');
});

Route::get('/manage-items', function () {
    return view('manage-items');
});
Route::get('/manage-employees', function () {
    return view('manage-employees');
});
Route::get('/pos-checkout', function () {
    return view('pos-checkout');
});
Route::get('/search-orders', function () {
    return view('search-orders');
});
