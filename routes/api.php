<?php

use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);

    // Announcement
    Route::get('/announcements', [AnnouncementController::class, 'index']);

    // Profile
    Route::get('/profile', [PersonalController::class, 'getMe']);
    Route::post('/profile/update', [PersonalController::class, 'updateProfile']);
    Route::post('/profile/update-password', [PersonalController::class, 'updatePassword']);
    Route::get('/profile/logs', [PersonalController::class, 'getLogs']);
    Route::get('/profile/notifications', [PersonalController::class, 'getNotifcations']);
    Route::post('/profile/notifications', [PersonalController::class, 'readedNotifcations']);

    // Media Order
    Route::get('/media-order', [MediaOrderController::class, 'getMediaOrder']);
    Route::get('/media-order/{id}', [MediaOrderController::class, 'singleMediaOrder']);
    Route::post('/media-order/{id}/upload-evidence', [MediaOrderController::class, 'uploadEvidences']);
    Route::post('/media-order/{id}/send-evidence', [MediaOrderController::class, 'sentToVerificator']);
    Route::post('/media-order/delete-evidence/{id}', [MediaOrderController::class, 'deleteEvidence']);

    Route::get('/media/get', [RegisterController::class, 'getMedia']);
    Route::post('/media/update', [RegisterController::class, 'updateMedia']);
});
