<?php

namespace Tests\Feature\User;

use App\Classes\ApiDocs;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait CreateUser
{
    use RefreshDatabase;

    /** @test */
    public function create_a_user()
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson('/api/create_user', [
                                 'name' => 'John',
                                 'email' => 'john@example.com',
                                 'phone' => '1234567890'
                             ]);

            $response->assertStatus(201)
                     ->assertJsonStructure([
                         'message',
                         'user' => [
                             'id',
                             'email',
                             'name',
                             'phone',
                             'created_at'
                         ]
                     ]);

            $this->api_docs->createApiDoc([
                'endpoint' => '/api/create_user',
                'method' => 'POST',
                'description' => 'Creates a new user',
                'request_body' => [
                    'name' => 'John',
                    'email' => 'john@example.com',
                    'phone' => '1234567890'
                ],
                'response' => $response->json(),
            ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /** @test */
    public function create_a_user_with_missing_fields()
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson('/api/create_user', []);

            $response->assertStatus(422)
                     ->assertJsonValidationErrors(['name', 'email']);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /** @test */
    public function create_a_user_without_authorization()
    {
        try {
            $response = $this->postJson('/api/create_user', [
                'name' => 'John',
                'email' => 'john@example.com',
                'phone' => '1234567890'
            ]);

            $response->assertStatus(401)
                     ->assertJson([
                         'error' => 'Unauthorized'
                     ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /** @test */
    public function create_a_user_with_invalid_email_format()
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson('/api/create_user', [
                                 'name' => 'John',
                                 'email' => 'invalid_email', 
                                 'phone' => '1234567890'
                             ]);

            $response->assertStatus(422)
                     ->assertJsonValidationErrors(['email']);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
