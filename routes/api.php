<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [App\Http\Controllers\Api\v1\UserAuthenticationController::class, 'register']);
Route::post('login', [App\Http\Controllers\Api\v1\UserAuthenticationController::class, 'auth']);


Route::post('logout', [App\Http\Controllers\Api\v1\UserAuthenticationController::class, 'logout'])->middleware('auth:api');
Route::post('verify-email', [App\Http\Controllers\Api\v1\UserEmailVerifyController::class, 'verifyEmail'])->middleware('auth:api');
Route::post('resend-verify-email', [App\Http\Controllers\Api\v1\UserEmailVerifyController::class, 'resendVerifyEmail'])->middleware('auth:api');

Route::middleware(['auth:api', 'user_verified'])->group(function(){
    Route::get('available-mfa-providers', [App\Http\Controllers\Api\v1\UserMfaController::class, 'index']);
    Route::post('mfa-enable', [App\Http\Controllers\Api\v1\UserMfaController::class, 'store']);
    Route::delete('mfa-enable', [App\Http\Controllers\Api\v1\UserMfaController::class, 'delete']);


    Route::get('user-mfa-providers', [App\Http\Controllers\Api\v1\UserMfaController::class, 'getUserProvider']);
    Route::post('mfa-send', [App\Http\Controllers\Api\v1\UserMfaController::class, 'send']);
    Route::post('mfa-code-verify', [App\Http\Controllers\Api\v1\UserMfaController::class, 'verify']);

    Route::middleware('mfa.verify')->group(function(){
        Route::get('profile', [App\Http\Controllers\Api\v1\UserController::class, 'index']);
        Route::get('wallets', [App\Http\Controllers\Api\v1\UserWalletController::class, 'index']);
        Route::post('wallets', [App\Http\Controllers\Api\v1\UserWalletController::class, 'store']);
        Route::get('wallets/{wallet_id}/denominations', [App\Http\Controllers\Api\v1\DenominationController::class, 'index']);
        Route::post('denomination/add', [App\Http\Controllers\Api\v1\DenominationController::class, 'store']);
        Route::post('denomination/remove', [App\Http\Controllers\Api\v1\DenominationController::class, 'remove']);


        Route::get('transactions', [App\Http\Controllers\Api\v1\TransactionController::class, 'index']);

        Route::get('available-currencies', [App\Http\Controllers\Api\v1\CurrencyController::class, 'index']);
    });
});
