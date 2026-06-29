<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\FavoriteRecipeContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteRecipeController extends Controller
{
    public function __construct(
        private readonly FavoriteRecipeContract $favorite
    ) {}

    /**
     * Menampilkan semua resep favorit user.
     * GET /api/v1/favorite-recipes
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        return response()->json(
            $this->favorite->getAll($userId)
        );
    }

    
    public function store(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        // Menambahkan validasi ketat sebelum melempar data ke Service Layer
        $validator = Validator::make($request->all(), [
            'recipe_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Cek terlebih dahulu apakah resep sudah pernah difavoritkan
        $isAlreadyFavorited = $this->favorite->isFavorited(
            $userId, 
            (int) $request->recipe_id
        );

        if ($isAlreadyFavorited) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resep ini sudah ada di dalam daftar favorit kamu.'
            ], 409);
        }

        // Jika aman, panggil Service untuk menyimpan ke DB
        $result = $this->favorite->add($userId, [
            'recipe_id' => $request->recipe_id,
            'title' => $request->title,
            'image' => $request->image
        ]);

        return response()->json($result, 201);
    }

    /**
     * Menghapus resep dari daftar favorit.
     * DELETE /api/v1/favorite-recipes/{recipeId}
     */
    public function destroy(Request $request, int $recipeId): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $removed = $this->favorite->remove($userId, $recipeId);

        if (! $removed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resep favorit tidak ditemukan atau sudah dihapus.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resep berhasil dihapus dari daftar favorit.'
        ]);
    }
}