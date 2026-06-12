<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\FavoriteRecipeContract;
use Illuminate\Http\Request;

class FavoriteRecipeController extends Controller
{
    public function __construct(
        private readonly FavoriteRecipeContract $favorite
    ) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->favorite->getAll($request->user()->id)
        );
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->favorite->add(
                $request->user()->id,
                $request->all()
            ),
            201
        );
    }

    public function destroy(Request $request, int $recipeId)
    {
        $this->favorite->remove(
            $request->user()->id,
            $recipeId
        );

        return response()->json([
            'message' => 'Favorit berhasil dihapus'
        ]);
    }
}