<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait GetUsers
{
    use RefreshDatabase;
    /** @test */
    public function get_users()
    {
        $user = User::factory(5)->withShelvesAndBooks(2, 2)->create();

        $response = $this->withHeader('dummy_authorization_token', 'dummy')
            ->getJson("/api/get_users");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'name',
                        'phone',
                        'created_at',
                        'shelves' => []
                        ]
                    ]
                ]
            );

        $responseData = $response->json();

        $this->api_docs->createApiDoc([
            'endpoint' => "/api/get_users",
            'method' => 'GET',
            'description' => 'Retrieves all users',
            'response' => $responseData,
        ]);
    }

    /** @test */
    public function fail_to_get_users_without_authorization()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/get_users");

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
    }
/** @test */
    public function can_get_users_with_shelves_and_books()
    {
        $user = User::factory(5)
            ->withShelvesAndBooks(2, 2)
            ->create();

        $response = $this->withHeader('dummy_authorization_token', 'dummy')
            ->getJson("/api/get_users");

        $response->assertStatus(200)
            ->assertJson([]);
    }
}