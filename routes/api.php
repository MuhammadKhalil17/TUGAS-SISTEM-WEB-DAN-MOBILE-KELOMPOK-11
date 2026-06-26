<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteRecipeController;

// Endpoint Publik (Tanpa Login)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Endpoint Privat (Wajib Login / Membawa Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    // Fitur Kulkas
    Route::get('/refrigerator', [FridgeController::class, 'index']);
    Route::post('/refrigerator', [FridgeController::class, 'store']);
    Route::delete('/refrigerator/clear', [FridgeController::class, 'clear']); 
    Route::delete('/refrigerator/{id}', [FridgeController::class, 'destroy']);

    // Fitur Resep Favorit
    Route::get('/favorite-recipes', [FavoriteRecipeController::class, 'index']);
    Route::post('/favorite-recipes', [FavoriteRecipeController::class, 'store']);
    Route::delete('/favorite-recipes/{recipeId}', [FavoriteRecipeController::class, 'destroy']);
});