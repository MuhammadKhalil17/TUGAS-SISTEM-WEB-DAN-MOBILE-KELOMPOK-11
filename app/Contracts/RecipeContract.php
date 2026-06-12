<?php

declare(strict_types=1);

namespace App\Contracts;

interface RecipeContract
{
    public function searchByIngredients(array $ingredients): array;

    public function getRecipeDetail(int $recipeId): array;

    public function getNutrition(int $recipeId): array;
}