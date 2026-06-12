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
    Route::get('/fridge', [FridgeController::class, 'index']);
    Route::post('/fridge', [FridgeController::class, 'store']);
    
    Route::post('/recipes/search', [RecipeController::class, 'searchRecipes']);
    Route::get('/recipes/{id}/details', [RecipeController::class, 'recipeDetails']);
    
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
});