<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankItemController;
use App\Http\Controllers\BankItemItemController;
use App\Http\Controllers\EnqueteBankController;
use App\Http\Controllers\EnqueteController;
use App\Http\Controllers\FormatReponseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ModaliteReponseController;
use App\Http\Controllers\RepondantController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('v1/auth/register', [AuthController::class, 'register']);
Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/refreshToken', [AuthController::class, 'refreshToken']);

Route::controller(RepondantController::class)->group(function () {
    Route::post('v1/repondants', 'store');
    Route::get('v1/repondants', 'index');
    Route::put('v1/repondants/{id}', 'update');
    Route::get('v1/repondants/{id}', 'show');
    Route::delete('v1/repondants/{id}', 'destroy');
});

// Public routes for anonymous enquete access
Route::controller(EnqueteController::class)->group(function () {
    Route::get('v1/enquetes/by-url/{url}', 'getByUrl');
});

// Public routes for submitting responses
Route::controller(ReponseController::class)->group(function () {
    Route::post('v1/reponses', 'store');
    Route::get('v1/reponses', 'index');
    Route::put('v1/reponses/{id}', 'update');
    Route::get('v1/reponses/{id}', 'show');
    Route::delete('v1/reponses/{id}', 'destroy');
});

Route::middleware('auth:api')->group(function () {

    Route::post('v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('v1/auth/me', [AuthController::class, 'me']);

    Route::controller(EnqueteController::class)->group(function () {
        Route::get('v1/enquetes', 'index');
        Route::get('v1/enquetes/{id}', 'show');
        Route::post('v1/enquetes', 'store');
        Route::put('v1/enquetes/{id}', 'update');
        Route::delete('v1/enquetes/{id}', 'destroy');

        Route::post('v1/users/{userId}/enquetes/{enqueteId}/bank-items', 'attachBankItems');
        Route::delete('v1/users/{userId}/enquetes/{enqueteId}/bank-items/detach', 'detachBankItems');                     
        Route::get('v1/users/{userId}/enquetes/{enqueteId}', 'getOneEnqueteForUserId');
        Route::post('v1/users/{userId}/enquetes/{enqueteId}/bank-items/order', 'saveBankItemsOrder');
        Route::get('v1/users/{userId}/enquetes', 'getAllEnqueteForUserId');
    });
    
    Route::controller(FormatReponseController::class)->group(function () {
        Route::post('v1/format-reponses', 'store');
        Route::get('v1/format-reponses', 'index');
        Route::put('v1/format-reponses/{id}', 'update');
        Route::get('v1/format-reponses/{id}', 'show');
        Route::delete('v1/format-reponses/{id}', 'destroy');

    });

    Route::controller(ItemController::class)->group(function () {
        Route::post('v1/items', 'store');
        Route::get('v1/items', 'index');
        Route::put('v1/items/{id}', 'update');
        Route::get('v1/items/{id}', 'show');
        Route::delete('v1/items/{id}', 'destroy');

        Route::get('v1/users/{userId}/items/{itemId}', 'getOneItemForUserId');
        Route::get('v1/users/{userId}/items', 'getAllItemForUserId');

        

    });

    Route::controller(ModaliteReponseController::class)->group(function () {
        Route::post('v1/modalite-reponses', 'store');
        Route::get('v1/modalite-reponses', 'index');
        Route::put('v1/modalite-reponses/{id}', 'update');
        Route::get('v1/modalite-reponses/{id}', 'show');
        Route::delete('v1/modalite-reponses/{id}', 'destroy');

    });

    Route::controller(BankItemController::class)->group(function () {
        Route::get('v1/bank-items', 'index');
        Route::get('v1/bank-items/{id}', 'show');
        Route::put('v1/bank-items/{id}', 'update');      
        Route::delete('v1/bank-items/{id}', 'destroy');
        Route::post('v1/bank-items', 'store');
        
        Route::post('v1/users/{userId}/bank-items/{bankItemId}/items/order', 'saveItemsOrder');

        Route::get('v1/users/{userId}/bank-items/{bankItemId}', 'getOneBankItemForUserId');
        Route::get('v1/users/{userId}/bank-items', 'getAllBankItemForUserId');
        Route::post('v1/users/{userId}/bank-items/{bankItemId}/items', 'attachItems');        
        Route::delete('v1/users/{userId}/bank-items/{bankItemId}/items/detach', 'detachItems');
    });

    Route::controller(UserController::class)->group(function () {
        Route::post('v1/users', 'store');
        Route::get('v1/users', 'index');
        Route::put('v1/users/{id}', 'update');
        Route::get('v1/users/{id}', 'show');
        Route::delete('v1/users/{id}', 'destroy');

    });

   
    Route::controller(BankItemItemController::class)->group(function () {
        Route::post('v1/bank-item-items', 'store');
        Route::get('v1/bank-item-items', 'index');
        Route::put('v1/bank-item-items/{id}', 'update');
        Route::get('v1/bank-item-items/{id}', 'show');
        Route::delete('v1/bank-item-items/{id}', 'destroy');
    });

    Route::controller(EnqueteBankController::class)->group(function () {
        Route::post('v1/enquete-banks', 'store');
        Route::get('v1/enquete-banks', 'index');
        Route::put('v1/enquete-banks/{id}', 'update');
        Route::get('v1/enquete-banks/{id}', 'show');
        Route::delete('v1/enquete-banks/{id}', 'destroy');

    });
 });