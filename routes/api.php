<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
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

Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found'
    ], 404);
})->name('api.fallback.404');

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('tasks', TaskController::class)->only(
        ['index', 'update', 'store', 'show', 'destroy']
    );
    Route::apiResource('users', UserController::class)->only(
        ['index', 'show']
    );
});
