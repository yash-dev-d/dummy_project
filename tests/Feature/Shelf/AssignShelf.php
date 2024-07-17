<?php

namespace Tests\Feature\Shelf;

use App\Models\Shelf;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait AssignShelf
{
    use RefreshDatabase;

    /** @test */
    public function assign_books()
    {
        try {
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create(['user_id' => $user->id]);
            $book = Book::factory()->create();

            $assignData = [
                'shelf_id' => $shelf->id,
                'book_id' => $book->id,
                'user_id' => $user->id
            ];

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->postJson(route('assign_books'), $assignData);

            $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Book assigned successfully'
                ]);

            $responseData = $response->json();

            $this->api_docs->createApiDoc([
                'endpoint' => route('assign_books', [], false),
                'method' => 'POST',
                'description' => 'Assigns Books to Shelf',
                'response' => $responseData,
            ]);
            $shelf->books()->detach($book->id);
          
            Book::find($book->id)->forceDelete();
            Shelf::find($shelf->id)->forceDelete();
            User::find($user->id)->forceDelete();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function assign_books_already_assigned()
    {
        try {
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create(['user_id' => $user->id]);
            $book = Book::factory()->create();
            $shelf->books()->attach($book->id);

            $assignData = [
                'shelf_id' => $shelf->id,
                'book_id' => $book->id,
                'user_id' => $user->id
            ];

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->postJson(route('assign_books'), $assignData);

            $response->assertStatus(422)
                ->assertJson([
                    'error' => 'Validation Error',
                    'message' => 'The book is already assigned to this shelf.'
                ]);
            $shelf->books()->detach($book->id);
          
            Book::find($book->id)->forceDelete();
            Shelf::find($shelf->id)->forceDelete();
            User::find($user->id)->forceDelete();    
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function assign_books_invalid_shelf_id()
    {
        try {
            $book = Book::factory()->create();
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create();
            $assignData = [
                'shelf_id' => $shelf->id,
                'book_id' => $book->id,
                'user_id' => $user->id
            ];

            $response = $this->withHeader('dummy_authorization_token', 'dummy')
                ->postJson(route('assign_books'), $assignData);

            $response->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'shelf_id' => [
                            'The selected shelf id is invalid.'
                        ]
                    ]
                ]);
                
          
                Book::find($book->id)->forceDelete();
                Shelf::find($shelf->id)->forceDelete();
                User::find($user->id)->forceDelete();    
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }

    /** @test */
    public function assign_books_without_authorization()
    {
        try {
            $user = User::factory()->create();
            $shelf = Shelf::factory()->create(['user_id' => $user->id]);
            $book = Book::factory()->create();
            $assignData = [
                'shelf_id' => $shelf->id,
                'book_id' => $book->id,
                'user_id' => $user->id
            ];

            $response = $this->postJson(route('assign_books'), $assignData);

            $response->assertStatus(401)
                ->assertJson([
                    'error' => 'Unauthorized'
                ]);
                
          
            Book::find($book->id)->forceDelete();
            Shelf::find($shelf->id)->forceDelete();
            User::find($user->id)->forceDelete();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->tearDown();
        }
    }
}
