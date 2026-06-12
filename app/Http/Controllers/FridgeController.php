<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FridgeController extends Controller
{
    // 1. GET /api/v1/fridge (Melihat isi kulkas user yang sedang login)
    public function index(Request $request)
    {
        // Mengambil data bahan makanan milik user yang sedang terautentikasi
        $ingredients = $request->user()->fridges()->select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'ingredients' => $ingredients
            ]
        ], 200);
    }

    // 2. POST /api/v1/fridge (Menambahkan bahan baru ke kulkas)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ingredient name field is required'
            ], 400);
        }

        // Menyimpan data bahan makanan baru terikat dengan id user yang login
        $fridgeItem = Fridge::create([
            'user_id' => $request->user()->id,
            'name' => strtolower($request->name), // Disimpan dengan huruf kecil semua agar seragam
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient added to fridge successfully',
            'data' => [
                'id' => $fridgeItem->id,
                'name' => $fridgeItem->name
            ]
        ], 200); // Response sukses sesuai spesifikasi API
    }
}