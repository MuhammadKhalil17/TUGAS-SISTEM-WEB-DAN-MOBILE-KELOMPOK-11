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