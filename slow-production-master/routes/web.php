<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|-----------------------------------------------------------------------
| Task 1 Authorization. 
| You can modify the accessibility of your routes for different users here
|-----------------------------------------------------------------------
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('register', [HomeController::class, 'register'])->name('register');
Route::post('register', [HomeController::class, 'doRegister'])->name('doRegister');
Route::post('login', [HomeController::class, 'doLogin'])->name('doLogin');
Route::get('log-out', [HomeController::class, 'logOut'])->name('logOut');

Route::group(['prefix' => 'posts', 'as' => 'posts.', 'middleware' => 'auth'], function ()
{
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::post('{post}/like', [PostController::class, 'like'])->name('like');
    Route::post('{post}/dislike', [PostController::class, 'dislike'])->name('dislike');
    Route::delete('{post}', [PostController::class, 'delete'])->name('delete');
});
