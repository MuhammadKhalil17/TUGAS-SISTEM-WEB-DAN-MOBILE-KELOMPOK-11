<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'     => 'Asisten Lab',
            'email'    => 'aslab@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('users', ['email' => 'aslab@test.com']);
    }

    /** @test */
    public function register_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@test.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name'     => 'Coba Lagi',
            'email'    => 'duplicate@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        User::factory()->create([
            'email'    => 'login@test.com',
            'password' => bcrypt('rahasia123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'login@test.com',
            'password' => 'rahasia123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonStructure(['data' => ['token', 'user']]);
    }

    /** @test */
    public function login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email'    => 'wrongpass@test.com',
            'password' => bcrypt('benar123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'wrongpass@test.com',
            'password' => 'salah456',
        ]);

        $response->assertStatus(401);
    }
}
