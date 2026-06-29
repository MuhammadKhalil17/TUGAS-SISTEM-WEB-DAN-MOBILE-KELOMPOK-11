<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class FridgeTest extends TestCase
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
    public function authenticated_user_can_view_empty_fridge()
    {
        $token = $this->loginAndGetToken();

        $response = $this->withToken($token)
                         ->getJson('/api/v1/fridge');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['ingredients']]);
    }

    /** @test */
    public function authenticated_user_can_add_ingredient_to_fridge()
    {
        $token = $this->loginAndGetToken();

        $response = $this->withToken($token)
                         ->postJson('/api/v1/fridge', ['name' => 'tomato']);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('ingredients', ['name' => 'tomato']);
    }

    /** @test */
    public function authenticated_user_can_delete_an_ingredient()
    {
        $token = $this->loginAndGetToken();

        // Tambah dulu
        $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'chicken']);

        // Ambil ID
        $listRes = $this->withToken($token)->getJson('/api/v1/fridge');
        $id = $listRes->json('data.ingredients.0.id');

        // Hapus
        $response = $this->withToken($token)
                         ->deleteJson("/api/v1/fridge/{$id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('ingredients', ['id' => $id]);
    }

    /** @test */
    public function authenticated_user_can_clear_fridge()
    {
        $token = $this->loginAndGetToken();

        $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'egg']);
        $this->withToken($token)->postJson('/api/v1/fridge', ['name' => 'milk']);

        $response = $this->withToken($token)
                         ->deleteJson('/api/v1/fridge/clear');

        $response->assertStatus(200);

        $listRes = $this->withToken($token)->getJson('/api/v1/fridge');
        $this->assertEmpty($listRes->json('data.ingredients'));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_fridge()
    {
        $response = $this->getJson('/api/v1/fridge');
        $response->assertStatus(401);
    }
}
