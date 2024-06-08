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


Route::post('verify-email', [App\Http\Controllers\Api\v1\UserEmailVerifyController::class, 'verifyEmail'])->middleware('auth:api');
Route::post('resend-verify-email', [App\Http\Controllers\Api\v1\UserEmailVerifyController::class, 'resendVerifyEmail'])->middleware('auth:api');

Route::middleware(['auth:api', 'user_verified'])->group(function(){
    Route::get('mfa-providers', [App\Http\Controllers\Api\v1\UserMfaController::class, 'index']);
    Route::post('mfa-enable', [App\Http\Controllers\Api\v1\UserMfaController::class, 'store']);
    Route::delete('mfa-enable', [App\Http\Controllers\Api\v1\UserMfaController::class, 'delete']);

    Route::middleware('mfa.verify')->group(function(){
        Route::get('wallet', [App\Http\Controllers\Api\v1\UserWalletController::class, 'index']);
    });
});
