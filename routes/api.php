<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\QueueController;
use App\Http\Controllers\API\QueueSubscriptionController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\UserController;
use App\Http\Resources\ReminderCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::middleware('throttle:api')->group(function () {
    Route::get('/', function () {
        return [
            'success' => true,
            'version' => '1.0.0',
        ];
    });
});

Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('auth/google/login', [AuthController::class, 'googleLogin'])->name('auth.google.login');
Route::post('auth/decrypt', [AuthController::class, 'decrypt'])->name('auth.decrypt');
Route::get('auth/emails/{user}/{token}/verify', [AuthController::class, 'verify'])->name('auth.emails.verify');
Route::post('auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
Route::post('auth/forget-password', [AuthController::class, 'forgetPassword'])->name('auth.forget-password');
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')
    ->name('auth.logout');

Route::get('users/withTrashedPaginate', [UserController::class, 'getAllCustomerWithTrashedPaginate'])->middleware('auth:sanctum')
    ->name('users.withTrashedPaginate');
Route::get('users/withTrashed', [UserController::class, 'getAllCustomerWithTrashed'])->middleware('auth:sanctum')
    ->name('users.withTrashed');
Route::patch('users/{id}/restore', [UserController::class, 'restore'])->middleware('auth:sanctum')
    ->name('users.restore');
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::put('users/{user}/password', [UserController::class, 'updatePassword'])
    ->middleware('auth:sanctum')
    ->name('users.update.password');
Route::post('users/{user}/avatar', [UserController::class, 'updateAvatar'])
    ->middleware('auth:sanctum')
    ->name('users.update.avatar');
Route::get('users/{user}/shop', [UserController::class, 'showShop'])->middleware('auth:sanctum')
    ->name('users.show.shop');

Route::get('shops/filter', [ShopController::class, 'filterShop']);
Route::patch('shops/{id}/restore', [ShopController::class, 'restore'])->middleware('auth:sanctum')
    ->name('shops.restore');
Route::get('shops/withTrashed', [ShopController::class, 'getAllShopWithTrashed'])->middleware('auth:sanctum')
    ->name('shops.withTrashed');
Route::get('shops/search', [ShopController::class, 'searchShops'])->middleware('auth:sanctum')->name('shops.search');
Route::get('shops/search/f', [ShopController::class, 'searchShopsWithFilters'])->middleware('auth:sanctum')->name('shops.search.filters');

Route::get('shops/location/nearby', [ShopController::class, 'showNearbyShops'])->middleware('auth:sanctum');;
Route::apiResource('shops', ShopController::class)->middleware('auth:sanctum');
Route::post('shops/{shop}/avatar', [ShopController::class, 'updateAvatar'])->middleware('auth:sanctum')
    ->name('shops.update.avatar');
Route::put('shops/{shop}/is-open', [ShopController::class, 'updateIsOpen'])->middleware('auth:sanctum')
    ->name('shops.update.is-open');
Route::put('shops/{id}/location', [ShopController::class, 'updateLocation'])->middleware('auth:sanctum')
    ->name('shops.update.location');
Route::post('shops/{shop}/item', [ShopController::class, 'storeItem'])->middleware('auth:sanctum')
    ->name('shops.store.item');
Route::put('shops/{shop}/item', [ShopController::class, 'updateItem'])->middleware('auth:sanctum')
    ->name('shops.update.item');
Route::get('shops/{shop}/recommend-items', [ShopController::class, 'showShopItems'])->middleware('auth:sanctum')
    ->name('shops.show.recommend-items');
Route::get('shops/{shop}/item', [ShopController::class, 'showItem'])->middleware('auth:sanctum')
    ->name('shops.show.item');

Route::get('images/{image}', [ImageController::class, 'show'])->name('images.show');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('queues/getAllQueuesReserved', [QueueController::class, 'getQueueReserved']);
    Route::get('queues/getQueueReservedWaiting', [QueueController::class, 'getQueueReservedWaiting']);
    Route::get('queues/getShopStat', [QueueController::class, 'getShopStat']);
    Route::get('queues/getAllQueuesAllShops', [QueueController::class, 'getAllQueuesAllShops']);
    Route::post('queues/{queue}', [QueueController::class,'update'])->middleware('auth:sanctum');
    Route::apiResource('queues', QueueController::class);

    Route::prefix('queues/{queue_id}')->group(function () {
        Route::post('join', [QueueController::class, 'joinQueue']);
        Route::post('status', [QueueController::class, 'status']);
        Route::post('cancel', [QueueController::class, 'cancel']);
        Route::post('next', [QueueController::class, 'next']);
        Route::get('getAllQueue', [QueueController::class, 'getAllQueues']);
        Route::get('getQueueNumber', [QueueController::class, 'getQueueNumber']);
        Route::get('getCompleteQueue', [QueueController::class, 'getCompleteQueue']);
    });




Route::get('/shops/reminders/{shop_id}', [ReminderController::class, 'show'])->name('reminders.show');
Route::post('/shops/reminders', [ReminderController::class, 'store'])->name('reminders.store');
Route::patch('/shops/reminders/{shop_id}', [ReminderController::class, 'markAsDone'])->name('reminders.update');



    Route::apiResource('reminders', ReminderCollection::class);
});


Route::get('redis_key', function () {
    return Redis::keys("*");
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/test/redisConnection", [QueueController::class, 'testConnection']);
Route::get("/test/checkChannel", [QueueController::class, 'checkPublisherChannel']);

Route::get("/test/origin", function (Request $request) {
    return [
        "origin" =>$request->getSchemeAndHttpHost()
    ];
});
