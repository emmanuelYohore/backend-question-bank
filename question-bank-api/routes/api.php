<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);

Route::controller(UserController::class)->group(function () {
    Route::post('v1/users', 'store');
    Route::get('v1/users', 'index');
    Route::put('v1/users/{id}', 'update');
    Route::get('v1/users/{id}', 'show');
    Route::delete('v1/users/{id}', 'destroy');

});

Route::middleware('auth:api')->group(function () {
     Route::post('v1/logout', [AuthController::class, 'logout']);

 });