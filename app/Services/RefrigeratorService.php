<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\RefrigeratorContract;
use App\Models\Fridge;

class RefrigeratorService implements RefrigeratorContract
{
    public function getAllByUser(int $userId): array
    {
        $ingredients = Fridge::where('user_id', $userId)->select('id', 'name')->get();

        return [
            'status' => 'success',
            'data' => [
                'ingredients' => $ingredients
            ]
        ];
    }

    public function addIngredient(int $userId, array $data): array
    {
        $fridgeItem = Fridge::create([
            'user_id' => $userId,
            'name' => strtolower($data['name']),
        ]);

        return [
            'status' => 'success',
            'message' => 'Bahan berhasil ditambahkan ke kulkas',
            'data' => [
                'id' => $fridgeItem->id,
                'name' => $fridgeItem->name
            ]
        ];
    }

    public function removeIngredient(int $userId, int $ingredientId): bool
    {
        $deleted = Fridge::where('user_id', $userId)->where('id', $ingredientId)->delete();
        
        return $deleted > 0;
    }

    public function clearInventory(int $userId): bool
    {
        $deleted = Fridge::where('user_id', $userId)->delete();

        return $deleted > 0;
    }
}