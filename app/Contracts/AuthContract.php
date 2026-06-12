<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface AuthContract
{
    public function register(array $data): User;

    public function login(string $email, string $password): array;

    public function logout(User $user): bool;

    public function refreshToken(User $user): string;
}