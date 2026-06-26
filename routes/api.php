<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteRecipeController;

// Endpoint Publik API
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

// Manajemen Kulkas
Route::get('/fridge', [FridgeController::class, 'index']);
Route::post('/fridge', [FridgeController::class, 'store']);
Route::delete('/fridge/{id}', [FridgeController::class, 'destroy']);

Route::post('/recipes/search', [RecipeController::class, 'search']);

// PERBAIKAN DI SINI: Daftarkan rute alternatif agar klop dengan Fetch Blade temanmu
Route::get('/favorite-recipes', [FavoriteRecipeController::class, 'index']);
Route::post('/bookmarks', [FavoriteRecipeController::class, 'store']);
Route::delete('/favorite-recipes/{recipeId}', [FavoriteRecipeController::class, 'destroy']);