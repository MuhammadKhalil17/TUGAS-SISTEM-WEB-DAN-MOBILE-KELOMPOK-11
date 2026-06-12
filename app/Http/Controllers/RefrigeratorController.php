<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\RefrigeratorContract;
use Illuminate\Http\Request;

class RefrigeratorController extends Controller
{
    public function __construct(
        private readonly RefrigeratorContract $refrigerator
    ) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->refrigerator->getAllByUser($request->user()->id)
        );
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->refrigerator->addIngredient(
                $request->user()->id,
                $request->all()
            ),
            201
        );
    }

    public function destroy(Request $request, int $id)
    {
        $this->refrigerator->removeIngredient(
            $request->user()->id,
            $id
        );

        return response()->json([
            'message' => 'Bahan berhasil dihapus'
        ]);
    }
}