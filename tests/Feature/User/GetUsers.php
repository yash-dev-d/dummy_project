<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait GetUsers
{
    use RefreshDatabase;

    /** @test */
    public function get_users()
    {
        try {
            $users = User::factory(5)->withShelvesAndBooks(2, 2)->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_users'));

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'email',
                            'name',
                            'phone',
                            'created_at',
                            'shelves' => [],
                        ]
                    ]
                ]);

            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => route('get_users', [], false),
                'method' => 'GET',
                'description' => 'Retrieves all users',
                'response' => $responseData,
            ]);
            foreach ($users as $user) {
                foreach ($user->shelves as $shelf) {
                    $shelf->books()->detach();
                    $shelf->books()->forceDelete();
                    $shelf->forceDelete();
                }
                $user->forceDelete();
            }
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function fail_to_get_users_without_authorization()
    {
        try {
            $response = $this->getJson(route('get_users'));

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
    public function can_get_users_with_shelves_and_books()
    {
        try {
            $users = User::factory(5)
                ->withShelvesAndBooks(2, 2)
                ->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->getJson(route('get_users'));

            $response->assertStatus(200)
                ->assertJson([]);
                foreach ($users as $user) {
                    foreach ($user->shelves as $shelf) {
                        $shelf->books()->detach();
                        $shelf->books()->forceDelete();
                        $shelf->forceDelete();
                    }
                    $user->forceDelete();
                }

        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
