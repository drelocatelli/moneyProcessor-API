<?php

use App\Http\Controllers\AuthController;
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

/**
 *  USER AUTHENTICATION
 * 
 */
Route::controller(AuthController::class)->prefix('/auth')->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

/**
 *  USER 
 * 
 */
Route::middleware(['auth:sanctum'])->controller(UserController::class)->prefix('/user')->group(function () {
    Route::get('/', 'index');
    Route::put('/edit', 'edit');
});