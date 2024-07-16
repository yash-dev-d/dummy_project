<?php

namespace Tests\Feature\Shelf;

use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait GetShelves
{
    use RefreshDatabase;

    /** @test */
    public function get_shelves()
    {
        $shelves = Shelf::factory(5)->create();

        $response = $this->withHeader('dummy_authorization_token', 'dummy')
            ->getJson("/api/get_shelves");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'name',
                        'created_at',
                        'user' => [
                            'id',
                            'email',
                            'name',
                            'phone',
                            'created_at',
                        ],
                        'books' => []
                    ]
                ],
                
            ]);

        $responseData = $response->json();

        $this->api_docs->createApiDoc([
            'endpoint' => "/api/get_shelves",
            'method' => 'GET',
            'description' => 'Retrieves all shelves',
            'response' => $responseData,
        ]);
    }

    /** @test */
    public function fail_to_get_shelves_without_authorization()
    {
        $response = $this->getJson("/api/get_shelves");

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
    }
}

