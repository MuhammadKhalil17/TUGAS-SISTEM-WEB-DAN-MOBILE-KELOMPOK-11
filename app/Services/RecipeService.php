<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\RecipeContract;
use Illuminate\Support\Facades\Http;

class RecipeService implements RecipeContract
{
    private string $apiKey;

    public function __construct()
    {
        // Mengambil API Key dari .env, jika tidak ada berikan string kosong
        $this->apiKey = env('SPOONACULAR_API_KEY', '');
    }

    public function searchByIngredients(array $ingredients): array
    {
        // Cegah eksekusi jika array bahan kosong
        if (empty($ingredients)) {
            return [
                'status' => 'error',
                'message' => 'Daftar bahan makanan tidak boleh kosong.'
            ];
        }

        if (empty($this->apiKey)) {
            // Return mock data for local demonstration/testing when API key is not configured
            $mockRecipes = [
                [
                    'id' => 648438,
                    'title' => 'Tomato and Egg Scramble',
                    'image' => 'https://spoonacular.com/recipeImages/648438-312x231.jpg',
                    'usedIngredientCount' => count(array_intersect(array_map('strtolower', $ingredients), ['egg', 'tomato', 'onion', 'garlic'])),
                    'missedIngredientCount' => max(0, 3 - count(array_intersect(array_map('strtolower', $ingredients), ['egg', 'tomato', 'onion', 'garlic'])))
                ],
                [
                    'id' => 648439,
                    'title' => 'Classic Chicken Soup',
                    'image' => 'https://spoonacular.com/recipeImages/648439-312x231.jpg',
                    'usedIngredientCount' => count(array_intersect(array_map('strtolower', $ingredients), ['chicken', 'onion', 'garlic', 'carrot'])),
                    'missedIngredientCount' => max(0, 4 - count(array_intersect(array_map('strtolower', $ingredients), ['chicken', 'onion', 'garlic', 'carrot'])))
                ]
            ];
            return [
                'status' => 'success',
                'data' => $mockRecipes
            ];
        }

        $ingredientsString = implode(',', $ingredients);

        // Menembak pihak ketiga (Spoonacular)
        $response = Http::get("https://api.spoonacular.com/recipes/findByIngredients", [
            'ingredients' => $ingredientsString,
            'apiKey' => $this->apiKey
        ]);

        // Cek apakah response dari Spoonacular sukses (Status 200)
        if ($response->successful()) {
            return [
                'status' => 'success',
                'data' => $response->json()
            ];
        }

        // Antisipasi jika API Key salah (401) atau kuota habis (402/429)
        return [
            'status' => 'error',
            'message' => 'Gagal mengambil data dari server Spoonacular. Kode Status: ' . $response->status()
        ];
    }

    public function getRecipeDetail(int $id): array
    {
        if (empty($this->apiKey)) {
            return [
                'status' => 'success',
                'data' => [
                    'id' => $id,
                    'title' => $id === 648438 ? 'Tomato and Egg Scramble' : 'Classic Chicken Soup',
                    'readyInMinutes' => 15,
                    'servings' => 2,
                    'instructions' => '1. Beat the eggs in a bowl. 2. Chop tomatoes and onions. 3. Heat oil and stir-fry.',
                    'extendedIngredients' => [
                        '2 large eggs',
                        '2 medium tomatoes',
                        '1/2 onion'
                    ]
                ]
            ];
        }

        $response = Http::get("https://api.spoonacular.com/recipes/{$id}/information", [
            'apiKey' => $this->apiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();

            return [
                'status' => 'success',
                'data' => [
                    'id' => $data['id'] ?? $id,
                    'title' => $data['title'] ?? 'Tanpa Judul',
                    'readyInMinutes' => $data['readyInMinutes'] ?? 0,
                    'servings' => $data['servings'] ?? 0,
                    'instructions' => $data['instructions'] ?? 'Instruksi tidak tersedia.',
                    'extendedIngredients' => collect($data['extendedIngredients'] ?? [])
                        ->map(fn($i) => $i['original'] ?? '')
                        ->toArray()
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal memuat detail resep.'
        ];
    }

    public function getNutrition(int $id): array
    {
        if (empty($this->apiKey)) {
            return [
                'status' => 'success',
                'data' => [
                    'calories' => '245k',
                    'carbs' => '15g',
                    'fat' => '12g',
                    'protein' => '10g'
                ]
            ];
        }

        $response = Http::get("https://api.spoonacular.com/recipes/{$id}/nutritionWidget.json", [
            'apiKey' => $this->apiKey
        ]);

        if ($response->successful()) {
            return [
                'status' => 'success',
                'data' => $response->json()
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal memuat informasi nutrisi.'
        ];
    }
}