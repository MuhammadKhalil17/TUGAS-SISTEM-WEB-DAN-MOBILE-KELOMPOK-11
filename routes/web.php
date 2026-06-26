<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Utama & Dashboard (Langsung di luar folder views)
Route::get('/', function () {
    return view('dashboard');
});
Route::view('/dashboard', 'dashboard');

// 2. Mengarah ke file di dalam folder (menggunakan tanda titik '.')
Route::view('/fridge', 'fridge.fridge');
Route::view('/recipes', 'recipes.recipes');
Route::view('/favorites', 'favorites.favorites');