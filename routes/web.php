<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RefrigeratorController;
use App\Http\Controllers\FavoriteRecipeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Jalur API untuk aplikasi Kulkasku. Diatur dengan prefix 'api/v1' 
| dari file bootstrap/app.php.
|
*/

// 🔐 Endpoint Publik (Otentikasi Akun)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// 🛡️ Endpoint Privat (Wajib Menyertakan Bearer Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout Sesi
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // 🥦 Manajemen Inventaris Kulkas (Refrigerator)
    Route::get('/fridge', [RefrigeratorController::class, 'index']);
    Route::post('/fridge', [RefrigeratorController::class, 'store']);
    Route::delete('/fridge/{id}', [RefrigeratorController::class, 'destroy']);

    // 🍳 Mesin Pencari & Generator Resep (Spoonacular Proxy)
    Route::post('/recipes/search', [RecipeController::class, 'search']);
    Route::get('/recipes/{id}/details', [RecipeController::class, 'show']);
    Route::get('/recipes/{id}/nutrition', [RecipeController::class, 'nutrition']);

    // 📌 Buku Resep Favorit (Bookmark / Favorite)
    Route::get('/bookmarks', [FavoriteRecipeController::class, 'index']);
    Route::post('/bookmarks', [FavoriteRecipeController::class, 'store']);
    Route::delete('/bookmarks/{recipeId}', [FavoriteRecipeController::class, 'destroy']);
    Route::view('/', 'dashboard');

    Route::view('/dashboard', 'dashboard');

    Route::view('/fridge', 'fridge.index');

    Route::view('/recipes', 'recipes.index');

    Route::view('/favorites', 'favorites.index');  
});