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
     * GET /api/v1/refrigerator
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->refrigerator->getAllByUser($request->user()->id)
        );
    }

    /**
     * Menambahkan bahan makanan baru ke dalam kulkas.
     * POST /api/v1/refrigerator
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi input nama bahan makanan
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
                $request->user()->id,
                $request->all()
            ),
            201
        );
    }

    /**
     * Menghapus satu bahan makanan tertentu dari kulkas berdasarkan ID.
     * DELETE /api/v1/refrigerator/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $deleted = $this->refrigerator->removeIngredient($request->user()->id, $id);

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
     * DELETE /api/v1/refrigerator/clear
     */
    public function clear(Request $request): JsonResponse
    {
        $cleared = $this->refrigerator->clearInventory($request->user()->id);

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