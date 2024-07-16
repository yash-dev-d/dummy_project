<?php
namespace Tests\Feature\Shelf;

use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait GetShelf
{
    use RefreshDatabase;

    /** @test */
    public function get_shelf()
    {
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('dummy_authorization_token', 'dummy')->getJson("/api/get_shelf/{$shelf->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                'id',
                'name',
                'user_id',
                'created_at',
                "books"]
            ]);

            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => "/api/get_shelf",
                'method' => 'GET',
                'description' => 'Retrieves the shelf',
                'response' => $responseData,
            ]);    
    }

    /** @test */
    public function get_shelf_not_found()
    {
        $response = $this->withHeader('dummy_authorization_token', 'dummy')->getJson('/api/get_shelf/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Shelf not found or deleted'
            ]);
    }

    /**@test */

    public function get_shelf_unathorized()
    {
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/get_shelf/{$shelf->id}");

        $response->assertStatus(401)
        ->assertJson([
            'error' => 'Unauthorized'
        ]);
    }
}
