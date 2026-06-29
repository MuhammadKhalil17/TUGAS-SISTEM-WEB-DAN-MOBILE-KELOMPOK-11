<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── REGISTER ────────────────────────────────────────────────────────────────

test('user dapat register dengan data yang valid', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name'     => 'Asisten Lab',
        'email'    => 'aslab@test.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('status', 'success');

    $this->assertDatabaseHas('users', ['email' => 'aslab@test.com']);
});

test('register gagal jika email sudah dipakai', function () {
    User::factory()->create(['email' => 'duplikat@test.com']);

    $response = $this->postJson('/api/v1/auth/register', [
        'name'     => 'Coba Lagi',
        'email'    => 'duplikat@test.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
});

test('register gagal jika field wajib kosong', function () {
    $response = $this->postJson('/api/v1/auth/register', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'email', 'password']);
});

// ─── LOGIN ────────────────────────────────────────────────────────────────────

test('user dapat login dengan kredensial yang benar', function () {
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
             ->assertJsonStructure([
                 'data' => ['token', 'user' => ['id', 'name', 'email']]
             ]);
});

test('login gagal jika password salah', function () {
    User::factory()->create([
        'email'    => 'salah@test.com',
        'password' => bcrypt('benar123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'salah@test.com',
        'password' => 'salah456',
    ]);

    $response->assertStatus(401);
});

test('login gagal jika email tidak terdaftar', function () {
    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'tidakada@test.com',
        'password' => 'apapun123',
    ]);

    $response->assertStatus(401);
});
