<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait DeleteUser
{
    use RefreshDatabase;

    /** @test */
    public function delete_a_user()
    {
        try {
            $user = User::factory()->create();

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->deleteJson(route('delete_user', ['id' => $user->id]));

            $response->assertStatus(200)
                ->assertJson([
                    'message' => 'User successfully deleted'
                ]);
            $user->forceDelete();    
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function delete_a_non_existing_user()
    {
        try {
            $nonExistingUserId = 9999;

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->deleteJson(route('delete_user', ['id' => $nonExistingUserId]));

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
    public function delete_a_user_without_authorization()
    {
        try {
            $user = User::factory()->create();

            $response = $this->deleteJson(route('delete_user', ['id' => $user->id]));

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
