<?php
namespace Tests\Feature\Shelf;

use App\Models\Shelf;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

trait AssignShelf
{
    use RefreshDatabase;

    /** @test */
    public function assign_books()
    {
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create();

        $assignData = [
            'shelf_id' => $shelf->id,
            'book_id' => $book->id,
            'user_id' => $user->id
        ];

        $response = $this->withHeader('dummy_authorization_token', 'dummy')->postJson('/api/assign_books', $assignData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Book assigned successfully'
            ]);
            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => "/api/assign_books",
                'method' => 'POST',
                'description' => 'Assigns Books to Shelf',
                'response' => $responseData,
            ]);    
    }

    /** @test */
    public function assign_books_already_assigned()
    {
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create();
        $shelf->books()->attach($book->id);

        $assignData = [
            'shelf_id' => $shelf->id,
            'book_id' => $book->id,
            'user_id' => $user->id
        ];

        $response = $this->withHeader('dummy_authorization_token', 'dummy')->postJson('/api/assign_books', $assignData);

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'Validation Error',
                'message' => 'The book is already assigned to this shelf.'
            ]);
    }

    /** @test */
    public function assign_books_invalid_shelf_id()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create();
        $assignData = [
            'shelf_id' => $shelf->id, 
            'book_id' => $book->id,
            'user_id' => $user->id
        ];

        $response = $this->withHeader('dummy_authorization_token', 'dummy')->postJson('/api/assign_books', $assignData);

        $response->assertStatus(422)
        ->assertJson([
            'errors' => [
                'shelf_id' => [
                    'The selected shelf id is invalid.'
                ]
            ]
        ]);
    }

    /** @test */
    public function assign_books_without_authorization()
    {
        $user = User::factory()->create();
        $shelf = Shelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create();
        $assignData = [
            'shelf_id' => $shelf->id,
            'book_id' => $book->id, 
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/assign_books', $assignData);

        $response->assertStatus(401)
        ->assertJson([
            'error' => 'Unauthorized'
        ]);
    }
}
