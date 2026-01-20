<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankItemController;
use App\Http\Controllers\EnqueteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('v1/auth/register', [AuthController::class, 'register']);
Route::post('v1/auth/login', [AuthController::class, 'login']);

Route::controller(UserController::class)->group(function () {
    Route::post('v1/users', 'store');
    Route::get('v1/users', 'index');
    Route::put('v1/users/{id}', 'update');
    Route::get('v1/users/{id}', 'show');
    Route::delete('v1/users/{id}', 'destroy');

});

Route::controller(EnqueteController::class)->group(function () {
    Route::get('v1/enquetes', 'index');
    Route::get('v1/enquetes/{id}', 'show');
});

Route::controller(BankItemController::class)->group(function () {
    Route::get('v1/bank-items', 'index');
    Route::get('v1/bank-items/{id}', 'show');
});


Route::middleware('auth:api')->group(function () {

    Route::post('v1/logout', [AuthController::class, 'logout']);

    Route::controller(EnqueteController::class)->group(function () {
        Route::post('v1/enquetes', 'store');
        Route::put('v1/enquetes/{id}', 'update');
        Route::delete('v1/enquetes/{id}', 'destroy');       
        Route::get('v1/users/{userId}/enquetes/{enqueteId}', 'getOneEnqueteForUserId');
        Route::get('v1/users/{userId}/enquetes', 'getAllEnqueteForUserId');
    });

    Route::controller(BankItemController::class)->group(function () {
        Route::post('v1/bank-items', 'store');      
        Route::put('v1/bank-items/{id}', 'update');      
        Route::delete('v1/bank-items/{id}', 'destroy');
});
 });