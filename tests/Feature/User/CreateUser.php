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
    }
    /** @test */
    public function create_a_user_with_missing_fields()
    {
        
        $response = $this->withHeader('dummy_authorization_token', 'dummy')
                         ->postJson('/api/create_user', []);

        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function create_a_user_without_authorization()
    {
        
        $response = $this->postJson('/api/create_user', [
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ]);

        
        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Unauthorized'
                 ]);
    }

    /** @test */
    public function create_a_user_with_invalid_email_format()
    {
        
        $response = $this->withHeader('dummy_authorization_token', 'dummy')
                         ->postJson('/api/create_user', [
                             'name' => 'John',
                             'email' => 'invalid_email', 
                             'phone' => '1234567890'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
