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
        try {
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create(['user_id' => $user->id]);

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->getJson(route('get_shelf', ['id' => $shelf->id]));

            $response->assertStatus(200)
                     ->assertJsonStructure([
                         'data' => [
                             'id',
                             'name',
                             'user_id',
                             'created_at',
                             'books'
                         ]
                     ]);

            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => route('get_shelf', ['id' => $shelf->id], false),
                'method' => 'GET',
                'description' => 'Retrieves the shelf',
                'response' => $responseData,
            ]);
            $shelfId = $response['data']['id'];  
            $userId = $response['data']['user_id'];
            Shelf::find($shelfId)->forceDelete();
            User::find($userId)->forceDelete();
            
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function get_shelf_not_found()
    {
        try {
            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                             ->getJson(route('get_shelf', ['id' => 999]));

            $response->assertStatus(404)
                     ->assertJson([
                         'error' => 'Shelf not found or deleted'
                     ]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } 
    }

    /** @test */
    public function get_shelf_unauthorized()
    {
        try {
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create(['user_id' => $user->id]);

            $response = $this->getJson(route('get_shelf', ['id' => $shelf->id]));

            $response->assertStatus(401)
                     ->assertJson([
                         'error' => 'Unauthorized'
                     ]);
            $shelf->forceDelete();
            $user->forceDelete();         
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
