<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\RefrigeratorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FridgeController extends Controller
{
    public function __construct(
        private readonly RefrigeratorContract $refrigerator
    ) {}

/**
     * Melihat isi kulkas user.
     * GET /api/v1/fridge
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        return response()->json(
            $this->refrigerator->getAllByUser($userId)
        );
    }

    /**
     * Menambahkan bahan makanan baru ke dalam kulkas.
     * POST /api/v1/fridge
     */
    public function store(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nama bahan makanan wajib diisi.'
            ], 400);
        }

        return response()->json(
            $this->refrigerator->addIngredient(
                $userId,
                $request->all()
            ),
            201
        );
    }

    /**
     * Menghapus satu bahan makanan tertentu dari kulkas berdasarkan ID.
     * DELETE /api/v1/fridge/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $deleted = $this->refrigerator->removeIngredient($userId, $id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bahan makanan tidak ditemukan di dalam kulkas.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bahan makanan berhasil dihapus dari kulkas.'
        ]);
    }
    /**
     * Mengosongkan seluruh isi kulkas user.
     * DELETE /api/v1/fridge/clear
     */
    public function clear(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $cleared = $this->refrigerator->clearInventory($userId);

        if (! $cleared) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kulkas kamu memang sudah kosong.'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seluruh isi kulkas berhasil dikosongkan.'
        ]);
    }
}