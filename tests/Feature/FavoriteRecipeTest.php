<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Helper login
function loginForFavorite(): string
{
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $res = test()->postJson('/api/v1/auth/login', [
        'email'    => $user->email,
        'password' => 'password123',
    ]);

    return $res->json('data.token');
}

// ─── LIHAT FAVORIT ───────────────────────────────────────────────────────────

test('user yang login dapat melihat daftar resep favorit', function () {
    $token = loginForFavorite();

    $response = $this->withToken($token)->getJson('/api/v1/favorite-recipes');

    $response->assertStatus(200);
});

test('daftar favorit awalnya kosong untuk user baru', function () {
    $token = loginForFavorite();

    $response = $this->withToken($token)->getJson('/api/v1/favorite-recipes');

    expect($response->json('data'))->toBeEmpty();
});

// ─── SIMPAN FAVORIT ──────────────────────────────────────────────────────────

test('user dapat menyimpan resep ke daftar favorit', function () {
    $token = loginForFavorite();

    $response = $this->withToken($token)
                     ->postJson('/api/v1/favorite-recipes', [
                         'recipe_id' => 648438,
                         'title'     => 'Tomato and Egg Scramble',
                         'image'     => 'https://spoonacular.com/recipeImages/648438-312x231.jpg',
                     ]);

    $response->assertStatus(200)
             ->assertJsonPath('status', 'success');

    $this->assertDatabaseHas('bookmarks', ['spoonacular_recipe_id' => 648438]);
});

test('menyimpan resep yang sama dua kali menghasilkan error 409', function () {
    $token = loginForFavorite();

    $payload = [
        'recipe_id' => 999999,
        'title'     => 'Resep Duplikat',
        'image'     => 'https://example.com/img.jpg',
    ];

    $this->withToken($token)->postJson('/api/v1/favorite-recipes', $payload);
    $response = $this->withToken($token)->postJson('/api/v1/favorite-recipes', $payload);

    $response->assertStatus(409);
});

// ─── HAPUS FAVORIT ───────────────────────────────────────────────────────────

test('user dapat menghapus resep dari daftar favorit', function () {
    $token = loginForFavorite();

    $this->withToken($token)->postJson('/api/v1/favorite-recipes', [
        'recipe_id' => 777777,
        'title'     => 'Resep Yang Akan Dihapus',
        'image'     => 'https://example.com/img.jpg',
    ]);

    $response = $this->withToken($token)
                     ->deleteJson('/api/v1/favorite-recipes/777777');

    $response->assertStatus(200);
    $this->assertDatabaseMissing('bookmarks', ['spoonacular_recipe_id' => 777777]);
});

// ─── AKSES TANPA LOGIN ────────────────────────────────────────────────────────

test('user yang tidak login tidak bisa melihat daftar favorit', function () {
    $this->getJson('/api/v1/favorite-recipes')->assertStatus(401);
});

test('user yang tidak login tidak bisa menyimpan resep favorit', function () {
    $this->postJson('/api/v1/favorite-recipes', [
        'recipe_id' => 123,
        'title'     => 'Test',
        'image'     => 'https://example.com/img.jpg',
    ])->assertStatus(401);
});
