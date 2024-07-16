<?php
namespace Tests\Feature\Shelf;

use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait CreateShelf
{
    use RefreshDatabase;

    /** @test */
    public function create_shelf()
    {
        $user = User::factory()->create();

        $shelfData = [
            'name' => 'New Shelf',
            'user_id' => $user->id,
        ];

        $response =$this->withHeader('dummy_authorization_token', 'dummy')
                                    ->postJson('/api/create_shelf', $shelfData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'user_id',
                    'created_at'
                ]
            ]);
            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => "/api/create_shelf",
                'method' => 'POST',
                'description' => 'Creates Shelf',
                'response' => $responseData,
            ]);    
    }

    /** @test */
    public function create_shelf_without_name()
    {
        $user = User::factory()->create();

        $shelfData = [
            'user_id' => $user->id,
        ];

        $response = $this->withHeader('dummy_authorization_token', 'dummy')->postJson('/api/create_shelf', $shelfData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name'
                ]
            ]);
    }

    /** @test */

    public function create_shelf_without_token(){
        $user = User::factory()->create();

        $shelfData = [
            'name' => 'New Shelf',
            'user_id' => $user->id,
        ];

        $response =$this->postJson('/api/create_shelf', $shelfData);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Unauthorized'
                 ]);

    }
}
