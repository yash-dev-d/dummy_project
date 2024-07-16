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
        $user = User::factory()->create();

        $response = $this->withHeader('dummy_authorization_token', 'dummy')
            ->deleteJson("/api/delete_user/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User successfully deleted'
            ]);
    }
}
