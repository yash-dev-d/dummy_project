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
        try {
            $user = User::factory()->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_user', ['id' => $user->id]));

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
                'endpoint' => route('get_user', ['id' => $user->id], false),
                'method' => 'GET',
                'description' => 'Retrieves a user by ID',
                'response' => $response->json(),
            ]);
            $user->forceDelete();  
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function fail_to_get_a_user_with_invalid_id()
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_user', ['id' => 999]));

            $response->assertStatus(404)
                ->assertJson([
                    'error' => 'User not found or deleted'
                ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function fail_to_get_a_user_without_authorization()
    {
        try {
            $user = User::factory()->create();

            $response = $this->getJson(route('get_user', ['id' => $user->id]));

            $response->assertStatus(401)
                ->assertJson([
                    'error' => 'Unauthorized'
                ]);
            $user->forceDelete();    
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
