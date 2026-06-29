<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

// Helper login
function loginForRecipeTest(): string
{
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $res = test()->postJson('/api/v1/auth/login', [
        'email'    => $user->email,
        'password' => 'password123',
    ]);

    return $res->json('data.token');
}

// ─── PENCARIAN RESEP ──────────────────────────────────────────────────────────

test('user yang login dapat mencari resep berdasarkan array bahan baku', function () {
    $token = loginForRecipeTest();

    // Mock Spoonacular API response
    Http::fake([
        'api.spoonacular.com/recipes/findByIngredients*' => Http::response([
            [
                'id' => 12345,
                'title' => 'Fried Egg',
                'image' => 'https://example.com/egg.jpg',
                'usedIngredientCount' => 1,
                'missedIngredientCount' => 0,
            ]
        ], 200)
    ]);

    $response = $this->withToken($token)
                     ->postJson('/api/v1/recipes/search', [
                         'ingredients' => ['egg']
                     ]);

    $response->assertStatus(200);
});

// ─── DETAIL RESEP ─────────────────────────────────────────────────────────────

test('user yang login dapat melihat instruksi detail resep', function () {
    $token = loginForRecipeTest();

    // Mock Spoonacular API detail response
    Http::fake([
        'api.spoonacular.com/recipes/12345/information*' => Http::response([
            'id' => 12345,
            'title' => 'Fried Egg',
            'readyInMinutes' => 10,
            'servings' => 1,
            'instructions' => 'Heat pan, crack egg, cook.',
            'extendedIngredients' => ['1 egg', '1 tsp oil']
        ], 200)
    ]);

    $response = $this->withToken($token)
                     ->getJson('/api/v1/recipes/12345/details');

    $response->assertStatus(200);
});


// ─── AKSES TANPA LOGIN ────────────────────────────────────────────────────────

test('user yang tidak login diblokir dari pencarian resep', function () {
    $response = $this->postJson('/api/v1/recipes/search', [
        'ingredients' => ['egg']
    ]);

    $response->assertStatus(401);
});

test('user yang tidak login diblokir dari melihat detail resep', function () {
    $response = $this->getJson('/api/v1/recipes/12345/details');

    $response->assertStatus(401);
});
