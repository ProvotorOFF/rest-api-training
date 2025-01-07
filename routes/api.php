<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class)->only(['store']);

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function() {
    Route::post('login', 'store')->name('login');
});

Route::post('test', function() {
    return response()->json(['message' => 'Test API Route is working']);
});

require_once __DIR__ . '/api/v1.php';