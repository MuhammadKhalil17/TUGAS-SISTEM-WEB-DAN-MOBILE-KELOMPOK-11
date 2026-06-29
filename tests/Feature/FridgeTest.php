<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Helper: login dan dapatkan token
function loginUser(): array
{
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $res = test()->postJson('/api/v1/auth/login', [
        'email'    => $user->email,
        'password' => 'password123',
    ]);

    return ['token' => $res->json('data.token'), 'user' => $user];
}

// ─── LIHAT ISI KULKAS ─────────────────────────────────────────────────────────

test('user yang login dapat melihat isi kulkas', function () {
    ['token' => $token] = loginUser();

    $response = $this->withToken($token)
                     ->getJson('/api/v1/fridge');

    $response->assertStatus(200)
             ->assertJsonStructure(['data' => ['ingredients']]);
});

test('kulkas awalnya kosong saat user baru daftar', function () {
    ['token' => $token] = loginUser();

    $response = $this->withToken($token)->getJson('/api/v1/fridge');

    expect($response->json('data.ingredients'))->toBeEmpty();
});

// ─── TAMBAH BAHAN ─────────────────────────────────────────────────────────────

test('user dapat menambahkan bahan ke kulkas', function () {
    ['token' => $token] = loginUser();

    $response = $this->withToken($token)
                     ->postJson('/api/v1/fridge', ['name' => 'tomato']);

    $response->assertStatus(200)
             ->assertJsonPath('status', 'success');

    $this->assertDatabaseHas('ingredients', ['name' => 'tomato']);
});

test('tambah bahan gagal jika nama kosong', function () {
    ['token' => $token] = loginUser();

    $response = $this->withToken($token)
                     ->postJson('/api/v1/fridge', ['name' => '']);

    $response->assertStatus(422);
});

// ─── HAPUS SATU BAHAN ─────────────────────────────────────────────────────────

test('user dapat menghapus satu bahan dari kulkas', function () {
    ['token' => $token] = loginUser();

    $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'chicken']);

    $id = $this->withToken($token)
               ->getJson('/api/v1/fridge')
               ->json('data.ingredients.0.id');

    $response = $this->withToken($token)->deleteJson("/api/v1/fridge/{$id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('ingredients', ['id' => $id]);
});

// ─── KOSONGKAN KULKAS ─────────────────────────────────────────────────────────

test('user dapat mengosongkan seluruh isi kulkas', function () {
    ['token' => $token] = loginUser();

    $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'egg']);
    $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'milk']);
    $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'butter']);

    $response = $this->withToken($token)->deleteJson('/api/v1/fridge/clear');

    $response->assertStatus(200);

    $isi = $this->withToken($token)->getJson('/api/v1/fridge')->json('data.ingredients');
    expect($isi)->toBeEmpty();
});

// ─── AKSES TANPA LOGIN ────────────────────────────────────────────────────────

test('user yang tidak login tidak bisa melihat kulkas', function () {
    $this->getJson('/api/v1/fridge')->assertStatus(401);
});

test('user yang tidak login tidak bisa menambah bahan', function () {
    $this->postJson('/api/v1/fridge', ['name' => 'egg'])->assertStatus(401);
});
