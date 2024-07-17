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
        try {
            $user = User::factory()->create();

            $shelfData = [
                'name' => 'New Shelf',
                'user_id' => $user->id,
            ];

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson(route('create_shelf'), $shelfData);

            $response->assertStatus(201)
                     ->assertJsonStructure([
                         'message',
                         'shelf' => [
                             'id',
                             'name',
                             'user_id',
                             'created_at'
                         ]
                     ]);

            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => route('create_shelf', [], false),
                'method' => 'POST',
                'description' => 'Creates Shelf',
                'response' => $responseData,
            ]);
            $shelfId = $response['shelf']['id'];  
            $userId = $response['shelf']['user_id']; 
    
            
            Shelf::find($shelfId)->forceDelete();
            User::find($userId)->forceDelete();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /**
     * @test
     * @dataProvider shelfDataProvider
     */
    public function create_shelf_validation($shelfData, $expectedErrors)
    {
        try {
            $user = User::factory()->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->postJson(route('create_shelf'), array_merge($shelfData, ['user_id' => $user->id]));

            $response->assertStatus(422)
                     ->assertJsonValidationErrors($expectedErrors);

            $user->forceDelete();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    public function shelfDataProvider()
    {
        return [
            [
                ['name' => null], 
                ['name'],
            ],
        ];
    }
    /** @test */
    public function create_shelf_without_token()
    {
        try {
            $user = User::factory()->create();

            $shelfData = [
                'name' => 'New Shelf',
                'user_id' => $user->id,
            ];

            $response = $this->postJson(route('create_shelf'), $shelfData);

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
