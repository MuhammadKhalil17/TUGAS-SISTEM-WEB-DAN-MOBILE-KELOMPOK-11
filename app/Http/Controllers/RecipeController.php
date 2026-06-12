<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function searchRecipes(Request $request)
    {
        // Validasi input agar memastikan request membawa array ingredients
        $request->validate([
            'ingredients' => 'required|array'
        ]);

        $ingredients = implode(',', $request->ingredients);
        $apiKey = env('SPOONACULAR_API_KEY'); 

        // Laravel menembak API pihak ketiga
        $response = Http::get("https://api.spoonacular.com/recipes/findByIngredients", [
            'ingredients' => $ingredients,
            'apiKey' => $apiKey
        ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json()
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengambil data dari penyedia resep'
        ], 502);
    }
}