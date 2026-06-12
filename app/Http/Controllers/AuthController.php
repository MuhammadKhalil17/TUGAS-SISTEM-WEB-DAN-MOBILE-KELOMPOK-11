<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\AuthContract;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthContract $auth
    ) {}

    public function register(Request $request)
    {
        $user = $this->auth->register($request->all());

        return response()->json([
            'message' => 'Register berhasil',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $token = $this->auth->login(
            $request->email,
            $request->password
        );

        return response()->json($token);
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}