<?php

declare(strict_types=1);

namespace App\Contracts;

interface FavoriteRecipeContract
{
    public function getAll(int $userId): array;

    public function add(int $userId, array $recipe): mixed;

    public function remove(int $userId, int $recipeId): bool;

    public function isFavorited(int $userId, int $recipeId): bool;
}