<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class FavoriteRecipeTest extends TestCase
{
    use RefreshDatabase;

    private function loginAndGetToken(): string
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $res = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        return $res->json('data.token');
    }

    /** @test */
    public function authenticated_user_can_view_favorite_recipes_list()
    {
        $token = $this->loginAndGetToken();

        $response = $this->withToken($token)
                         ->getJson('/api/v1/favorite-recipes');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_save_a_recipe_to_favorites()
    {
        $token = $this->loginAndGetToken();

        $response = $this->withToken($token)
                         ->postJson('/api/v1/favorite-recipes', [
                             'recipe_id' => 648438,
                             'title'     => 'Tomato and Egg Scramble',
                             'image'     => 'https://spoonacular.com/recipeImages/648438-312x231.jpg',
                         ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('bookmarks', ['spoonacular_recipe_id' => 648438]);
    }

    /** @test */
    public function saving_duplicate_recipe_returns_409_conflict()
    {
        $token = $this->loginAndGetToken();

        $payload = [
            'recipe_id' => 999999,
            'title'     => 'Duplicate Recipe Test',
            'image'     => 'https://example.com/img.jpg',
        ];

        // Simpan pertama kali
        $this->withToken($token)->postJson('/api/v1/favorite-recipes', $payload);

        // Simpan lagi — harus conflict
        $response = $this->withToken($token)
                         ->postJson('/api/v1/favorite-recipes', $payload);

        $response->assertStatus(409);
    }

    /** @test */
    public function authenticated_user_can_delete_a_favorite_recipe()
    {
        $token = $this->loginAndGetToken();

        // Simpan dulu
        $this->withToken($token)->postJson('/api/v1/favorite-recipes', [
            'recipe_id' => 777777,
            'title'     => 'Recipe to Delete',
            'image'     => 'https://example.com/img.jpg',
        ]);

        // Hapus berdasarkan recipe_id
        $response = $this->withToken($token)
                         ->deleteJson('/api/v1/favorite-recipes/777777');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('bookmarks', ['spoonacular_recipe_id' => 777777]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_favorites()
    {
        $response = $this->getJson('/api/v1/favorite-recipes');
        $response->assertStatus(401);
    }
}
