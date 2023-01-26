<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\RevenuesController;
use App\Http\Controllers\UserController;
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
Route::controller(AuthController::class)
    ->prefix('/auth')->group(function () {
        Route::post('/register', 'register')->name('auth-register');
        Route::post('/login', 'login')->name('auth-login');
    });

/**
 *  USER
 *
 */
Route::middleware(['auth:sanctum'])->controller(UserController::class)->prefix('/user')->group(function () {
    Route::get('/', 'index');
    Route::put('/update', 'update');
});

/**
 *  EXPENSES
 *
 */
Route::middleware(['auth:sanctum'])->controller(ExpensesController::class)->prefix('expenses')->group(function () {
    Route::get('/', 'index')->name('expenses.index');
    Route::post('/create', 'create')->name('expenses.create');
    Route::put('/update', 'update');
    Route::delete('/delete', 'delete');
});

/**
 *  REVENUES
 *
 */
Route::middleware(['auth:sanctum'])->controller(RevenuesController::class)->prefix('/revenues')->group(function () {
    Route::get('/', 'index');
    Route::post('/create', 'create');
    Route::put('/update', 'update');
    Route::delete('/delete', 'delete');
});

/**
 *  RESUME
 *
 */
Route::middleware(['auth:sanctum'])->controller(ResumeController::class)->prefix('/resume')->group(function () {
    Route::get('/', 'index');
});
