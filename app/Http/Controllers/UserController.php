<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateShelfRequest;
use App\Http\Requests\AssignBooksRequest;
use App\Http\Requests\CreateBookRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\ShelfResource;
use App\Http\Resources\BookResource;
use App\Models\User;
use App\Models\Shelf;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function createUser(CreateUserRequest $request)
    {
    try {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User successfully created',
            'user' => new UserResource($user)  
        ], 201); // success 201 code
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage()
        ], 500);  // server error 500 code
    }
    }

    public function getUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found or deleted'], 404);
        }

        try {
            $loadBooks = filter_var($request->query('load_books', false), FILTER_VALIDATE_BOOLEAN);

            if ($loadBooks) {
                $user->load('shelves.books');  
            }

            return new UserResource($user); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500); // server error 500 code
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found or deleted'], 404);
        }

        try {
            $user->delete();
            return response()->json(['message' => 'User successfully deleted'], 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createShelf(CreateShelfRequest $request)
    {
        try {
            $shelf = Shelf::create($request->validated());
            return response()->json([
                'message' => 'Shelf successfully created',
                'shelf' => new ShelfResource($shelf)
            ], 201); // success 201 code
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500); // server error 500 code
        }
    }

    public function getShelf(Request $request, $id)
    {
        try {
            $shelf = Shelf::with('books')->find($id);

            if (!$shelf) {
                return response()->json(['error' => 'Shelf not found or deleted'], 404);
            }

            return new ShelfResource($shelf); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500); // server error 500 code
        }
    }

    public function assignBooks(AssignBooksRequest $request)
    {
        try {
            $isAssigned = DB::table('shelf_has_books')
                ->where('shelf_id', $request->input('shelf_id'))
                ->where('book_id', $request->input('book_id'))
                ->exists();

            if ($isAssigned) {
                return response()->json([
                    'error' => 'Validation Error',
                    'message' => 'The book is already assigned to this shelf.'
                ], 422);
            }

            $shelf = Shelf::find($request->input('shelf_id'));
            $shelf->books()->attach($request->input('book_id'));

            return response()->json([
                'message' => 'Book assigned successfully'
            ], 201); // single message confirmation response
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500); // server error 500 code
        }
    }

    public function getShelves(Request $request)
{
    try {
        $perPage = $request->input('per_page', 5);
        $shelves = Shelf::with(['books', 'user'])->paginate($perPage);

        return ShelfResource::collection($shelves); 
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function getUsers(Request $request)
{
    try {
        $perPage = $request->input('per_page', 3);
        $users = User::with('shelves.books')->paginate($perPage);

        return UserResource::collection($users); 
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function createBook(CreateBookRequest $request)
    {
        try {
            $book = Book::create($request->validated());

            return new BookResource($book); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
