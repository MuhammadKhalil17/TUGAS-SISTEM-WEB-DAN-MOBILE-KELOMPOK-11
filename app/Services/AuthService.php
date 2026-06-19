<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AuthContract;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthContract
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password yang kamu masukkan salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ];
    }

    public function logout(User $user): bool
    {
        // Menghapus token aktif saat ini dan mengembalikan status boolean
        return (bool) $user->currentAccessToken()->delete();
    }

    public function refreshToken(User $user): string
    {
        // Menghapus token lama dan menerbitkan token baru
        $user->tokens()->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }
}