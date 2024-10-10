<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/password/forgot', 'AuthController@forgotPassword');
Route::post('/password/reset', 'AuthController@resetPassword');
Route::get('/user', 'AuthController@getUser')->middleware('auth:sanctum');
