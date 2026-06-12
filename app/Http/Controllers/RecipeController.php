<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\RecipeContract;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        private readonly RecipeContract $recipe
    ) {}

    public function search(Request $request)
    {
        return response()->json(
            $this->recipe->searchByIngredients(
                $request->ingredients
            )
        );
    }

    public function show(int $id)
    {
        return response()->json(
            $this->recipe->getRecipeDetail($id)
        );
    }

    public function nutrition(int $id)
    {
        return response()->json(
            $this->recipe->getNutrition($id)
        );
    }
}