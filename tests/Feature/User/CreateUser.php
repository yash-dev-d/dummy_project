<?php

namespace Tests\Feature\User;

use App\Classes\ApiDocs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

trait CreateUser
{
    use RefreshDatabase;

    /** @test */
    public function create_a_user()
    {
        try {
            $data = [ 
                'name' => 'John',
                'email' => 'john@example.com',
                'phone' => '1234567890'
            ];

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson(route('create_user'), $data); 

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
                'endpoint' => route('create_user', [], false),
                'method' => 'POST',
                'description' => 'Creates a new user',
                'request_body' => $data,
                'response' => $response->json(),
            ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            User::find($response->json()['user']['id'])->forceDelete();
            $this->tearDown();
        }
    }

    /**
     * @test
     * @dataProvider missingFieldsProvider
     */
    public function create_a_user_with_missing_fields($data, $missingFields)
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson(route('create_user'), $data);

            $response->assertStatus(422)
                     ->assertJsonValidationErrors($missingFields);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    public function missingFieldsProvider()
    {
        return [
            [[], ['name', 'email']], 
            [['name' => 'John'], ['email']], 
            [['email' => 'john@example.com'], ['name']], 
        ];
    }

    /** @test */
    public function create_a_user_without_authorization()
    {
        try {
            $data = [
                'name' => 'John',
                'email' => 'john@example.com',
                'phone' => '1234567890'
            ];

            $response = $this->postJson(route('create_user'), $data);

            $response->assertStatus(401)
                     ->assertJson([
                         'error' => 'Unauthorized'
                     ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function create_a_user_with_invalid_email_format()
    {
        try {
            $data = [
                'name' => 'John',
                'email' => 'invalid_email', 
                'phone' => '1234567890'
            ];
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson(route('create_user'), $data);

            $response->assertStatus(422)
                     ->assertJsonValidationErrors(['email']);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
