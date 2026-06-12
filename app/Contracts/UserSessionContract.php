<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface UserSessionContract
{
    public function createToken(User $user): string;

    public function revokeToken(User $user): bool;

    public function validateToken(string $token): bool;
}