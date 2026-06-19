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
        $this->apiKey = env('SPOONACULAR_API_KEY', '');
    }

    public function searchByIngredients(array $ingredients): array
    {
        $ingredientsString = implode(',', $ingredients);

        $response = Http::get("https://api.spoonacular.com/recipes/findByIngredients", [
            'ingredients' => $ingredientsString,
            'apiKey' => $this->apiKey
        ]);

        return [
            'status' => 'success',
            'data' => $response->json()
        ];
    }

    public function getRecipeDetail(int $id): array
    {
        $response = Http::get("https://api.spoonacular.com/recipes/{$id}/information", [
            'apiKey' => $this->apiKey
        ]);

        $data = $response->json();

        return [
            'status' => 'success',
            'data' => [
                'id' => $data['id'] ?? $id,
                'title' => $data['title'] ?? '',
                'readyInMinutes' => $data['readyInMinutes'] ?? 0,
                'servings' => $data['servings'] ?? 0,
                'instructions' => $data['instructions'] ?? '',
                'extendedIngredients' => collect($data['extendedIngredients'] ?? [])->map(fn($i) => $i['original'] ?? '')->toArray()
            ]
        ];
    }

    public function getNutrition(int $id): array
    {
        $response = Http::get("https://api.spoonacular.com/recipes/{$id}/nutritionWidget.json", [
            'apiKey' => $this->apiKey
        ]);

        return [
            'status' => 'success',
            'data' => $response->json()
        ];
    }
}