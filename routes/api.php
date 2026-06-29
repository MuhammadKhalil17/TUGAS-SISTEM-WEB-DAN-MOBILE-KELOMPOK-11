<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteRecipeController;

// Endpoint Publik API
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

// Endpoint terproteksi Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// Manajemen Kulkas (Bisa diakses publik dengan fallback user_id = 1, atau terautentikasi)
Route::prefix('fridge')->group(function () {
    Route::get('/', [FridgeController::class, 'index']);
    Route::post('/', [FridgeController::class, 'store']);
    Route::delete('/clear', [FridgeController::class, 'clear']);
    Route::delete('/{id}', [FridgeController::class, 'destroy']);
});

Route::prefix('refrigerator')->group(function () {
    Route::get('/', [FridgeController::class, 'index']);
    Route::post('/', [FridgeController::class, 'store']);
    Route::delete('/clear', [FridgeController::class, 'clear']);
    Route::delete('/{id}', [FridgeController::class, 'destroy']);
});

// Generator Resep
Route::post('/recipes/search', [RecipeController::class, 'search']);
Route::get('/recipes/{id}/details', [RecipeController::class, 'show']);

// Buku Resep Favorit
Route::get('/favorite-recipes', [FavoriteRecipeController::class, 'index']);
Route::post('/favorite-recipes', [FavoriteRecipeController::class, 'store']);
Route::delete('/favorite-recipes/{recipeId}', [FavoriteRecipeController::class, 'destroy']);

// Rute Alternatif Bookmarks
Route::post('/bookmarks', [FavoriteRecipeController::class, 'store']);