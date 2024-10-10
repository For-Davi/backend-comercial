<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum');

Route::group(['prefix' => 'employee', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [UserController::class, 'getAll']);
    Route::put('/update', [UserController::class, 'updateUser']);
    Route::post('/delete', [UserController::class, 'deleteUser']);
});

Route::group(['prefix' => 'client', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{enterprise_id}', [ClientController::class, 'getAll']);
    Route::post('/', [ClientController::class, 'createClient']);
    Route::put('/update', [ClientController::class, 'updateClient']);
    Route::post('/delete', [ClientController::class, 'deleteClient']);
});
