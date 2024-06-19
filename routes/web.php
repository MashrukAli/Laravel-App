<?php

use App\Models\User;
use GuzzleHttp\Middleware;
use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\user_controller;
use App\Http\Controllers\FollowController;

Route::get('/admins-only', function() {
    return 'only admins can see this page';
})->middleware('can:visitAdminPages');

//User controller routes
Route::get('/', [user_controller::class, "showCorrectHomepage"])->name('login');

Route::post('/register', [user_controller::class, 'register'])->middleware('guest');

Route::post('/login', [user_controller::class, 'login'])->middleware('guest');

Route::post('/logout', [user_controller::class, 'logout'])->middleware('auth');

Route::get('/manage-avatar', [user_controller::class, 'showAvatarForm']);
Route::post('/manage-avatar', [user_controller::class, 'storeAvatar']);

//follow routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow']);
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow']);

//Blog routes

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');

Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('auth');

Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);

Route::delete('/post/{post}', [PostController::class, 'delete']);

Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update, post');
Route::put('/post/{post}', [PostController::class. 'actuallyUpdate'])->middleware('can:update,post');

Route::get('/search/{term}', [PostController::class, 'search']);
//Profile Routes

Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
    Route::get('/profile/{user:username}', [user_controller::class, 'profile']);
    Route::get('/profile/{user:username}/followers', [user_controller::class, 'profileFollowers']); 
    Route::get('/profile/{user:username}/following', [user_controller::class, 'profileFollowing']); 
});
Route::get('/profile/{user:username}/raw', [user_controller::class, 'profileRaw'])->middleware('cache.headers:public;max_age=20;etag');
Route::get('/profile/{user:username}/followers/raw', [user_controller::class, 'profileFollowersRaw']); 
Route::get('/profile/{user:username}/following/raw', [user_controller::class, 'profileFollowingRaw']); 

//Chat Routes
Route::post('/send-chat-message', function (Request $request){
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);
    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' =>auth()->user()->username, 'textvalue' => strip_tags($request->textvalue)]))->toOthers();
    return response()->noContent();
}); 