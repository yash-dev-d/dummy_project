<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/success', function () {
    return response()->json(['success_message' => 'yes success']);
});


use App\Http\Controllers\UserController;

Route::middleware(['dummyAuthMiddleware'])->group(function () {
    
    Route::post('/create_user', [UserController::class, 'createUser'])->name("create_user");
    Route::get('/get_user/{id}', [UserController::class, 'getUser'])->name("get_user");
    Route::delete('/delete_user/{id}', [UserController::class, 'deleteUser'])->name("delete_user");
    Route::post('/create_shelf', [UserController::class, 'createShelf'])->name("create_shelf");
    Route::get('/get_shelf/{id}', [UserController::class, 'getShelf'])->name("get_shelf");
    Route::post('/assign_books', [UserController::class, 'assignBooks'])->name("assign_books");
    Route::get('/get_shelves', [UserController::class, 'getShelves'])->name("get_shelves");
    Route::get('/get_users', [UserController::class, 'getUsers'])->name("get_users");

    //extras
    Route::post('/create_book', [UserController::class, 'createBook'])->name("create_book");

});