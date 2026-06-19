<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BookmarkController;

// Endpoint Publik (Tanpa Login)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Endpoint Privat (Wajib Login / Membawa Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    // Fitur Kulkas
    Route::get('/refrigerator', [RefrigeratorController::class, 'index']);
    Route::post('/refrigerator', [RefrigeratorController::class, 'store']);
    Route::delete('/refrigerator/clear', [RefrigeratorController::class, 'clear']); 
    Route::delete('/refrigerator/{id}', [RefrigeratorController::class, 'destroy']);

    // Fitur Resep Favorit
    Route::get('/favorite-recipes', [FavoriteRecipeController::class, 'index']);
    Route::post('/favorite-recipes', [FavoriteRecipeController::class, 'store']);
    Route::delete('/favorite-recipes/{recipeId}', [FavoriteRecipeController::class, 'destroy']);
});