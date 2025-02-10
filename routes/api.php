<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MediaOrderController;
use App\Http\Controllers\API\PersonalController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/serverCheck', [AuthController::class, 'serverCheck']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updateFcmToken', [AuthController::class, 'updateFcmToken']);

    // Profile
    Route::get('/profile', [PersonalController::class, 'getMe']);
    Route::post('/profile/update', [PersonalController::class, 'updateProfile']);
    Route::post('/profile/update-password', [PersonalController::class, 'updatePassword']);
    Route::get('/profile/logs', [PersonalController::class, 'getLogs']);
    Route::get('/profile/notifications', [PersonalController::class, 'getNotifcations']);

    // Media Order
    Route::get('/media-order', [MediaOrderController::class, 'getMedia']);

    Route::get('/media/get', [RegisterController::class, 'getMedia']);
    Route::post('/media/update', [RegisterController::class, 'updateMedia']);
});
