<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FavoriteRecipeContract;
use App\Models\Bookmark;

class FavoriteRecipeService implements FavoriteRecipeContract
{
    public function getAll(int $userId): array
    {
        $bookmarks = Bookmark::where('user_id', $userId)->get();

        return [
            'status' => 'success',
            'data' => $bookmarks
        ];
    }

    public function add(int $userId, array $data): array
    {
        $bookmark = Bookmark::updateOrCreate(
            [
                'user_id' => $userId,
                'spoonacular_recipe_id' => $data['recipe_id']
            ],
            [
                'title' => $data['title'],
                'image' => $data['image'] ?? null,
            ]
        );

        return [
            'status' => 'success',
            'message' => 'Resep berhasil difavoritkan',
            'data' => $bookmark
        ];
    }

    public function remove(int $userId, int $recipeId): void
    {
        Bookmark::where('user_id', $userId)
            ->where('spoonacular_recipe_id', $recipeId)
            ->delete();
    }
}