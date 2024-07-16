<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait GetUser
{
    use RefreshDatabase;
    /** @test */
    public function get_a_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('dummy_authorization_token', 'dummy')
            ->getJson("/api/get_user/{$user->id}");

        
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->phone,
                'created_at' => $user->created_at->toISOString(),
            ]
        ]);
        $this->api_docs->createApiDoc([
            'endpoint' => "/api/get_user/{$user->id}",
            'method' => 'GET',
            'description' => 'Retrieves a user by ID',
            'response' => $response->json(),
        ]);
    }
     /** @test */
public function fail_to_get_a_user_with_invalid_id()
{
    $response = $this->withHeader('dummy_authorization_token', 'dummy')
                     ->getJson('/api/get_user/999'); 

    $response->assertStatus(404)
             ->assertJson([
                 'error' => 'User not found or deleted'
             ]);
}

 /** @test */
public function fail_to_get_a_user_without_authorization()
{
    $user = User::factory()->create();

    $response = $this->getJson("/api/get_user/{$user->id}");

    $response->assertStatus(401)
             ->assertJson([
                 'error' => 'Unauthorized'
             ]);
}

}