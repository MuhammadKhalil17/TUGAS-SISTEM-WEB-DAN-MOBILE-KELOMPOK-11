<?php

declare(strict_types=1);

namespace App\Contracts;

interface SpoonacularServiceContract
{
    public function findByIngredients(array $ingredients): array;

    public function recipeInformation(int $recipeId): array;

    public function nutritionInformation(int $recipeId): array;
}