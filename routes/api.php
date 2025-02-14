<?php

use App\Http\Controllers\API\Auth\AuthenticateController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\QueueController;
use App\Http\Controllers\API\QueueSubscriptionController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/', function () {
        return [
            'success' => true,
            'version' => '1.0.0',
        ];
    });
});

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::put('users/{user}/password', [UserController::class, 'updatePassword'])
    ->middleware('auth:sanctum')
    ->name('users.update.password');

Route::apiResource('shops', ShopController::class)->middleware('auth:sanctum');

Route::get('images/{image}', [ImageController::class, 'show'])->name('images.show');

Route::get('queues/{queue_id}', [QueueController::class, 'getAllQueues'])->middleware('auth:sanctum');
Route::apiResource('queues', QueueController::class)->middleware('auth:sanctum');

Route::post('queues/{queue_id}/join', [QueueController::class, 'joinQueue'])->middleware('auth:sanctum');
Route::get('queues/{queue_id}/status', [QueueController::class, 'status'])->middleware('auth:sanctum');
Route::post('queues/{queue_id}/cancel', [QueueController::class, 'cancel'])->middleware('auth:sanctum');
Route::post('queues/{queue_id}/next', [QueueController::class, 'next'])->middleware('auth:sanctum');

Route::get('/subscribe', [QueueSubscriptionController::class, 'subscribe'])->middleware('auth:sanctum');

Route::post('login', [AuthenticateController::class, 'login'])->name('user.login');
Route::post('shop/login', [AuthenticateController::class, 'loginShop'])->name('shop.login');
Route::post('register', [AuthenticateController::class, 'register'])->name('user.register');

Route::get('auth/google', [AuthenticateController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthenticateController::class, 'handleGoogleCallback'])->name('auth.google.callback');








