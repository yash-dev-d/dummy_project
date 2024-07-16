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
    
    Route::post('/create_user', [UserController::class, 'createUser']);
    Route::get('/get_user/{id}', [UserController::class, 'getUser']);
    Route::delete('/delete_user/{id}', [UserController::class, 'deleteUser']);
    Route::post('/create_shelf', [UserController::class, 'createShelf']);
    Route::get('/get_shelf/{id}', [UserController::class, 'getShelf']);
    Route::post('/assign_books', [UserController::class, 'assignBooks']);
    Route::get('/get_shelves', [UserController::class, 'getShelves']);
    Route::get('/get_users', [UserController::class, 'getUsers']);

    //extras
    Route::post('/create_book', [UserController::class, 'createBook']);

});