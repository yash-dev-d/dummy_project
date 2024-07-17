<?php

namespace Tests\Feature\Shelf;
use App\Models\User;
use App\Models\Shelf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait GetShelves
{
    use RefreshDatabase;

    /** @test */
    public function get_shelves()
    {
        try {
            $shelves = Shelf::factory(5)->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_shelves'));

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
                'endpoint' => route('get_shelves', [], false),
                'method' => 'GET',
                'description' => 'Retrieves all shelves',
                'response' => $responseData,
            ]);
        $userIds = $shelves->pluck('user_id')->unique();
        foreach ($shelves as $shelf) {
            $shelf->forceDelete();
        }
        foreach ($userIds as $userId) {
            User::find($userId)->forceDelete();
        }
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function fail_to_get_shelves_without_authorization()
    {
        try {
            $response = $this->getJson(route('get_shelves'));

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
    public function get_shelves_with_books()
    {
        try {
            $shelves = Shelf::factory(5)->WithBooks(8)->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_shelves'));

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
                'endpoint' => route('get_shelves', [], false),
                'method' => 'GET',
                'description' => 'Retrieves all shelves with books',
                'response' => $responseData,
            ]);
        $userIds = $shelves->pluck('user_id')->unique();
        foreach ($shelves as $shelf) {
            $shelf->books()->detach();
            $shelf->books()->forceDelete();
            $shelf->forceDelete();
        }
        foreach ($userIds as $userId) {
            User::find($userId)->forceDelete();
        }
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
