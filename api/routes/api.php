<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// api/routes/api.php

use App\Http\Controllers\ApiController;

Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);

// Middleware for authenticated users
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [ApiController::class, 'getUser']);
    Route::post('/add-friend/{id}', [ApiController::class, 'addFriend']);
    Route::post('/accept-friend/{id}', [ApiController::class, 'acceptFriend']);
    Route::post('/reject-friend/{id}', [ApiController::class, 'rejectFriend']);
    Route::get('/friends', [ApiController::class, 'getFriends']);
    Route::post('/send-message', [ApiController::class, 'sendMessage']);
    Route::get('/messages/{friendId}', [ApiController::class, 'getMessages']);
});
