<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DependenceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tags',TagController::class);
    Route::apiResource('expenses',DependenceController::class);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/user',[UserController::class,'show']);
});
Route::apiResource('group',GroupController::class);