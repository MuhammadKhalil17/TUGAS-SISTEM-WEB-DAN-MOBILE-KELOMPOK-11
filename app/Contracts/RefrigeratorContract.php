<?php

declare(strict_types=1);

namespace App\Contracts;

interface RefrigeratorContract
{
    public function getAllByUser(int $userId): array;

    public function addIngredient(int $userId, array $data): mixed;

    public function removeIngredient(int $userId, int $ingredientId): bool;

    public function clearInventory(int $userId): bool;
}