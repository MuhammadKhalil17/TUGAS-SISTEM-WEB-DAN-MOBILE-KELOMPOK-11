<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    // POST /api/v1/bookmarks (Menyimpan resep ke favorit)
    public function store(Request $request)
    {
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

        // Cek apakah resep ini sudah pernah dibookmark oleh user yang sama
        $alreadyBookmarked = $request->user()->bookmarks()
            ->where('spoonacular_recipe_id', $request->recipe_id)
            ->exists();

        if ($alreadyBookmarked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe is already bookmarked'
            ], 409);
        }

        // Simpan bookmark baru terikat dengan ID User
        $bookmark = Bookmark::create([
            'user_id' => $request->user()->id,
            'spoonacular_recipe_id' => $request->recipe_id,
            'title' => $request->title,
            'image' => $request->image
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe successfully bookmarked'
        ], 200);
    }
}